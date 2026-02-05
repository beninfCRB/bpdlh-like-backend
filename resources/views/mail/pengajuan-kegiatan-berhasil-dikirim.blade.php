<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pemberitahuan Pengajuan Proposal Akses Dana Layanan Masyarakat untuk Lingkungan</title>
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
        <h1>Pemberitahuan Pengajuan Proposal Akses Dana Layanan Masyarakat untuk Lingkungan</h1>
        <p>
            Yth. Bapak/Ibu Pengusul Kegiatan Layanan Dana Masyarakat untuk Lingkungan <i>Periode 4</i>,
        </p>
        <p>
            Pengajuan usulan kegiatan Bapak/Ibu berhasil dikirim, saat ini akan dilakukan proses verifikasi dan validasi
            oleh Tim Verifikasi dan Validasi Kementerian Kehutanan. mohon untuk menunggu informasi selanjutnya melalui
            email atau membuka aplikasi secara berkala.
        </p>
        <div class="info">
            <p><strong>Detail Pengajuan:</strong></p>
            <p>Nama Pengusul: {{ $data['nama_pengusul'] }}</p>
            <p>Judul Kegiatan: {{ $data['judul_kegiatan'] }}</p>
            <p></p>Nomor Pengajuan: {{ $data['nomor_pengajuan'] }}</p>
        </div>
        <p>
            Demikian informasi ini kami sampaikan. Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.
        </p>
        <p>
            Informasi lebih lanjut dapat menghubungi kami melalui email <a
                href="mailto:layanandanamasyarakat@bpdlh.id">layanandanamasyarakat@bpdlh.id</a>
        </p>
        <div class="footer">
            Hormat kami,<br>
            Tim Pengelola Layanan Dana Masyarakat untuk Lingkungan<br>
        </div>
    </div>
</body>

</html>
