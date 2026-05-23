<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Http\Request;

class MapLocationController extends Controller
{
    public function getAdministrativeData(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        $village = Village::query()
            ->select(
                'id',
                'code',
                'district_code',
                'name',
            )
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(CAST(JSON_EXTRACT(meta, '$.lat') AS DECIMAL(10,7)))) * cos(radians(CAST(JSON_EXTRACT(meta, '$.long') AS DECIMAL(10,7))) - radians(?)) + sin(radians(?)) * sin(radians(CAST(JSON_EXTRACT(meta, '$.lat') AS DECIMAL(10,7)))))) AS distance",
                [$lat, $lng, $lat]
            )
            ->selectRaw("CAST(JSON_EXTRACT(meta, '$.lat') AS DECIMAL(10,7)) AS meta_lat")
            ->selectRaw("CAST(JSON_EXTRACT(meta, '$.long') AS DECIMAL(10,7)) AS meta_long")
            ->whereRaw("JSON_EXTRACT(meta, '$.lat') IS NOT NULL")
            ->having('distance', '<', 10)
            ->orderBy('distance', 'asc')
            ->first();

        if (!$village) {
            return response()->json(['message' => 'Wilayah tidak ditemukan di sekitar titik ini'], 404);
        }

        $districtCode = $village->district_code;
        $cityCode = substr($districtCode, 0, 4);
        $provinceCode = substr($districtCode, 0, 2);

        $district = District::query()->where('code', $districtCode)->first();
        $city = City::query()->where('code', $cityCode)->first();
        $province = Province::query()->where('code', $provinceCode)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'village' => [
                    'code' => $village->code,
                    'name' => $village->name,
                    'id' => $village->id
                ],
                'district' => $district ? ['code' => $district->code, 'name' => $district->name, 'id' => $district->id] : null,
                'city' => $city ? ['code' => $city->code, 'name' => $city->name, 'id' => $city->id] : null,
                'province' => $province ? ['code' => $province->code, 'name' => $province->name, 'id' => $province->id] : null,
            ]
        ]);
    }
}