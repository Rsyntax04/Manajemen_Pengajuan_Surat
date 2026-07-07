<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Jenis_Surat extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_surat' => 'PERJALANAN_DINAS',
                'nama_jenis' => 'Surat Penugasan Perjalanan Dinas',
                'penandatangan_nama' => 'Dr. Budi Santoso, M.T.',
                'penandatangan_nip' => '197001012000031001',
                'penandatangan_jabatan' => 'Dekan Fakultas',
                'template_file' => 'Template_Surat_Tugas_Perjalanan_Dinas.docx',
                'active_template_file' => 'Template_Surat_Tugas_Perjalanan_Dinas.docx',
            ],
            [
                'kode_surat' => 'KARYA_ILMIAH',
                'nama_jenis' => 'Surat Penugasan Menghasilkan Karya Ilmiah',
                'penandatangan_nama' => 'Dr. Budi Santoso, M.T.',
                'penandatangan_nip' => '197001012000031001',
                'penandatangan_jabatan' => 'Dekan Fakultas',
                'template_file' => 'Template_Surat_Tugas_Publikasi_Karya_Ilmiah.docx',
                'active_template_file' => 'Template_Surat_Tugas_Publikasi_Karya_Ilmiah.docx',
            ],
            [
                'kode_surat' => 'PENGABDIAN',
                'nama_jenis' => 'Surat Penugasan Pengabdian Masyarakat',
                'penandatangan_nama' => 'Dr. Budi Santoso, M.T.',
                'penandatangan_nip' => '197001012000031001',
                'penandatangan_jabatan' => 'Dekan Fakultas',
                'template_file' => 'Template_Surat_Tugas_Abdimas.docx',
                'active_template_file' => 'Template_Surat_Tugas_Abdimas.docx',
            ],
            [
                'kode_surat' => 'PENGEMBANGAN_DIRI',
                'nama_jenis' => 'Surat Penugasan Pengembangan Diri',
                'penandatangan_nama' => 'Prof. Dr. Siti Aminah, M.Si.',
                'penandatangan_nip' => '196502021990032001',
                'penandatangan_jabatan' => 'Ketua Program Studi',
                'template_file' => 'Template_Surat_Tugas_Pengembangan_Diri.docx',
                'active_template_file' => 'Template_Surat_Tugas_Pengembangan_Diri.docx',
            ],
            [
                'kode_surat' => 'KEPANITIAAN',
                'nama_jenis' => 'Surat Penugasan Kepanitiaan',
                'penandatangan_nama' => 'Dr. Budi Santoso, M.T.',
                'penandatangan_nip' => '197001012000031001',
                'penandatangan_jabatan' => 'Dekan Fakultas',
                'template_file' => 'Template_Surat_Tugas_Kepanitiaan.docx',
                'active_template_file' => 'Template_Surat_Tugas_Kepanitiaan.docx',
            ],
            [
                'kode_surat' => 'KEANGGOTAAN_PROFESI',
                'nama_jenis' => 'Keanggotaan Profesi',
                'penandatangan_nama' => 'Prof. Dr. Siti Aminah, M.Si.',
                'penandatangan_nip' => '196502021990032001',
                'penandatangan_jabatan' => 'Ketua Program Studi',
                'template_file' => null,
                'active_template_file' => null,
            ],
            [
                'kode_surat' => 'PENELITIAN',
                'nama_jenis' => 'Surat Penelitian',
                'penandatangan_nama' => 'Dr. Budi Santoso, M.T.',
                'penandatangan_nip' => '197001012000031001',
                'penandatangan_jabatan' => 'Dekan Fakultas',
                'template_file' => null,
                'active_template_file' => null,
            ],
        ];

        foreach ($data as $item) {
            DB::table('jenis_surat')->updateOrInsert(
                ['kode_surat' => $item['kode_surat']],
                $item
            );
        }
    }
}