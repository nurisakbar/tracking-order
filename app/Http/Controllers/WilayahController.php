<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\City;

class WilayahController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function province(Request $request)
    {
        $province = \Cache::rememberForever('province', function () {
            return Province::select('province_id', 'province')->get();
        });

        return $province;
    }

    public function city($province_id)
    {
        $city = \Cache::rememberForever('city-' . $province_id, function () use ($province_id) {
            return City::select('city_id', 'province_id', 'province', 'city_name', 'type', 'postal_code')
                    ->where('province_id', $province_id)
                    ->get();
        });
        return $city;
    }
}
