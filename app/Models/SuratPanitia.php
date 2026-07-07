<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPanitia extends Model
{
    protected $table = 'surat_panitia';

    protected $fillable = [
        'surat_id',
        'nama',
        'identitas',
        'jabatan',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    // RELASI ke surat master
    public function surat()
    {
        return $this->belongsTo(SuratMaster::class, 'surat_id');
    }
}