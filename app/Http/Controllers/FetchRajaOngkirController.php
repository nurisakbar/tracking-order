<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FetchRajaOngkirController extends Controller
{
    public function province()
    {
        $provinceEndpoint = "https://api.rajaongkir.com/starter/province";
        $key = env("RAJAONGKIR_API_KEY");
        $provinceResponse = Http::withHeaders(['key'=>$key])->get($provinceEndpoint);
        Province::insert($provinceResponse->json()['rajaongkir']['results']);
    }
}
