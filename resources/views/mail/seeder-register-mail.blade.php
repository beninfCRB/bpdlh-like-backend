<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Email Informasi Akun</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        h1 {
            color: #12A3A4;
        }

        p {
            line-height: 1.6;
        }

        .info {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .info p {
            margin: 0;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Informasi Akun Anda</h1>
        <p>
            Halo {{ $data->nama_pic }},
        </p>
        <p>
            Terima kasih telah mendaftar di Layanan Dana Masyarakat Untuk Lingkungan. Berikut adalah detail akun Anda:
        </p>
        <div class="info">
            <p><strong>Email:</strong> {{ $data->email }}</p>
            <p><strong>Password:</strong> {{ $default_password }}</p>
        </div>
        <p>
            Harap simpan informasi ini dengan aman dan tidak membagikannya kepada orang lain. Jika Anda memiliki
            pertanyaan atau memerlukan bantuan lebih lanjut, jangan ragu untuk <a
                href="mailto:{{ env('PHPEMAIL_FROM_ADDRESS') }}">menghubungi kami</a>.
        </p>
        <p>
            Terima kasih,
        </p>
        <div class="footer">
            Hormat kami,<br>
            Badan Pengelola Dana Lingkungan Hidup (BPDLH)<br>
        </div>
    </div>
</body>

</html>