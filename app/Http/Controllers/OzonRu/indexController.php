<?php

namespace App\Http\Controllers\OzonRu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class indexController extends Controller
{
    private function getCategories()
    {
        $url = "https://api-seller.ozon.ru/v1/categories/tree?language=EN";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
            "Client-Id: 581894",
            "Api-Key: bae82125-3f19-4ff4-88d5-53620faa9027",
            "language: EN",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        var_dump($resp);
    }

    public function saveCategories()
    {

    }
}
