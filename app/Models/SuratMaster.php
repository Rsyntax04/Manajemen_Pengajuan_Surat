<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMaster extends Model
{
    protected $table = 'surat_master';

    protected $fillable = [
        'user_id',
        'jenis_surat_id',
        'nomor_surat',
        'status',
        'catatan_revisi',
        'file_hasil',
        'approved_at'
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(SuratDetail::class, 'surat_id');
    }

    public function files()
    {
        return $this->hasMany(SuratFile::class, 'surat_id');
    }

    public function anggota()
    {
        return $this->hasMany(SuratAnggota::class, 'surat_id');
    }

    public function panitia()
    {
        return $this->hasMany(SuratPanitia::class, 'surat_id');
    }
    protected $casts = [
    'approved_at' => 'datetime',
];
}