<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\City;
use App\Http\Requests\CostRequest;
use App\Models\Cost;
use Cache;

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
        $province = Cache::rememberForever('province', function () {
            return Province::select('province_id', 'province')->get();
        });

        return $province;
    }

    public function city($province_id)
    {
        $city = Cache::rememberForever('city-' . $province_id, function () use ($province_id) {
            return City::select('city_id', 'province_id', 'province', 'city_name', 'type', 'postal_code')
                    ->where('province_id', $province_id)
                    ->get();
        });
        return $city;
    }

    public function cost(CostRequest $request)
    {
        $cost = Cache::rememberForever('cost-' . $request->origin.'-'.$request->destination.'-'.$request->courier, function () use ($request) {
            $costEndpoint   = "https://api.rajaongkir.com/starter/cost";
            $request['key'] = env("RAJAONGKIR_API_KEY");
            $costResponse   = Http::post($costEndpoint, $request->all());
            $results = $costResponse->json()['rajaongkir']['results'][0]['costs'];
            if ($results) {
                foreach ($results as $result) {
                    $data           = array_merge($request->all(), $result);
                    $data['cost']   = serialize($result['cost']);
                    $where          = [
                        'origin'        =>  $request->origin,
                        'destination'   =>  $request->destination,
                        'courier'       =>  $request->courier,
                        'service'       =>  $result['service']
                    ];
                    Cost::firstOrCreate($where, $data);
                }
            }
    
            return Cost::select('origin', 'destination', 'courier', 'service', 'description', 'cost')
            ->where('origin', $request->origin)
            ->where('destination', $request->destination)
            ->where('courier', $request->courier)
            ->get();
        });

        return $cost;
    }
}
