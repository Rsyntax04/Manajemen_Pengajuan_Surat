<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanBaruNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $surat;

    public function __construct($surat)
    {
        $this->surat = $surat;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Pengajuan Surat Baru',
            'message' => 'Ada pengajuan surat baru dari ' . ($this->surat->user->name ?? 'User') . '.',
            'surat_id' => $this->surat->id,
            'url' => route('mo.approval')
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
