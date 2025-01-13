<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAB Kegiatan</title>
    <style>
        /* Mengatur ukuran kertas menjadi landscape */
        @page {
            size: A4 landscape;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            /* Tabel penuh lebar */
            border-collapse: collapse;
            /* Menghilangkan spasi antar border */
        }

        th,
        td {
            border: 1px solid #000;
            /* Border untuk setiap sel */
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            /* Warna latar belakang header */
        }

        caption {
            font-size: 1.5em;
            font-weight: bold;
            margin: 10px;
        }
    </style>
</head>

<body>

    <table>
        <caption>RAB Kegiatan</caption>
        <thead>
            <tr>
                <th>No. </th>
                <th>Deskripsi</th>
                <th>Satuan</th>
                <th>Harga Unit</th>
                <th>Jumlah</th>
                <th>Harga Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $letters = range('A', 'Z');
                $total = 0;
            @endphp
            @foreach ($data->data['komponen_rab'] as $key => $item)
                <tr>
                    <th>{{ $letters[$loop->index] }}</th>
                    <th>{{ $key }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach ($item as $it)
                    <tr>
                        <td style="text-align: right">{{ $loop->iteration }}</td>
                        <td>{{ $it['komponen_rab'] }}</td>
                        <td>{{ $it['satuan'] }}</td>
                        <td style="text-align: right">Rp. {{ number_format($it['harga_unit']) }}</td>
                        <td style="text-align: center">{{ number_format($it['qty']) }}</td>
                        @php
                            $total += $it['harga_unit'] * $it['qty'];
                        @endphp
                        <td style="text-align: right">Rp. {{ number_format($it['harga_unit'] * $it['qty']) }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td style="text-align: right;" colspan="5">Total</td>
                <td style="text-align: right">Rp. {{ number_format($total) }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
