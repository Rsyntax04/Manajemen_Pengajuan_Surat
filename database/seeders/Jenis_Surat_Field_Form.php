<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Jenis_Surat_Field_Form extends Seeder
{
    public function run(): void
    {
        $surat = DB::table('jenis_surat')->get()->keyBy('kode_surat');

        $fields = [];

        /*
        |--------------------------------------------------------------------------
        | PERJALANAN DINAS
        |--------------------------------------------------------------------------
        */
        $fields = array_merge($fields, [
            [
                'jenis_surat_id' => $surat['PERJALANAN_DINAS']->id,
                'field_name' => 'nama_kegiatan',
                'field_type' => 'text',
                'is_required' => 1,
                'options' => null,
                'urutan' => 1,
            ],
            [
                'jenis_surat_id' => $surat['PERJALANAN_DINAS']->id,
                'field_name' => 'tanggal_mulai',
                'field_type' => 'date',
                'is_required' => 1,
                'options' => null,
                'urutan' => 2,
            ],
            [
                'jenis_surat_id' => $surat['PERJALANAN_DINAS']->id,
                'field_name' => 'tanggal_selesai',
                'field_type' => 'date',
                'is_required' => 1,
                'options' => null,
                'urutan' => 3,
            ],
            [
                'jenis_surat_id' => $surat['PERJALANAN_DINAS']->id,
                'field_name' => 'lokasi',
                'field_type' => 'textarea',
                'is_required' => 1,
                'options' => null,
                'urutan' => 4,
            ],
            [
                'jenis_surat_id' => $surat['PERJALANAN_DINAS']->id,
                'field_name' => 'list_anggota',
                'field_type' => 'list_anggota',
                'is_required' => 1,
                'options' => null,
                'urutan' => 5,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | KARYA ILMIAH
        |--------------------------------------------------------------------------
        */
        $fields = array_merge($fields, [
            [
                'jenis_surat_id' => $surat['KARYA_ILMIAH']->id,
                'field_name' => 'judul_karya_ilmiah',
                'field_type' => 'textarea',
                'is_required' => 1,
                'options' => null,
                'urutan' => 1,
            ],
            [
                'jenis_surat_id' => $surat['KARYA_ILMIAH']->id,
                'field_name' => 'tempat_publikasi',
                'field_type' => 'text',
                'is_required' => 1,
                'options' => null,
                'urutan' => 2,
            ],
            [
                'jenis_surat_id' => $surat['KARYA_ILMIAH']->id,
                'field_name' => 'list_anggota',
                'field_type' => 'list_anggota',
                'is_required' => 1,
                'options' => null,
                'urutan' => 3,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | PENGABDIAN MASYARAKAT
        |--------------------------------------------------------------------------
        */
        $fields = array_merge($fields, [
            [
                'jenis_surat_id' => $surat['PENGABDIAN']->id,
                'field_name' => 'judul_kegiatan',
                'field_type' => 'text',
                'is_required' => 1,
                'options' => null,
                'urutan' => 1,
            ],
            [
                'jenis_surat_id' => $surat['PENGABDIAN']->id,
                'field_name' => 'lokasi_pelaksanaan',
                'field_type' => 'textarea',
                'is_required' => 1,
                'options' => null,
                'urutan' => 2,
            ],
            [
                'jenis_surat_id' => $surat['PENGABDIAN']->id,
                'field_name' => 'list_anggota',
                'field_type' => 'list_anggota',
                'is_required' => 1,
                'options' => null,
                'urutan' => 3,
            ],
            [
                'jenis_surat_id' => $surat['PENGABDIAN']->id,
                'field_name' => 'tanggal_mulai',
                'field_type' => 'date',
                'is_required' => 1,
                'options' => null,
                'urutan' => 4,
            ],
            [
                'jenis_surat_id' => $surat['PENGABDIAN']->id,
                'field_name' => 'tanggal_selesai',
                'field_type' => 'date',
                'is_required' => 1,
                'options' => null,
                'urutan' => 5,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | PENGEMBANGAN DIRI
        |--------------------------------------------------------------------------
        */
        $fields = array_merge($fields, [
            [
                'jenis_surat_id' => $surat['PENGEMBANGAN_DIRI']->id,
                'field_name' => 'jenis_pengembangan_diri',
                'field_type' => 'text',
                'is_required' => 1,
                'options' => null,
                'urutan' => 1,
            ],
            [
                'jenis_surat_id' => $surat['PENGEMBANGAN_DIRI']->id,
                'field_name' => 'lokasi',
                'field_type' => 'textarea',
                'is_required' => 1,
                'options' => null,
                'urutan' => 2,
            ],
            [
                'jenis_surat_id' => $surat['PENGEMBANGAN_DIRI']->id,
                'field_name' => 'list_anggota',
                'field_type' => 'list_anggota',
                'is_required' => 1,
                'options' => null,
                'urutan' => 3,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | KEPANITIAAN
        |--------------------------------------------------------------------------
        */
        $fields = array_merge($fields, [
            [
                'jenis_surat_id' => $surat['KEPANITIAAN']->id,
                'field_name' => 'nama_kepanitiaan',
                'field_type' => 'text',
                'is_required' => 1,
                'options' => null,
                'urutan' => 1,
            ],
            [
                'jenis_surat_id' => $surat['KEPANITIAAN']->id,
                'field_name' => 'tanggal_mulai',
                'field_type' => 'date',
                'is_required' => 1,
                'options' => null,
                'urutan' => 2,
            ],
            [
                'jenis_surat_id' => $surat['KEPANITIAAN']->id,
                'field_name' => 'tanggal_selesai',
                'field_type' => 'date',
                'is_required' => 1,
                'options' => null,
                'urutan' => 3,
            ],
            [
                'jenis_surat_id' => $surat['KEPANITIAAN']->id,
                'field_name' => 'list_panitia',
                'field_type' => 'list_kepanitiaan',
                'is_required' => 1,
                'options' => null,
                'urutan' => 4,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | KEANGGOTAAN PROFESI
        |--------------------------------------------------------------------------
        */
        $fields = array_merge($fields, [
            [
                'jenis_surat_id' => $surat['KEANGGOTAAN_PROFESI']->id,
                'field_name' => 'nama_profesi',
                'field_type' => 'text',
                'is_required' => 1,
                'options' => null,
                'urutan' => 1,
            ],
            [
                'jenis_surat_id' => $surat['KEANGGOTAAN_PROFESI']->id,
                'field_name' => 'lokasi',
                'field_type' => 'textarea',
                'is_required' => 1,
                'options' => null,
                'urutan' => 2,
            ],
            [
                'jenis_surat_id' => $surat['KEANGGOTAAN_PROFESI']->id,
                'field_name' => 'list_anggota',
                'field_type' => 'list_anggota',
                'is_required' => 1,
                'options' => null,
                'urutan' => 3,
            ],
            [
                'jenis_surat_id' => $surat['KEANGGOTAAN_PROFESI']->id,
                'field_name' => 'tanggal_mulai',
                'field_type' => 'date',
                'is_required' => 1,
                'options' => null,
                'urutan' => 4,
            ],
            [
                'jenis_surat_id' => $surat['KEANGGOTAAN_PROFESI']->id,
                'field_name' => 'tanggal_selesai',
                'field_type' => 'date',
                'is_required' => 1,
                'options' => null,
                'urutan' => 5,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | PENELITIAN
        |--------------------------------------------------------------------------
        */
        $fields = array_merge($fields, [
            [
                'jenis_surat_id' => $surat['PENELITIAN']->id,
                'field_name' => 'judul_penelitian',
                'field_type' => 'textarea',
                'is_required' => 1,
                'options' => null,
                'urutan' => 1,
            ],
            [
                'jenis_surat_id' => $surat['PENELITIAN']->id,
                'field_name' => 'tempat_publikasi',
                'field_type' => 'text',
                'is_required' => 1,
                'options' => null,
                'urutan' => 2,
            ],
            [
                'jenis_surat_id' => $surat['PENELITIAN']->id,
                'field_name' => 'list_anggota',
                'field_type' => 'list_anggota',
                'is_required' => 1,
                'options' => null,
                'urutan' => 3,
            ],
        ]);

        DB::table('jenis_surat_field_form')->insert($fields);
    }
}