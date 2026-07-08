<?php
$templatePath = 'storage/app/public/template/1783473459_Template_Surat_Tugas_Kepanitiaan.docx';

if (!file_exists($templatePath)) {
    echo "Template tidak ditemukan: $templatePath\n";
    exit;
}

$zip = new ZipArchive();
if (!$zip->open($templatePath)) {
    echo "Gagal membuka DOCX\n";
    exit;
}

$xml = $zip->getFromName('word/document.xml');
$zip->close();

if (!$xml) {
    echo "Gagal membaca document.xml\n";
    exit;
}

// Cari tabel dan ekstrak bagian yang relevan
if (preg_match_all('/<w:tbl>(.*?)<\/w:tbl>/s', $xml, $tables)) {
    echo "Ditemukan " . count($tables[1]) . " tabel\n\n";
    
    foreach ($tables[1] as $idx => $tbl) {
        echo "===== TABEL " . ($idx + 1) . " =====\n";
        
        // Ekstrak dan tampilkan struktur baris
        if (preg_match_all('/<w:tr(.*?)<\/w:tr>/s', $tbl, $rows)) {
            echo "Jumlah baris: " . count($rows[0]) . "\n";
            
            foreach ($rows[0] as $rowIdx => $row) {
                echo "\n--- Baris " . ($rowIdx + 1) . " ---\n";
                
                // Cek apakah row berisi placeholder
                if (strpos($row, '${') !== false || strpos($row, '{') !== false) {
                    echo "Berisi PLACEHOLDER!\n";
                    
                    // Tampilkan potongan XML yang penting
                    preg_match_all('/<w:t>([^<]+)<\/w:t>/', $row, $texts);
                    if (!empty($texts[1])) {
                        echo "Isi sel: " . implode(" | ", $texts[1]) . "\n";
                    }
                    
                    // Tampilkan snippet XML
                    echo "\nSnippet XML (500 char):\n";
                    echo substr($row, 0, 500) . "...\n";
                }
            }
        }
        echo "\n";
    }
}
