<?php

namespace App\Http\Controllers\Joom;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductStoreHistory;
use App\Models\Store;
use Illuminate\Http\Request;

class indexController extends Controller
{
    private function data()
    {
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
//                "categoryId" => "string",
//                "dangerKind" => 'notDangerous',
                "description" => $productContent->description,
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
//                "gtin" => "string",
//                "landingPageUrl" => "string",
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
                "name" => $productContent->name,
                "sku" => $str_r,
//                "storeId" => "string",
                "variants" => [
                    [
                        "attributes" => [
                            [
                                "key" => $productContent->attributeName,
                                "value" => $productContent->attributeValue
                            ]
                        ],
                        "currency" => "USD",
//                        "declaredValue" => "4.52",
//                        "enabled" => true,
//                        "gtin" => "string",
//                        "hsCode" => "string",
//                        "inventory" => 0,
//                        "mainImage" => [
//                            "imageState" => "blockedImages",
//                            "origUrl" => "http=>//example.com",
//                            "processed" => [
//                                [
//                                    "height" => 0,
//                                    "url" => "http=>//example.com",
//                                    "width" => 0
//                                ]
//                            ]
//                        ],
//                        "msrPrice" => "string",
                        "price" => $product->price,
//                        "shippingHeight" => 0,
//                        "shippingLength" => 0,
//                        "shippingPrice" => 0,
//                        "shippingWeight" => 0,
//                        "shippingWidth" => 0,
//                        "size" => "string",
                        "sku" => $str_r,
                    ]
                ]
            ]
        ];
        return $data;
    }

    public function send()
    {
        $data = $this->data();
        $data_string = json_encode($data);


        $ch = curl_init('https://merchant.joom.com/docs/api/v3/products/create');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
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
