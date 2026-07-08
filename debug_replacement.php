<?php
// Simulasi duplikasi dengan 4 data panitia seperti yang user tunjukkan
$panitia = [
    (object)['jabatan' => 'Ketua', 'nama' => 'Ravel Setiady', 'identitas' => '2272003'],
    (object)['jabatan' => 'Ketua', 'nama' => 'Ravel Setiady', 'identitas' => '2272004'],
    (object)['jabatan' => 'Ketua', 'nama' => 'Ravel Setiady', 'identitas' => '2272005'],
    (object)['jabatan' => 'Ketua', 'nama' => 'Ravel Setiady', 'identitas' => '2272006'],
];

$templatePath = 'storage/app/public/template/1783473459_Template_Surat_Tugas_Kepanitiaan.docx';

$zip = new ZipArchive();
if (!$zip->open($templatePath)) {
    echo "Gagal membuka DOCX\n";
    exit;
}

$documentXml = $zip->getFromName('word/document.xml');
$zip->close();

// Debug: ekstrak row asli
if (preg_match('/<w:tbl>(.*?)<w:tr(.*?)>(.*?)<\/w:tr>(.*?)<\/w:tbl>/s', $documentXml, $matches)) {
    $tableStart = $matches[1];
    $firstRowAttrs = $matches[2];
    $rowContent = $matches[3];
    $tableEnd = $matches[4];
    
    $rowXml = '<w:tr' . $firstRowAttrs . '>' . $rowContent . '</w:tr>';
    
    echo "=== ROW ASLI (TEMPLATE) ===\n";
    preg_match_all('/<w:t>([^<]*)<\/w:t>/', $rowXml, $texts);
    echo "Isi: " . trim(implode(" | ", $texts[1])) . "\n\n";
    
    // Simulasi duplikasi untuk setiap panitia
    echo "=== HASIL DUPLIKASI ===\n";
    foreach ($panitia as $idx => $row) {
        $renderedRow = $rowXml;
        
        // Debug sebelum replace
        echo "Panitia " . ($idx + 1) . " - Sebelum replace:\n";
        preg_match_all('/<w:t>([^<]*)<\/w:t>/', substr($renderedRow, 0, 300), $t1);
        echo "  Start: " . trim(implode(" | ", $t1[1])) . "...\n";
        
        // Replace placeholder
        $renderedRow = preg_replace_callback('/\$\{\s*jabatan\s*\}/', function() use ($row) {
            return htmlspecialchars($row->jabatan ?? '-', ENT_XML1);
        }, $renderedRow);
        
        $renderedRow = preg_replace_callback('/\$\{\s*nama\s*\}/', function() use ($row) {
            return htmlspecialchars($row->nama ?? '-', ENT_XML1);
        }, $renderedRow);
        
        $renderedRow = preg_replace_callback('/\$\{\s*identitas\s*\}/', function() use ($row) {
            return htmlspecialchars($row->identitas ?? '-', ENT_XML1);
        }, $renderedRow);
        
        // Debug setelah replace
        echo "Panitia " . ($idx + 1) . " - Setelah replace:\n";
        preg_match_all('/<w:t>([^<]*)<\/w:t>/', $renderedRow, $t2);
        echo "  Hasil: " . trim(implode(" | ", $t2[1])) . "\n";
        
        // Cek placeholder yang tersisa
        if (strpos($renderedRow, '${') !== false) {
            echo "  ⚠️  Placeholder masih ada!\n";
        }
        echo "\n";
    }
}
