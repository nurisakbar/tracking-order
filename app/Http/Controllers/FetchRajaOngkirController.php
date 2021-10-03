<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Province;

class FetchRajaOngkirController extends Controller
{
    public function province()
    {
        $provinceEndpoint = "https://api.rajaongkir.com/starter/province";
        $key = env("RAJAONGKIR_API_KEY");
        $provinceResponse = Http::withHeaders(['key' => $key])->get($provinceEndpoint);
        Province::insert($provinceResponse->json()['rajaongkir']['results']);



        $cityEndpoint = "https://api.rajaongkir.com/starter/city";
        $key = env("RAJAONGKIR_API_KEY");
        $cityResponse = Http::withHeaders(['key' => $key])->get($cityEndpoint);
        City::insert($cityResponse->json()['rajaongkir']['results']);
    }
}
