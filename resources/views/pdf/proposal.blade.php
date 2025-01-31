<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Proposal Small Grant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .content {
            border: 1px solid #000;
            margin-bottom: 10px;
        }

        .signature {
            margin-top: 40px;
        }

        .signature .stamp {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Template </h1>
        <h1> Proposal Small Grant</h1>
    </div>

    <div class="section">
        <b>I. UMUM</b>

        <div class="content">
            <b>1. Judul Kegiatan</b>
            <br>
            {{ $data->data->judul_pengajuan_kegiatan }}
        </div>

        <div class="content">
            <b>2. Pengusul</b>
            <br>
            Nama:
            {{ $data->data->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat }}
            <br>
            Alamat: {{ $data->data->alamat_kegiatan }}
            <br>
            Penjamin Kegiatan: {{ $data->data->user_akseslh->data_pic_kelompok_masyarakat->nama_pic }}
        </div>

        <div class="content">
            <b>3. Nomor Proposal:</b> {{ $data->data->nomor_pengajuan }}
        </div>

        <div class="content">
            <b>4. Perkiraan Bulan Pelaksanaan:</b> {{ date('d-M-Y', strtotime($data->data->tanggal_mulai_kegiatan)) }} -
            {{ date('d-M-Y', strtotime($data->data->tanggal_akhir_kegiatan)) }}
        </div>
    </div>

    <div class="section">
        <b>II. JUSTIFIKASI</b>

        <div class="content">
            <b>5. Latar Belakang Kegiatan/Project Background</b>
            <br>
            {{ $data->data->proposal_kegiatan }}
        </div>

        <div class="content">
            <b>6. Tujuan Kegiatan</b>
            <br>
            {{ $data->data->tujuan_kegiatan }}
        </div>

        <div class="content">
            <b>7. Ruang Lingkup Kegiatan/Scope of Work</b>
            <br>
            {{ $data->data->ruang_lingkup_kegiatan }}
        </div>
    </div>

    <div class="signature">
        Tanggal: {{ \Carbon\Carbon::now()->format('d-M-Y') }}
        <br>
        Penanggung Jawab Usulan
        <br>
        <div class="stamp">
            (tanda tangan dan stempel)
        </div>
        <br>
        <div class="line">
            ({{ $data->data->user_akseslh->data_pic_kelompok_masyarakat->nama_pic }})
            <br>
            ({{ $data->data->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat }})
        </div>
    </div>
</body>

</html>
