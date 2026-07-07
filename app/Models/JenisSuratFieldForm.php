<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSuratFieldForm extends Model
{
    protected $table = 'jenis_surat_field_form';

    protected $fillable = [
        'jenis_surat_id',
        'field_name',
        'field_type',
        'is_required',
        'options',
        'urutan'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }
}