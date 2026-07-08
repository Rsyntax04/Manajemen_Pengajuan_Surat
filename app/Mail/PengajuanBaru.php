<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\JenisSurat;

class PengajuanBaru extends Mailable
{
    public $surat;

    public $jenis_surat;

    public $mo;

    public function __construct($surat,$jenis_surat,$mo)
    {
        $this->surat = $surat;
        $this->jenis_surat = $jenis_surat;
        $this->mo = $mo;
    }

    public function build()
    {
        return $this->subject('Pengajuan Surat Baru')
                    ->view('emails.pengajuan-baru');
    }
}