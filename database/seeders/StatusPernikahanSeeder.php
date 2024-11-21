<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Seeder;
use App\Models\StatusPernikahan;
use Illuminate\Support\Facades\DB;

class StatusPernikahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $status_pernikahan = [
            [
                'id'                    => Uuid::uuid4()->toString(),
                'status_pernikahan' => 'Belum Menikah',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),

            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'status_pernikahan' => 'Menikah',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),

            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'status_pernikahan' => 'Istri > 1',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),

            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'status_pernikahan' => 'Cerai',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),

            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'status_pernikahan' => 'Cerai Mati',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),

            ],
        ];
        \DB::table('status_pernikahans')->insert($status_pernikahan);
    }
}
