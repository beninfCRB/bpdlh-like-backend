<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pemberitahuan Pencairan Dana Termin II</title>
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
        <h1>Pemberitahuan Pencairan Dana Termin II</h1>
        <p>
            Yth. Bapak/Ibu Pengusul Kegiatan Layanan Dana Masyarakat untuk Lingkungan,
        </p>
        <p>
            Dengan ini kami sampaikan dana termin II untuk nomor pengajuan {{ $data['nomor_pengajuan'] }} telah tersedia
            pada rekening BNI dengan
            nomor rekening:({{ $data['nomor_rekening'] }}).
        </p>
        <p>
            Bapak/Ibu PIC dipersilahkan melakukan penarikan dana pada cabang Bank BNI yang sesuai dengan
            domisili Bapak/Ibu, dengan membawa dokumen-dokumen sebagai berikut:
        </p>
        <ol>
            <li>
                KTP
            </li>
            <li>
                NPWP /Surat pernyataan tidak memiliki NPWP (bila tidak memiliki NPWP)
            </li>
            <li>
                Kartu Keluarga
            </li>
            <li>
                Surat Keterangan sebagai penerima layanan dana masyarakat dari BPDLH (dapat diunduh pada aplikasi
                layanan dana masyarakat)
            </li>
            <li>
                Surat Keputusan (SK) penetapan penerima manfaat layanan dana masyarakat dari Kementerian Kehutanan
                (dapat diunduh pada aplikasi layanan dana masyarakat)
            </li>
        </ol>
        <p>
            Sebagai tambahan informasi, jika Bapak/Ibu mengalami kendala saat proses aktivasi rekening atau pada saat
            pencairan dana maka Bapak/Ibu dapat menghubungi nomor wa: 081111812090 untuk melaporkan kendala/keluhan.
        </p>
        <p>
            Demikian pemberitahuan ini kami sampaikan, atas kerjasamanya kami ucapkan terima kasih.
        </p>
        <div class="footer">
            Hormat kami,<br>
            Tim Pengelola Layanan Dana Masyarakat untuk Lingkungan<br>
        </div>
    </div>
</body>

</html>
