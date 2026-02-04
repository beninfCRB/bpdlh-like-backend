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

    public function sendEmail($to, $subject, $data, $default_password, $altBody = '', $view = null)
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            if ($view) {
                # code...
                $this->mail->Body    = view($view, ['data' => $data, 'default_password' => $default_password]);
            } else {

                $this->mail->Body    = view('mail.register-mail', ['data' => $data, 'default_password' => $default_password]);
            }
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function transaksiPenyaluran($to, $subject, $data, $altBody = '', $view)
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to->email);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = view($view, compact('data', 'to'));
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function verifikasiPengajuanKegiatan($to, $subject, $data, $altBody = '', $view)
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to->email);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = view($view, compact('data', 'to'));
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return 'Email berhasil dikirim';
        } catch (Exception $e) {
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function verifikasiValidasiDitolak($to, $subject, $data, $altBody = '', $view)
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to->email);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = view($view, compact('data', 'to'));
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return 'Email berhasil dikirim';
        } catch (Exception $e) {
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function verifikasiLaporanDitolak($to, $subject, $data, $altBody = '', $view)
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to->email);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = view($view, compact('data', 'to'));
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return 'Email berhasil dikirim';
        } catch (Exception $e) {
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function profileDitolak($to, $subject, $data, $altBody = '', $view)
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to->email);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = view($view, compact('data', 'to'));
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return 'Email berhasil dikirim';
        } catch (Exception $e) {
            return false;
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function pengajuanKegiatanBerhasilDikirim($to, $subject, $data, $altBody = '', $view)
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to->email);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = view($view, compact('data', 'to'));
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function pengajuanKegiatanDiterima($to, $subject, $data, $altBody = '', $view)
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to->email);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = view($view, compact('data', 'to'));
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function getTokenAktivasi($to, $subject, $token, $altBody = '')
    {
        try {
            // Pengaturan pengirim dan penerima
            $this->mail->setFrom(env('PHPEMAIL_FROM_ADDRESS'), env('PHPEMAIL_FROM_NAME'));
            $this->mail->addAddress($to);

            // Konten email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;

            $this->mail->Body    = view('mail.token-verify', ['email' => $to, 'token' => $token]);
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return "Email gagal dikirim. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
