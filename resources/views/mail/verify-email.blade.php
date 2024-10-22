<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Email Verifikasi Akun</title>
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
        <h1>Verifikasi Akun Anda</h1>
        <p>
            Halo {{ $data->email }},
        </p>
        <p>
            Terima kasih atas antusiasme Anda pada Layanan Dana Masyarakat untuk Lingkungan. Berikut adalah kode
            aktivasi akun
            yang dapat Anda gunakan untuk registrasi:
        </p>
        <div class="info">
            <p><strong>Kode Aktivasi:</strong> {{ $kode_aktivasi }}</p>
        </div>
        <p>
            Harap simpan informasi login ini dengan aman dan tidak membagikannya kepada pihak lain. Jika Anda memiliki
            pertanyaan lebih lanjut atau memerlukan bantuan kami, jangan ragu untuk menghubungi kami melalui email
            <a href="mailto:layanandanamasyarakat@bpdlh.id">
                layanandanamasyarakat@bpdlh.id.
            </a>
        </p>
        <p>
            Terima kasih,
        </p>
        <div class="footer">
            Salam,<br>
            Tim Pengelola Layanan Dana Masyarakat untuk Lingkungan<br>
        </div>
    </div>
</body>

</html>
