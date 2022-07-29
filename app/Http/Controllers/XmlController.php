<?php namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Xml;
use App\Services\ArrayToXml;
use DOMDocument;
use Illuminate\Http\Request;
use \SimpleXMLElement;
use stdClass;

class XmlController extends Controller
{


    public function save(Request $request)
    {
        $xml = new Xml();
        $xml->url = $request->url;
        $xml->percent = $request->percent;
        $xml->save();
    }


    public function parser(Request $request)
    {
        header("Content-Type: application/xml; charset=utf-8");

        $xml_string = Xml::find($request->id);
        $xmlFile = simplexml_load_file($xml_string->url, "SimpleXMLElement", LIBXML_NOCDATA);

        // $jsonFormatData = json_encode($xmlFile);
        //$result = json_decode($jsonFormatData, true);


        $simplexml = new SimpleXMLElement('<?xml version="1.0"  encoding="UTF-8"?><Products/>');
        $x = "";
        $i = 0;
        $a = 0;

        $result = $xmlFile->xpath('Product');
        foreach ($result as $items) {

            $a = $items->xpath('Categories')[0];

            $b = $items->xpath('Manufacturers')[0];

            $resim = $items->xpath('Pictures');
            $x = [];
            foreach ($resim as $item) {
                $x[] = $item->xpath('Picture/PictureUrl');
            }


            $book1 = $simplexml->addChild('Product');
            $book1->addChild("ProductId", $items->xpath('ProductId')[0]);
            $book1->addChild("Status", $items->xpath('Status')[0]);
            $book1->addChild("ProductName", $items->xpath('ProductName')[0]);
            $book1->addChild("FullDescription", '<![CDATA[' . html_entity_decode($items->xpath('FullDescription')[0]) . ' ]]');
            $book1->addChild("ProductSku", $items->xpath('ProductSku')[0]);
            $book1->addChild("ProductGtin", $items->xpath('ProductGtin')[0]);
            $book1->addChild("Tax", 18);
            $book1->addChild("ProductStockQuantity", $items->xpath('ProductStockQuantity')[0]);
            $book1->addChild("DeliveryTime", $items->xpath('DeliveryTime')[0]);
            $book1->addChild("ProductPrice", $items->xpath('ProductPrice')[0] + ($items->xpath('ProductPrice')[0] * 100) / 100);
            //$book1->addChild("ProductCombinations", $item['ProductCombinations']);
            $pictures = $book1->addChild("Pictures");

            foreach ($x as $images) {
                foreach ($images as $image) {
                    $picture = $pictures->addChild('Picture');
                    $picture->addChild('PictureUrl', $image);
                }
            }


            $varyantarray = $items->xpath('ProductCombinations/ProductCombination');
            $varyants = $book1->addChild("ProductCombinations");

            foreach ($varyantarray as $varyant) {
                $varyantItem = $varyants->addChild('ProductCombination');
                $varyantItem->addChild('ProductCombinationId', $varyant->ProductCombinationId);
                $varyantItem->addChild('VariantGtin', $varyant->VariantGtin);
                $varyantItem->addChild('VariantStockQuantity', $varyant->VariantStockQuantity);
                $varyantItemAttributes = $varyantItem->addChild('ProductAttributes');
                $varyantItemAttribute = $varyantItemAttributes->addChild('ProductAttribute');
                $varyantItemAttribute->addChild('VariantName', $varyant->ProductAttributes->ProductAttribute->VariantName);
                $varyantItemAttribute->addChild('VariantValue', $varyant->ProductAttributes->ProductAttribute->VariantValue);
            }
            $book1->addChild("Categories", $a->xpath('Category')[0]->CategorName);
            $book1->addChild("Manufacturers", $b->xpath('Manufacturer')[0]->ManufacturerName);
            $i++;
        }

        file_put_contents('books.xml', $simplexml->asXML());
        return redirect()->back();
    }
}
