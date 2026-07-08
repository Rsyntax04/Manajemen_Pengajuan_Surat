<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\JenisSurat;

class StatusPengajuanMail extends Mailable
{
    public $surat;
    public $jenis_surat;
    public $pesan;

    public function __construct($surat,$jenis_surat,$pesan)
    {
        $this->surat = $surat;
        $this->jenis_surat = $jenis_surat;
        $this->pesan = $pesan;
    }

    public function build()
    {
        return $this->subject('Status Pengajuan Surat')
                    ->view('emails.status-pengajuan');
    }
}