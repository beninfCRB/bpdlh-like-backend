<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pemberitahuan Persetujuan Pengajuan Proposal Akses Dana Layanan Masyarakat untuk Lingkungan</title>
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
        <h1>Pemberitahuan Persetujuan Pengajuan Proposal Akses Dana Layanan Masyarakat untuk Lingkungan</h1>
        <p>
            Yth.
        </p>
        <p>
            {{ $data['nama_pic'] }}.
        </p>
        <p>
            {{ $data['kelompok_masyarakat'] }}.
        </p>
        <p>
            Berdasarkan hasil penilaian secara menyeluruh atas permohonan Saudara/i pada layanan dana masyarakat untuk
            lingkungan, dengan ini kami sampaikan bahwa proposal Saudara/i dengan nomor pengajuan
            <b>{{ $data['nomor_pengajuan'] }}</b>
            berjudul <b>"{{ $data['judul_pengajuan_kegiatan'] }}"</b> dengan jumlah dana Rp.
            <b>{{ number_format($data['total']) }}</b>
            telah disetujui melalui Surat
            Keputusan yang dapat diunduh melalui tautan berikut: <a
                href="{{ $data['document_sk'] }}">LINK_SK_PENERIMA_DANA</a>.
            Proses
            pencairan dana hibah
            dapat diakses melalui rekening Bank yang telah kami siapkan.
        </p>
        <p>
            Untuk keperluan penyaluran dana lebih lanjut, mohon Saudara/i dapat :

        </p>
        <ol>
            <li>
                Menandatangani Surat Pernyataan Tanggung Jawab Mutlak (terlampir) dan mengirimkan kembali lewat email
                ini dan menggunggahnya melalui aplikasi/sistem layanan dana masyarakat untuk lingkungan.
            </li>
            <li>
                Melakukan pengkinian tanggal kegiatan dalam sistem layanan bilamana kegiatan yang diusulkan telah
                terlewati atau berkeinginan untuk merubah tanggal kegiatan.
            </li>
        </ol>
        <p>
            Demikian kami sampaikan, atas kerjasamanya kami ucapakan terimakasih.
        </p>
        <div class="footer">
            Hormat kami,<br>
            Tim Pengelola Layanan Dana Masyarakat untuk Lingkungan<br>
        </div>
    </div>
</body>

</html>
