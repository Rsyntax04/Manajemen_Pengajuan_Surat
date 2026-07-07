<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratFile extends Model
{
    protected $table = 'surat_files';

    protected $fillable = [
        'surat_id',
        'user_id',
        'nama_file',
        'path_file',
        'file_type',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function surat()
    {
        return $this->belongsTo(SuratMaster::class, 'surat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}