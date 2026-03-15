<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pengajuan Perlu Perhatian</title>
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
            color: #ff0000;
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
        <h1>Pengajuan Perlu Perhatian</h1>
        <p>
            Halo {{ $to->nama_pic }},
        </p>
        <p>
            Mohon maaf berdasarkan hasil verifikasi dan validasi, pengajuan dengan nomor {{ $data['nomor_pengajuan'] }}
            perlu melakukan perbaikan RAB, dengan catatan sebagai berikut:
        </p>
        <div class="info">
            <p>{{ $data['catatan_log'] }}</p>
        </div>
        <p>
            Mohon melakukan perbaikan RAB.
            Informasi lebih lanjut dapat menghubungi nomor PMU FOLU NC : 0811-8881-0990.
        </p>
        <div class="footer">
            Salam,<br>
            Tim Pengelola Layanan Dana Masyarakat untuk Lingkungan<br>
        </div>
    </div>
</body>

</html>
