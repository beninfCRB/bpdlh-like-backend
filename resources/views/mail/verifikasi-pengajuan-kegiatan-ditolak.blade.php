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
            Yth. Bapak/Ibu {{ $to->nama_pic ?? $data['nama_pic'] }}, {{ $data['kelompok_masyarakat'] ?? null }},
        </p>
        <p>
            Berdasarkan hasil verifikasi dan validasi, dengan ini kami sampaikan bahwa usulan kegiatan Bapak/Ibu dengan
            nomor pengajuan {{ $data['nomor_pengajuan'] }} berjudul "{{ $data['judul_pengajuan_kegiatan'] }}" dengan
            jumlah dana Rp.{{ number_format($data['total']) }} <b>belum dapat disetujui</b> pada periode saat ini,
            dengan catatan sebagai berikut:
        </p>
        <div class="info">
            <p>{{ $data['catatan_log'] }}</p>
        </div>
        <p>
            Seluruh proses seleksi dan keputusan akhir merupakan kewenangan penuh Tim FOLU NC 2&3 Kementerian Kehutanan.
            Keputusan ini bersifat mutlak serta tidak dapat diganggu gugat (<i>final and binding</i>).
        </p>
        <p>
            Kami ucapkan terima kasih atas perhatian dan komitmen Bapak/Ibu dalam mendukung aksi nyata pelestarian
            lingkungan. Kami berharap Bapak/Ibu dapat berpartisipasi pada periode selanjutnya.
            Informasi lebih lanjut dapat menghubungi kami melalui email <a
                href="mailto:layanandanamasyarakat@bpdlh.id">layanandanamasyarakat@bpdlh.id</a>.
        </p>
        <div class="footer">
            Hormat kami,<br>
            Tim Pengelola Layanan Dana Masyarakat untuk Lingkungan<br>
        </div>
    </div>
</body>

</html>
