<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPengajuan extends Model
{
    protected $table = 'history_pengajuan';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'surat_id',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surat()
    {
        return $this->belongsTo(SuratMaster::class, 'surat_id');
    }
}