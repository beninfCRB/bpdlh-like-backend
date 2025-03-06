<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifikasiLaporanSptjmDikembalikan extends Notification
{
    use Queueable;
    protected $nomor_pengajuan, $atas_nama, $catatan;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($nomor_pengajuan, $atas_nama, $catatan)
    {
        //
        $this->nomor_pengajuan  = $nomor_pengajuan;
        $this->atas_nama        = $atas_nama;
        $this->catatan          = $catatan;
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
            'message_header'    => 'Mohon Maaf, Nomor permohonan: #' . $this->nomor_pengajuan . ' atas nama ' . $this->atas_nama . ' belum dapat disetujui, silakan lakukan perbaikan dan ajukan reviu kembali.',
            'message_body'      => $this->catatan
        ];
    }
}
