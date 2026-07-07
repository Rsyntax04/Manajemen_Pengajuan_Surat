<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratAnggota extends Model
{
    protected $table = 'surat_anggota';

    protected $fillable = [
        'surat_id',
        'nama',
        'identitas',
        'keterangan',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function surat()
    {
        return $this->belongsTo(SuratMaster::class, 'surat_id');
    }
}