<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\SuratAnggota;
use App\Models\SuratDetail;
use App\Models\SuratMaster;
use App\Models\SuratPanitia;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestingDataSeeder extends Seeder
{
    /**
     * Run the database seeds for testing purposes.
     */
    public function run(): void
    {
        $mahasiswa = User::where('email', 'mahasiswa@mail.com')->first();
        $dosen = User::where('email', 'dosen@mail.com')->first();
        $mo = User::where('email', 'mo@mail.com')->first();

        $perjalananDinas = JenisSurat::where('kode_surat', 'PERJALANAN_DINAS')->first();
        $karyaIlmiah = JenisSurat::where('kode_surat', 'KARYA_ILMIAH')->first();
        $pengabdian = JenisSurat::where('kode_surat', 'PENGABDIAN')->first();

        if ($mahasiswa && $perjalananDinas) {
            $suratPending = SuratMaster::firstOrCreate(
                [
                    'user_id' => $mahasiswa->id,
                    'jenis_surat_id' => $perjalananDinas->id,
                    'status' => 'pending',
                ],
                [
                    'nomor_surat' => null,
                    'catatan_revisi' => null,
                    'file_hasil' => null,
                ]
            );

            $this->seedDetails($suratPending, [
                'nama_kegiatan' => 'Rapat Koordinasi Program Studi',
                'tanggal_mulai' => '2026-07-15',
                'tanggal_selesai' => '2026-07-16',
                'lokasi' => 'Gedung Fakultas Teknik, Bandung',
            ]);

            $this->seedAnggota($suratPending, [
                ['nama' => 'Ayu Lestari', 'identitas' => 'MHS101', 'keterangan' => 'Ketua Tim'],
                ['nama' => 'Rizky Pratama', 'identitas' => 'MHS102', 'keterangan' => 'Anggota'],
            ]);
        }

        if ($dosen && $karyaIlmiah && $mo) {
            $suratApproved = SuratMaster::firstOrCreate(
                [
                    'user_id' => $dosen->id,
                    'jenis_surat_id' => $karyaIlmiah->id,
                    'status' => 'approved',
                ],
                [
                    'nomor_surat' => 'UNIV/2026/001',
                    'file_hasil' => 'surat-karya-ilmiah.docx',
                ]
            );

            $suratApproved->forceFill([
                'approved_by' => $mo->id,
                'approved_at' => now(),
            ])->save();

            $this->seedDetails($suratApproved, [
                'judul_karya_ilmiah' => 'Pengembangan Aplikasi Manajemen Pengajuan Surat Berbasis Web',
                'tempat_publikasi' => 'Jurnal Informatika Terapan',
            ]);

            $this->seedAnggota($suratApproved, [
                ['nama' => 'Dr. Budi Santoso', 'identitas' => 'DSN001', 'keterangan' => 'Penulis Utama'],
                ['nama' => 'Siti Nurhaliza', 'identitas' => 'DSN002', 'keterangan' => 'Co-Author'],
            ]);
        }

        if ($mahasiswa && $pengabdian) {
            $suratRevisi = SuratMaster::firstOrCreate(
                [
                    'user_id' => $mahasiswa->id,
                    'jenis_surat_id' => $pengabdian->id,
                    'status' => 'revisi',
                ],
                [
                    'nomor_surat' => null,
                    'catatan_revisi' => 'Perbaiki lokasi pelaksanaan dan tanggal kegiatan.',
                    'file_hasil' => null,
                ]
            );

            $this->seedDetails($suratRevisi, [
                'judul_kegiatan' => 'Bakti Sosial Donor Darah',
                'lokasi_pelaksanaan' => 'RSUD Cibiru',
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2026-08-02',
            ]);

            $this->seedAnggota($suratRevisi, [
                ['nama' => 'Dewi Anggraini', 'identitas' => 'MHS201', 'keterangan' => 'Koordinator'],
                ['nama' => 'Fajar Nugroho', 'identitas' => 'MHS202', 'keterangan' => 'Anggota'],
            ]);
        }
    }

    private function seedDetails(SuratMaster $surat, array $values): void
    {
        foreach ($values as $fieldName => $fieldValue) {
            SuratDetail::updateOrCreate(
                [
                    'surat_id' => $surat->id,
                    'field_name' => $fieldName,
                ],
                [
                    'field_value' => $fieldValue,
                ]
            );
        }
    }

    private function seedAnggota(SuratMaster $surat, array $members): void
    {
        foreach ($members as $member) {
            SuratAnggota::updateOrCreate(
                [
                    'surat_id' => $surat->id,
                    'nama' => $member['nama'],
                ],
                [
                    'identitas' => $member['identitas'] ?? null,
                    'keterangan' => $member['keterangan'] ?? null,
                ]
            );
        }
    }
}
