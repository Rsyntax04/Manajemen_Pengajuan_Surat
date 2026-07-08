<?php
$templatePath = 'storage/app/public/template/1783473459_Template_Surat_Tugas_Kepanitiaan.docx';

if (!file_exists($templatePath)) {
    echo "Template tidak ditemukan\n";
    exit;
}

$zip = new ZipArchive();
if (!$zip->open($templatePath)) {
    echo "Gagal membuka DOCX\n";
    exit;
}

$xml = $zip->getFromName('word/document.xml');
$zip->close();

// Cari tabel
if (preg_match('/<w:tbl>(.*?)<\/w:tbl>/s', $xml, $matches)) {
    $tableXml = $matches[1];
    
    // Hitung jumlah baris
    if (preg_match_all('/<w:tr(.*?)<\/w:tr>/s', $tableXml, $rows)) {
        echo "Total baris di tabel: " . count($rows[0]) . "\n\n";
        
        foreach ($rows[0] as $idx => $row) {
            echo "=== BARIS " . ($idx + 1) . " ===\n";
            
            // Ekstrak text dari sel
            preg_match_all('/<w:t>([^<]*)<\/w:t>/', $row, $texts);
            if (!empty($texts[1])) {
                echo "Isi: " . trim(implode(" | ", $texts[1])) . "\n";
            }
            
            // Cek apakah ada placeholder
            if (strpos($row, '${') !== false) {
                echo "Status: PLACEHOLDER (belum diganti)\n";
            }
            
            echo "\n";
        }
    }
}
