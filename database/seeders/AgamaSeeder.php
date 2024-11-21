<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Agama;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $agama = [
            [
                'id'                    => Uuid::uuid4()->toString(),
                'agama' => 'Islam',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'agama' => 'Kristen',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'agama' => 'Katholik',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),

            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'agama' => 'Hindu',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),

            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'agama' => 'Budha',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),

            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'agama' => 'Kong Hu Cu',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),

            ],
        ];

        \DB::table('agamas')->insert($agama);
    }
}
