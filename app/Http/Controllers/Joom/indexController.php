<?php

namespace App\Http\Controllers\Joom;

use App\Http\Controllers\Controller;
use App\Models\JoomToken;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductStoreHistory;
use App\Models\Store;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class indexController extends Controller
{
    private function data()
    {
        $translation = new GoogleTranslate('tr');
        $translation->setTarget('en');
        $product = Product::first();
        $productContent = ProductContent::first();
        $store = ProductStoreHistory::first();
        $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPUQRSTUVWXYZ';
        $str_r = substr(str_shuffle($str), 0, 10);
        $data = [
            [
                "attributes" => [
                    [
                        "key" => $productContent->attributeName,
                        "value" => $productContent->attributeValue
                    ]
                ],
                "brand" => $store->platform,
                "categoryId" => null,
                "dangerKind" => null,
                "description" => $translation->translate($productContent->description),
                "enabled" => true,
                "extraImages" => [
                    [
                        "imageState" => "$productContent->name",
                        "origUrl" => "https://cdn.dsmcdn.com/".json_decode($product->images, true)[0],
                        "processed" => [
                            [
                                "height" => 500,
                                "url" => "https://cdn.dsmcdn.com/".json_decode($product->images, true)[0],
                                "width" => 500
                            ]
                        ]
                    ]
                ],
                "gtin" => null,
                "landingPageUrl" => null,
                "mainImage" => [
                    "imageState" => "blockedImages",
                    "origUrl" => "https://cdn.dsmcdn.com/".json_decode($product->images, true)[0],
                    "processed" => [
                        [
                            "height" => 500,
                            "url" => "https://cdn.dsmcdn.com/".json_decode($product->images, true)[0],
                            "width" => 500
                        ]
                    ]
                ],
                "name" => $translation->translate($productContent->name),
                "sku" => $str_r,
                "storeId" => null,
                "variants" => [
                    [
                        "attributes" => [
                            [
                                "key" => $translation->translate($productContent->attributeName),
                                "value" => $translation->translate($productContent->attributeValue)
                            ]
                        ],
                        "currency" => "USD",
                        "declaredValue" => null,
                        "enabled" => null,
                        "gtin" => null,
                        "hsCode" => null,
                        "inventory" => null,
                        "mainImage" => [
                            "imageState" => null,
                            "origUrl" => null,
                            "processed" => [
                                [
                                    "height" => null,
                                    "url" => null,
                                    "width" => null
                                ]
                            ]
                        ],
                        "msrPrice" => "string",
                        "price" => $product->price*20%100%18,
                        "shippingHeight" =>null,
                        "shippingLength" =>null,
                        "shippingPrice" =>null,
                        "shippingWeight" =>null,
                        "shippingWidth" =>null,
                        "size" => null,
                        "sku" => $str_r,
                    ]
                ]
            ]
        ];
        return $data;
    }

    private function auth()
    {
        $curl = curl_init();
        //$code = file_get_contents('https://api-merchant.joom.com/api/v2/oauth/authorize?client_id=d9e8cf21659e4bed');

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-merchant.joom.com/api/v2/oauth/access_token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=d9e8cf21659e4bed&client_secret=8bf7b5f5b2461f9f95dfc13770e4e5e2&grant_type=authorization_code&code=e3adc41c291db245f73c98871b806251&redirect_uri=https://connect.entegresan.com/joom.php',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $responsej = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($responsej, true);
        $joom = new JoomToken();
        $joom->access_token = $response['data']['access_token'];
        $joom->refresh_token = $response['data']['refresh_token'];
        $joom->expires_in = $response['data']['expires_in'];
        $joom->expiry_time = $response['data']['expiry_time'];
        $joom->merchant_user_id = $response['data']['merchant_user_id'];
        $joom->save();
        echo $responsej;
    }

    public function send()
    {
        $now = time();
        $token = JoomToken::where('expiry_time', '>', $now)->orderBy('expiry_time', 'DESC')->first();
        if (empty($token)) {
            $this->auth();
        }
        //dd($token->access_token);
        $data = $this->data();
        $data_string = json_encode($data);
        //dd($data_string);

        $ch = curl_init('https://merchant.joom.com/docs/api/v3/products/create');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: Bearer ' . $token->access_token)
        );

        $result = curl_exec($ch);

        echo $result;
    }


    public function jsonResponse($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        exit(json_encode($data));
    }
/*https://merchant.joom.com/docs/api/v3/products/create

    Müşteri Kimlik Numarası
d9e8cf21659e4bed

    Gizli anahtar
8bf7b5f5b2461f9f95dfc13770e4e5e2

    Yeniden yönlendirme URL'si
https://connect.entegresan.com/joom.php*/
}
