<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailPhpService
{
    protected $mail;

    function __construct()
    {
        $this->mail = new PHPMailer(true);

        // Konfigurasi server SMTP
        $this->mail->isSMTP();
        $this->mail->Host       = env('PHPEMAIL_HOST'); // Gunakan variabel lingkungan untuk keamanan
        $this->mail->SMTPAuth   = env('PHPEMAIL_AUTH');
        $this->mail->Username   = env('PHPEMAIL_USERNAME');
        $this->mail->Password   = env('PHPEMAIL_PASSWORD');
        $this->mail->SMTPSecure = env('PHPEMAIL_ENCRYPTION');
        $this->mail->Port       = env('PHPEMAIL_PORT');
    }

    public function sendEmail($to, $subject, $body, $altBody = '')
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return 'Email berhasil dikirim';
        } catch (Exception $e) {
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
