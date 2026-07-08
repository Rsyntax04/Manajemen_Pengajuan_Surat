@extends('layouts.user')

@section('title', 'Lihat PDF - ' . $pengajuan->jenisSurat->nama_jenis)

@section('content')
<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <div class="p-6 border-b flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pratinjau Surat</h2>
            <p class="text-gray-500 mt-1">{{ $pengajuan->jenisSurat->nama_jenis }}</p>
        </div>
        <div class="flex gap-3">
            <button onclick="toggleSideBySide()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                <span id="viewModeText">Tampilan Berdampingan</span>
            </button>
            <a href="{{ route('user.pengajuan.download', $pengajuan->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                Download PDF
            </a>
            <a href="{{ route('user.history') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- PDF Viewer Container -->
    <div id="pdfContainer" class="w-full" style="height: 600px; overflow: hidden;">
        <div class="flex h-full" id="viewerWrapper">
            <!-- Single Page View (Default) -->
            <div id="singlePageView" class="w-full overflow-auto">
                <div id="pdf-viewer-single" class="flex justify-center p-4">
                    <canvas id="canvas-single"></canvas>
                </div>
            </div>

            <!-- Side-by-Side View -->
            <div id="sideBySideView" class="hidden w-full flex overflow-auto">
                <div class="w-1/2 border-r border-gray-300 overflow-auto p-2">
                    <div id="pdf-viewer-left" class="flex justify-center">
                        <canvas id="canvas-left"></canvas>
                    </div>
                </div>
                <div class="w-1/2 overflow-auto p-2">
                    <div id="pdf-viewer-right" class="flex justify-center">
                        <canvas id="canvas-right"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Controls -->
    <div class="p-6 border-t bg-gray-50 flex justify-between items-center">
        <button onclick="previousPage()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
            ← Sebelumnya
        </button>
        <div class="text-center">
            <span id="pageInfo" class="text-gray-600 font-semibold">Halaman 1 dari 1</span>
        </div>
        <button onclick="nextPage()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
            Berikutnya →
        </button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Setup PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const pdfUrl = '{{ $pdfPath }}';
    let pdfDoc = null;
    let currentPage = 1;
    let isSideBySide = false;

    // Initialize PDF viewer
    document.addEventListener('DOMContentLoaded', function() {
        loadPDF();
    });

    function loadPDF() {
        pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
            pdfDoc = pdf;
            updatePageInfo();
            renderPage(currentPage);
        }).catch(error => {
            console.error('Error loading PDF:', error);
            alert('Gagal memuat PDF. Silakan coba lagi.');
        });
    }

    function renderPage(pageNum) {
        if (!pdfDoc) return;

        if (isSideBySide) {
            renderSideBySide(pageNum);
        } else {
            renderSinglePage(pageNum);
        }
    }

    function renderSinglePage(pageNum) {
        pdfDoc.getPage(pageNum).then(page => {
            const scale = 1.5;
            const viewport = page.getViewport({ scale: scale });
            const canvas = document.getElementById('canvas-single');
            const context = canvas.getContext('2d');

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            page.render(renderContext).promise.then(() => {
                console.log('Single page rendered');
            });
        });
    }

    function renderSideBySide(pageNum) {
        // Render current page on left
        if (pageNum <= pdfDoc.numPages) {
            renderPageToCanvas(pageNum, 'canvas-left');
        }

        // Render next page on right
        if (pageNum + 1 <= pdfDoc.numPages) {
            renderPageToCanvas(pageNum + 1, 'canvas-right');
        }
    }

    function renderPageToCanvas(pageNum, canvasId) {
        pdfDoc.getPage(pageNum).then(page => {
            const scale = 1.3;
            const viewport = page.getViewport({ scale: scale });
            const canvas = document.getElementById(canvasId);
            const context = canvas.getContext('2d');

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            page.render(renderContext).promise.then(() => {
                console.log('Page ' + pageNum + ' rendered on ' + canvasId);
            });
        });
    }

    function toggleSideBySide() {
        isSideBySide = !isSideBySide;
        const singleView = document.getElementById('singlePageView');
        const sideBySideView = document.getElementById('sideBySideView');
        const btn = document.getElementById('viewModeText');

        if (isSideBySide) {
            singleView.classList.add('hidden');
            sideBySideView.classList.remove('hidden');
            btn.textContent = 'Tampilan Tunggal';
            // Adjust current page for side-by-side (show even pages on left)
            if (currentPage % 2 === 0) {
                currentPage = currentPage - 1;
            }
        } else {
            singleView.classList.remove('hidden');
            sideBySideView.classList.add('hidden');
            btn.textContent = 'Tampilan Berdampingan';
        }

        renderPage(currentPage);
        updatePageInfo();
    }

    function nextPage() {
        if (!pdfDoc) return;

        if (isSideBySide) {
            currentPage += 2;
            if (currentPage > pdfDoc.numPages) {
                currentPage = pdfDoc.numPages - 1;
            }
        } else {
            if (currentPage < pdfDoc.numPages) {
                currentPage++;
            }
        }

        renderPage(currentPage);
        updatePageInfo();
    }

    function previousPage() {
        if (!pdfDoc) return;

        if (isSideBySide) {
            currentPage -= 2;
            if (currentPage < 1) {
                currentPage = 1;
            }
        } else {
            if (currentPage > 1) {
                currentPage--;
            }
        }

        renderPage(currentPage);
        updatePageInfo();
    }

    function updatePageInfo() {
        if (!pdfDoc) return;

        let pageText;
        if (isSideBySide) {
            const nextPage = Math.min(currentPage + 1, pdfDoc.numPages);
            pageText = `Halaman ${currentPage} - ${nextPage} dari ${pdfDoc.numPages}`;
        } else {
            pageText = `Halaman ${currentPage} dari ${pdfDoc.numPages}`;
        }

        document.getElementById('pageInfo').textContent = pageText;
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'ArrowRight') {
            nextPage();
        } else if (event.key === 'ArrowLeft') {
            previousPage();
        }
    });
</script>

<style>
    #pdfContainer {
        background-color: #f0f0f0;
    }

    canvas {
        background-color: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
        display: block;
    }
</style>
@endsection