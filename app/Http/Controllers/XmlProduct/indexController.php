<?php

namespace App\Http\Controllers\XmlProduct;

use App\Http\Controllers\Controller;
use App\Models\XmlCategory;
use App\Models\XmlManufacturer;
use App\Models\XmlPictures;
use App\Models\XmlProduct;
use App\Models\XmlProductCombination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class indexController extends Controller
{
    // https://www.ayakkabixml.com/index.php?route=ddaxml/xml_export&kullanici_adi=umut&sifre=6731&key=79e9350f9
    public function index() {
        $url = 'https://www.ayakkabixml.com/index.php?route=ddaxml/xml_export&kullanici_adi=umut&sifre=6731&key=79e9350f9';
        $page = file_get_contents($url);
        $xml = simplexml_load_string($page, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        //dd($array['Product'][0]);
        foreach ($array['Product'] as $val) {
            //if (empty(XmlProduct::where('ProductId',$val['ProductId'])->first())) {
                $product = new XmlProduct;
                $product->ProductId = $val['ProductId'];
                $product->Status = $val['Status'];
                $product->ProductName = $val['ProductName'];
                $product->FullDescription = $val['FullDescription'];
                $product->ProductSku = $val['ProductSku'];
                $product->ProductGtin = $val['ProductGtin'];
                $product->ProductColor = $val['ProductColor'];
                $product->ProductStockQuantity = $val['ProductStockQuantity'];
                $product->DeliveryTime = $val['DeliveryTime'];
                $product->ProductPrice = $val['ProductPrice'];
                $product->Breadcrumb = $val['Breadcrumb'];
                $product->save();
            //}
            //if (empty(XmlProductCombination::where('ProductId',$val['ProductId'])->first())) {
                foreach ($val['ProductCombinations']['ProductCombination'] as $item) {
                    $XmlProductCombination = new XmlProductCombination;
                    $XmlProductCombination->ProductId = $val['ProductId'];
                    $XmlProductCombination->ProductCombinationId = $item['ProductCombinationId'];
                    $XmlProductCombination->VariantGtin = $item['VariantGtin'];
                    $XmlProductCombination->VariantStockQuantity = $item['VariantStockQuantity'];
                    $XmlProductCombination->VariantName = $item['ProductAttributes']['ProductAttribute']['VariantName'];
                    $XmlProductCombination->VariantValue = $item['ProductAttributes']['ProductAttribute']['VariantValue'];
                    $XmlProductCombination->save();
                }
            //}
            //if (empty(XmlPictures::where('ProductId',$val['ProductId'])->first())) {
                foreach ($val['Pictures']['Picture'] as $item) {
                    $XmlPictures = new XmlPictures;
                    $XmlPictures->ProductId = $val['ProductId'];
                    $XmlPictures->PictureUrl = $item['PictureUrl'];
                    $XmlPictures->save();

                    $url = $item['PictureUrl'];
                    $contents = file_get_contents($url);
                    $name = $val['ProductId'].'.'.substr($url, strrpos($url, '.') + 1);
                    Storage::put($name, $contents);
                }
            //}
            //if (empty(XmlCategory::where('ProductId',$val['ProductId'])->first())) {
                foreach ($val['Categories']['Category'] as $item) {
                    $XmlCategory = new XmlCategory;
                    $XmlCategory->ProductId = $val['ProductId'];
                    $XmlCategory->CategoryId = $item['CategoryId'];
                    $XmlCategory->CategorName = $item['CategorName'];
                    $XmlCategory->CategoryPath = $item['CategoryPath'];
                    $XmlCategory->save();
                }
            //}
            //if (empty(XmlManufacturer::where('ProductId',$val['ProductId'])->first())) {
                foreach ($val->XmlManufacturers->XmlManufacturer as $item) {
                    $XmlManufacturer = new XmlManufacturer;
                    $XmlManufacturer->ProductId = $val['ProductId'];
                    $XmlManufacturer->ManufacturerId = $item['ManufacturerId'];
                    $XmlManufacturer->ManufacturerName = $item['ManufacturerName'];
                    $XmlManufacturer->save();
                }
            //}
        }
        return "Başarılı";
    }
}
