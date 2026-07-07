<?php

namespace App\Services;

use App\Models\SuratMaster;
use App\Models\SuratDetail;
use App\Models\SuratAnggota;
use App\Models\SuratPanitia;
use App\Models\JenisSurat;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

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

        $template = new TemplateProcessor($templatePath);

        /*
        |-----------------------------------------
        | 1. REPLACE FIELD ${}
        |-----------------------------------------
        */
        foreach ($details as $key => $value) {
            $template->setValue($key, $value);
        }

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
        $template->setValue('penandatangan_nama', $jenis->penandatangan_nama ?? '-');
        $template->setValue('penandatangan_nip', $jenis->penandatangan_nip ?? '-');
        $template->setValue('penandatangan_jabatan', $jenis->penandatangan_jabatan ?? '-');

        /*
        |-----------------------------------------
        | 3. HANDLE TABLE (REPEATER)
        |-----------------------------------------
        */
        $list = "";

        foreach ($anggota as $i => $row) {
            $list .= ($i + 1) . ". " . $row->nama . " - " . $row->identitas . "\n";
        }

        $template->setValue('list_anggota', $list);

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
        return $pdfPath;
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