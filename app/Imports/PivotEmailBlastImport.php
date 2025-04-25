<?php

namespace App\Imports;

use App\Models\PivotEmailBlast;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;

class PivotEmailBlastImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Skip header
        $rows->shift();

        foreach ($rows as $row) {
            $data = [
                'email' => $row[0],
                'nomor_pengajuan' => $row[1],
                'status' => $row[2],
                'catatan_log' => $row[3] ?? null,
            ];

            Validator::make($data, [
                'email' => 'required|email',
                'nomor_pengajuan' => 'required|string',
                'status' => 'required|in:diterima,ditolak',
            ])->validate();

            PivotEmailBlast::create($data);
        }
    }
}
