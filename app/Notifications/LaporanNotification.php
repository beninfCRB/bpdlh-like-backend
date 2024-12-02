<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LaporanNotification extends Notification
{
    use Queueable;
    protected $nomor_pengajuan, $atas_nama;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($nomor_pengajuan, $atas_nama)
    {
        //
        $this->nomor_pengajuan  = $nomor_pengajuan;
        $this->atas_nama        = $atas_nama;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
            'message_header'    => 'Laporan Kegiatan Pengajuan No. #' . $this->nomor_pengajuan . ' atas nama ' . $this->atas_nama . ' telah diterima.',
            'message_body'      => 'Pantau proses berikutnya di Layanan Dana Masyarakat untuk Lingkungan.'
        ];
    }
}
