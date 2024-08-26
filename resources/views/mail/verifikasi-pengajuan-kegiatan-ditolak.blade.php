<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Email Pengajuan Kegiatan Belum Dapat Disetujui</title>
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
        <h1>Pengajuan Kegiatan Belum Dapat Disetujui</h1>
        <p>
            Halo {{ $to->nama_pic }},
        </p>
        <p>
            Mohon maaf pengajuan anda dengan nomor pengajuan {{ $data['nomor_pengajuan'] }} belum dapat disetujui,
            dengan catatan sebagai berikut:
        </p>
        <div class="info">
            <p>{{ $data['catatan_log'] }}</p>
        </div>
        {{-- <p>
            Silahkan cek pengajuan pada link berikut <a
                href="{{ env('URL_FE') ?? 'https://bpdlh.id/layanan-masyarakat/#/layanan-masyarakat/sign-in' }}">"KLIK
                DI SINI"</a>.
        </p> --}}
        <div class="footer">
            Hormat kami,<br>
            Badan Pengelola Dana Lingkungan Hidup (BPDLH)<br>
        </div>
    </div>
</body>

</html>