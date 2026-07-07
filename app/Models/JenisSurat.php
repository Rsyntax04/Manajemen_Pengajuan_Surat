<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';

    protected $fillable = [
        'nama_jenis',
        'kode_surat',
        'template_file',
        'template_html',
        'template_json',
        'penandatangan_nama',
        'penandatangan_nip',
        'penandatangan_jabatan',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function fields()
    {
        return $this->hasMany(JenisSuratFieldForm::class);
    }

    public function surat()
    {
        return $this->hasMany(SuratMaster::class);
    }
}