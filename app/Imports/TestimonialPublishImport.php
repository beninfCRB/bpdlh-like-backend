<?php

namespace App\Imports;

use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TestimonialPublishImport implements ToCollection, WithChunkReading
{
    public function collection(Collection $rows)
    {
        // buang header (aman walau di chunk)
        if ($rows->first()[0] === 'nomor_pengajuan') {
            $rows->shift();
        }

        $nomorPengajuan = $rows
            ->pluck(0)
            ->filter() // buang null / kosong
            ->unique()
            ->toArray();

        if (empty($nomorPengajuan)) {
            return;
        }

        // HANYA yang ada relasinya saja yang akan ke-update
        Testimonial::whereHas('pengajuan_kegiatan', function ($query) use ($nomorPengajuan) {
            $query->whereIn('nomor_pengajuan', $nomorPengajuan);
        })->update([
            'is_published'   => true,
            'published_date' => now()->toDateString(),
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
