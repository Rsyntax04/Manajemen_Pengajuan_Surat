<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratDetail extends Model
{
    protected $table = 'surat_detail';

    protected $fillable = [
        'surat_id',
        'field_name',
        'field_value'
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