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

        $latExpr = "CAST(latitude AS DECIMAL(10,7))";
        $lngExpr = "CAST(longitude AS DECIMAL(10,7))";

        $radiusCandidates = [10, 25, 50];
        $village = null;
        $usedRadius = null;

        foreach ($radiusCandidates as $radius) {
            $candidate = Village::query()
                ->select(
                    'id',
                    'code',
                    'district_code',
                    'name',
                )
                ->selectRaw(
                    "(6371 * acos(cos(radians(?)) * cos(radians({$latExpr})) * cos(radians({$lngExpr}) - radians(?)) + sin(radians(?)) * sin(radians({$latExpr})))) AS distance",
                    [$lat, $lng, $lat]
                )
                ->selectRaw("{$latExpr} AS meta_lat")
                ->selectRaw("{$lngExpr} AS meta_long")
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereRaw("latitude REGEXP '^-?[0-9]+(\\.[0-9]+)?$'")
                ->whereRaw("longitude REGEXP '^-?[0-9]+(\\.[0-9]+)?$'")
                ->having('distance', '<', $radius)
                ->orderBy('distance', 'asc')
                ->first();

            if ($candidate) {
                $village = $candidate;
                $usedRadius = $radius;
                break;
            }
        }

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
            'used_radius_km' => $usedRadius,
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