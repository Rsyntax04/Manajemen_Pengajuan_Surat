<?php

namespace App\Services;

use App\Models\SuratMaster;
use App\Models\SuratDetail;
use App\Models\SuratAnggota;
use App\Models\SuratPanitia;
use App\Models\JenisSurat;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use ZipArchive;

class SuratGeneratorService
{
    public function generate($suratId)
    {
        $master = SuratMaster::findOrFail($suratId);

        $details = SuratDetail::where('surat_id', $suratId)
            ->pluck('field_value', 'field_name')
            ->toArray();

        $anggota = SuratAnggota::where('surat_id', $suratId)->get();
        $panitia = SuratPanitia::where('surat_id', $suratId)->get();

        /*
        |-----------------------------------------
        | AMBIL TEMPLATE DARI JENIS SURAT
        |-----------------------------------------
        */
        $jenis = JenisSurat::findOrFail($master->jenis_surat_id);

        $templatePath = storage_path(
            "app/public/template/" . $jenis->template_file
        );

        if (!file_exists($templatePath)) {
            throw new \Exception("Template tidak ditemukan: " . $templatePath);
        }

        $normalizedTemplatePath = $this->normalizeTemplateDocument($templatePath);

        $this->applyPanitiaTableRows($normalizedTemplatePath, $panitia);

        $template = new TemplateProcessor($normalizedTemplatePath);

        /*
        |-----------------------------------------
        | 1. REPLACE FIELD ${}
        |-----------------------------------------
        */
        foreach ($details as $key => $value) {
            $template->setValue($key, $value);
        }

        $this->setTemplateAliases($template, $details);

        /*
        |-----------------------------------------
        | 2. SYSTEM GENERATED VALUES
        |-----------------------------------------
        */
        $tanggal = $master->approved_at ? Carbon::parse($master->approved_at) : Carbon::now();
        $template->setValue('tanggal_ttd', $tanggal->translatedFormat('d F Y'));

        $template->setValue(
            'nomor_surat',
            $this->generateNomorSurat($master, $tanggal)
        );

        $template->setValue('tahun', $tanggal->format('Y'));

        // Signatory details
        $penandatanganNama = $jenis->penandatangan_nama ?? '-';
        $penandatanganNip = $jenis->penandatangan_nip ?? '-';
        $penandatanganJabatan = $jenis->penandatangan_jabatan ?? '-';

        $template->setValue('penandatangan_nama', $penandatanganNama);
        $template->setValue('nama_penandatangan', $penandatanganNama);
        $template->setValue('penandatangan_nip', $penandatanganNip);
        $template->setValue('penandatangan_jabatan', $penandatanganJabatan);
        $template->setValue('jabatan_penandatangan', $penandatanganJabatan);

        /*
        |-----------------------------------------
        | 3. HANDLE TABLE (REPEATER)
        |-----------------------------------------
        */
        $listAnggota = "";

        foreach ($anggota as $i => $row) {
            $listAnggota .= ($i + 1) . ". " . $row->nama . " - " . $row->identitas . "\n";
        }

        $template->setValue('list_anggota', $listAnggota);

        $listPanitia = "";

        foreach ($panitia as $i => $row) {
            $jabatan = $row->jabatan ?? '-';
            $nama = $row->nama ?? '-';
            $identitas = $row->identitas ?? '-';
            $listPanitia .= ($i + 1) . ". " . $jabatan . ", " . $nama . ", " . $identitas . "\n";
        }

        $template->setValue('list_panitia', $listPanitia);
        $template->setValue('panitia_list', $listPanitia);

        $this->setPanitiaPlaceholders($template, $panitia);

        /*
        |-----------------------------------------
        | 4. SAVE FILE (WORD -> PDF)
        |-----------------------------------------
        */
        $baseName = 'surat_' . $master->id . '_' . time();
        $docxName = $baseName . '.docx';
        $pdfName = $baseName . '.pdf';
        
        $docxPath = storage_path("app/public/surat/{$docxName}");
        $pdfPath = storage_path("app/public/surat/{$pdfName}");

        if (!file_exists(storage_path("app/public/surat"))) {
            mkdir(storage_path("app/public/surat"), 0777, true);
        }

        $template->saveAs($docxPath);

        // Convert DOCX to PDF using DomPDF via PhpWord
        \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
        
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($docxPath);
        $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
        $pdfWriter->save($pdfPath);

        $master->update([
            'file_hasil' => $pdfPath
        ]);

        @unlink($normalizedTemplatePath);

        return $pdfPath;
    }

