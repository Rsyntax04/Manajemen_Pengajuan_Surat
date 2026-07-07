@extends('layouts.user')

@section('title', 'Edit Pengajuan Surat')

@section('content')

<div class="mb-6">
    <a href="{{ route('user.history') }}" class="text-blue-600 hover:underline">&larr; Kembali ke History</a>
</div>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white p-6 rounded-xl shadow">

    {{-- ===================== --}}
    {{-- INFO & TEMPLATE --}}
    {{-- ===================== --}}
    @if(
    $selectedSurat &&
    in_array($selectedSurat->kode_surat, [
        'PENGABDIAN',
        'KARYA_ILMIAH',
        'PENGEMBANGAN_DIRI',
        'PENELITIAN',
        'PENGAJUAN_PROPOSAL',
        'KEANGGOTAAN_PROFESI',
        'PERJALANAN_DINAS'
    ])
    )

    <div class="mb-6 p-4 border rounded bg-gray-50">
        <p class="font-bold mb-2">📄 Template & Panduan Pengisian</p>
        <p class="text-sm text-gray-600 mb-3">
            Gunakan template berikut untuk menghindari kesalahan format data. Jangan mengubah urutan kolom Excel.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ asset('templates\Contoh_List_Anggota.xlsx') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ⬇ Template Anggota
            </a>
        </div>
        <ul class="list-disc ml-5 text-sm text-gray-600 mt-3">
            <li>File harus format .xlsx</li>
            <li>Jangan merge cell</li>
            <li>Baris pertama adalah header</li>
            <li>Hapus baris kosong sebelum upload</li>
        </ul>
    </div>
    @elseif($selectedSurat && $selectedSurat->kode_surat == 'KEPANITIAAN')

    <div class="mb-6 p-4 border rounded bg-gray-50">
        <p class="font-bold mb-2">📄 Template & Panduan Pengisian</p>
        <p class="text-sm text-gray-600 mb-3">
            Gunakan template berikut untuk menghindari kesalahan format data. Jangan mengubah urutan kolom Excel.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ asset('templates\Contoh_List_Panitia.xlsx') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ⬇ Template Kepanitiaan
            </a>
        </div>
        <ul class="list-disc ml-5 text-sm text-gray-600 mt-3">
            <li>File harus format .xlsx</li>
            <li>Jangan merge cell</li>
            <li>Baris pertama adalah header</li>
            <li>Hapus baris kosong sebelum upload</li>
        </ul>
    </div>
    @endif

    {{-- ===================== --}}
    {{-- FORM DINAMIS --}}
    {{-- ===================== --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('user.pengajuan.update', $pengajuan->id) }}"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <input type="hidden" name="jenis_surat_id" value="{{ $selectedSurat->id }}">

        <h2 class="text-xl font-bold mb-6">
            Edit Pengajuan: {{ $selectedSurat->nama_jenis }}
        </h2>

        @if($pengajuan->status == 'revisi' && $pengajuan->catatan_revisi)
            <div class="mb-6 bg-orange-100 text-orange-800 p-4 rounded border border-orange-200">
                <p class="font-bold">Catatan Revisi:</p>
                <p>{{ $pengajuan->catatan_revisi }}</p>
            </div>
        @endif

        @foreach($fields as $field)
        <div class="mb-6 p-4 border rounded">
            <label class="block font-semibold mb-2">
                {{ ucwords(str_replace('_', ' ', $field->field_name)) }}
            </label>

            @php
                $name = "fields[".$field->id."]";
                $value = $detailValues[$field->field_name] ?? '';
            @endphp

            {{-- TEXT --}}
            @if($field->field_type == 'text')
                <input type="text" name="{{ $name }}" value="{{ old($name, $value) }}"
                       class="border w-full px-3 py-2 rounded">

            {{-- DATE --}}
            @elseif($field->field_type == 'date')
                <input type="date" name="{{ $name }}" value="{{ old($name, $value) }}"
                       class="border w-full px-3 py-2 rounded">

            {{-- TEXTAREA --}}
            @elseif($field->field_type == 'textarea')
                <textarea name="{{ $name }}"
                          class="border w-full px-3 py-2 rounded">{{ old($name, $value) }}</textarea>
        
            {{-- LIST ANGGOTA --}}
            @elseif($field->field_type == 'list_anggota')
                <div class="mb-2 text-sm text-gray-700">
                    * Kosongkan jika tidak ingin mengubah data anggota yang sudah ada. Jika diisi, data lama akan ditimpa.
                </div>
                <input type="file"
                       name="fields[{{ $field->id }}][file]"
                       id="excel_{{ $field->id }}"
                       accept=".xlsx,.xls"
                       data-type="list_anggota"
                       class="border w-full px-3 py-2 rounded"
                       onchange="autoPreviewExcel({{ $field->id }})">

                <p class="text-sm text-gray-500 mt-2">
                    Format: Nama | NIM | Keterangan (opsional)
                </p>
                <div id="preview_{{ $field->id }}" class="mt-3"></div>

            {{-- LIST KEPANITIAAN --}}
            @elseif($field->field_type == 'list_kepanitiaan')
                <div class="mb-2 text-sm text-gray-700">
                    * Kosongkan jika tidak ingin mengubah data kepanitiaan yang sudah ada. Jika diisi, data lama akan ditimpa.
                </div>
                <input type="file"
                       name="fields[{{ $field->id }}][file]"
                       id="excel_{{ $field->id }}"
                       accept=".xlsx,.xls"
                       data-type="list_kepanitiaan"
                       class="border w-full px-3 py-2 rounded"
                       onchange="autoPreviewExcel({{ $field->id }})">

                <p class="text-sm text-gray-500 mt-2">
                    Nama | NRP/NIK | Kepanitiaan | Jabatan
                </p>
                <div id="preview_{{ $field->id }}" class="mt-3"></div>
            @endif
        </div>
        @endforeach

        {{-- SUBMIT --}}
        <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
window.autoPreviewExcel = function(id) {
    const input = document.getElementById('excel_' + id);
    const preview = document.getElementById('preview_' + id);

    if (!input || !preview) return;
    if (!input.files || !input.files[0]) return;

    preview.innerHTML = `<div class="text-gray-500 text-sm p-2">Loading preview...</div>`;

    const file = input.files[0];
    const type = input.dataset.type || 'default';

    const reader = new FileReader();

    reader.onload = function (e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
        const json = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: "" });
        const rows = json.slice(1);

        let headers = [];
        if (type === 'list_anggota') {
            headers = ['Nama', 'NIM / NRP', 'Prodi'];
        } else if (type === 'list_kepanitiaan') {
            headers = ['Nama', 'NRP / NIK', 'Kepanitiaan', 'Masa', 'Jabatan'];
        } else {
            headers = json[0] || ['Col 1', 'Col 2', 'Col 3'];
        }

        let html = `
        <div class="border rounded mt-3 overflow-hidden">
            <div style="max-height: 250px; overflow-y: auto;">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
        `;
        headers.forEach(h => { html += `<th class="border p-2">${h}</th>`; });
        html += `</tr></thead><tbody>`;
        
        rows.forEach(row => {
            html += `<tr>`;
            headers.forEach((_, i) => { html += `<td class="border p-2">${row[i] ?? ''}</td>`; });
            html += `</tr>`;
        });
        
        html += `</tbody></table></div></div>`;
        preview.innerHTML = html;
    };
    reader.readAsArrayBuffer(file);
}
</script>
@endsection