    private function normalizeTemplateDocument($templatePath)
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'surat_tpl_');
        @unlink($tempPath);
        $tempPath .= '.docx';

        $source = new ZipArchive();
        $target = new ZipArchive();

        if ($source->open($templatePath) !== true) {
            throw new \Exception('Gagal membuka template DOCX: ' . $templatePath);
        }

        if ($target->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $source->close();
            throw new \Exception('Gagal membuat salinan template DOCX sementara.');
        }

        for ($i = 0; $i < $source->numFiles; $i++) {
            $name = $source->getNameIndex($i);
            $content = $source->getFromName($name);

            if ($content === false) {
                continue;
            }

            if (str_contains($name, 'word/document.xml')) {
                $content = $this->normalizeTemplatePlaceholders($content);
            }

            $target->addFromString($name, $content);
        }

        $source->close();
        $target->close();

        return $tempPath;
    }

    private function setTemplateAliases($template, $details)
    {
        $aliases = [
            'skema_abdimas' => 'skema_abdimas',
            'periode' => 'periode',
            'nama_kegiatan' => 'nama_kegiatan',
            'penyelenggara' => 'penyelenggara',
            'list_panitia' => 'list_panitia',
        ];

        foreach ($aliases as $alias => $fallbackKey) {
            if (!isset($details[$alias]) && isset($details[$fallbackKey])) {
                $template->setValue($alias, $details[$fallbackKey]);
            }
        }
    }

    private function setPanitiaPlaceholders($template, $panitia)
    {
        if ($panitia->isEmpty()) {
            $template->setValue('jabatan', '-');
            $template->setValue('nama', '-');
            $template->setValue('identitas', '-');
            return;
        }

        $row = $panitia->first();

        $template->setValue('jabatan', $row->jabatan ?? '-');
        $template->setValue('nama', $row->nama ?? '-');
        $template->setValue('identitas', $row->identitas ?? '-');
    }

    private function applyPanitiaTableRows($templatePath, $panitia)
    {
        if ($panitia->isEmpty()) {
            return;
        }

        $zip = new ZipArchive();
        if ($zip->open($templatePath) !== true) {
            return;
        }

        $documentXml = $zip->getFromName('word/document.xml');
        if ($documentXml === false) {
            $zip->close();
            return;
        }

        // Ekstrak SEMUA baris dari tabel
        if (!preg_match('/<w:tbl>(.*?)<\/w:tbl>/s', $documentXml, $tableMatch)) {
            $zip->close();
            return;
        }

        $tableContent = $tableMatch[1];
        
        // Ekstrak semua rows
        if (!preg_match_all('/<w:tr(.*?)<\/w:tr>/s', $tableContent, $rowMatches)) {
            $zip->close();
            return;
        }

        $allRows = $rowMatches[0];
        if (count($allRows) < 2) {
            $zip->close();
            return; // Minimal perlu header + data row
        }

        // Row kedua adalah template (yang berisi placeholder)
        $templateRow = $allRows[1];
        
        // Cek apakah row kedua memang punya placeholder
        if (strpos($templateRow, '${') === false) {
            $zip->close();
            return;
        }

        // Hapus row kedua (template placeholder) dari array
        $headerRows = [$allRows[0]]; // Keep header
        
        // Duplikasi row kedua untuk setiap panitia
        $dataRows = '';
        foreach ($panitia as $row) {
            $renderedRow = $templateRow;
            
            $renderedRow = preg_replace_callback('/\$\{\s*jabatan\s*\}/', function() use ($row) {
                return htmlspecialchars($row->jabatan ?? '-', ENT_XML1);
            }, $renderedRow);
            
            $renderedRow = preg_replace_callback('/\$\{\s*nama\s*\}/', function() use ($row) {
                return htmlspecialchars($row->nama ?? '-', ENT_XML1);
            }, $renderedRow);
            
            $renderedRow = preg_replace_callback('/\$\{\s*identitas\s*\}/', function() use ($row) {
                return htmlspecialchars($row->identitas ?? '-', ENT_XML1);
            }, $renderedRow);
            
            $dataRows .= $renderedRow;
        }

        // Reconstruct table: header + all data rows
        $newTableContent = '';
        foreach ($headerRows as $hRow) {
            $newTableContent .= $hRow;
        }
        $newTableContent .= $dataRows;

        $newTableXml = '<w:tbl>' . preg_replace('/<w:tbl>.*?<\/w:tbl>/s', '', $tableMatch[0], 1) . $newTableContent . '</w:tbl>';
        $documentXml = preg_replace('/<w:tbl>.*?<\/w:tbl>/s', '<w:tbl>' . substr($tableMatch[1], 0, strpos($tableMatch[1], '<w:tr')) . $newTableContent . '</w:tbl>', $documentXml, 1);

        $zip->addFromString('word/document.xml', $documentXml);
        $zip->close();
    }

    private function extractTableRowXml($documentXml)
    {
        if (preg_match('/<w:tbl>(.*?)<w:tr([^>]*)>(.*?)<\/w:tr>(.*?)<\/w:tbl>/s', $documentXml, $matches)) {
            return '<w:tr' . $matches[2] . '>' . $matches[3] . '</w:tr>';
        }

        return null;
    }

    private function normalizeTemplatePlaceholders($content)
    {
        $content = preg_replace_callback('/\{\{([A-Za-z0-9_]+)\}\}/', function ($matches) {
            return '${' . $matches[1] . '}';
        }, $content);

        $content = preg_replace_callback('/\[\[([A-Za-z0-9_]+)\]\]/', function ($matches) {
            return '${' . $matches[1] . '}';
        }, $content);

        $content = preg_replace_callback('/\$\{(?:<[^>]+>|<\/[^>]+>)*([A-Za-z0-9_]+)(?:<[^>]+>|<\/[^>]+>)*\}/', function ($matches) {
            return '${' . $matches[1] . '}';
        }, $content);

        $content = preg_replace_callback('/\$([A-Za-z0-9_]+)/', function ($matches) {
            return '${' . $matches[1] . '}';
        }, $content);

        return $content;
    }

    private function generateNomorSurat($master, $tanggal)
    {
        $tahun = $tanggal->format('Y');
        $bulan = $tanggal->format('n');

        $count = SuratMaster::where('jenis_surat_id', $master->jenis_surat_id)
            ->whereYear('approved_at', $tahun)
            ->whereMonth('approved_at', $bulan)
            ->count();

        $urut = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $bulanRomawi = $romans[(int)$bulan];

        return $urut . "/SPn-IF/FTRC-UKM/".$bulanRomawi."/" . $tahun;
    }

    private function formatAnggota($data)
    {
        $result = [];

        foreach ($data as $i => $row) {
            $result[] = [
                'anggota' => ($i + 1) . ". " . $row->nama
            ];
        }

        return $result;
    }

    private function formatPanitia($data)
    {
        $result = [];

        foreach ($data as $i => $row) {
            $result[] = [
                'panitia' => ($i + 1) . ". " . $row->nama . " - " . $row->jabatan
            ];
        }

        return $result;
    }
}