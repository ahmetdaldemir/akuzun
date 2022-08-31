<?php namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\Xml;
use App\Models\XmlManufacturer;
use App\Models\XmlPictures;
use App\Models\XmlProduct;
use App\Models\XmlProductCombination;
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

/*
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
*/

    public function databaseXml()
    {

        /*  $result = Product::all();
          foreach ($result as $items) {
              $categorylists = explode("&", $items->category_tree);
                  $i = 0;
                  foreach ($categorylists as $categorylist) {
                     echo "<br>".$categorylist;
                      $i++;
              }
          }
          dd("bitti");
  */
        header("Content-Type: application/xml; charset=utf-8");
        $simplexml = new SimpleXMLElement('<?xml version="1.0"  encoding="UTF-8"?><products/>');

        $result = Product::all();
        foreach ($result as $items) {
            $content = ProductContent::where('product_id', $items->product_id)->where('language_id', 1)->first()->name;
            $book1 = $simplexml->addChild('product');
            $book1->addChild("product_id", $items->id);
            $book1->addChild("active", 1);
            $book1->addChild("name", '<![CDATA[' . html_entity_decode(ProductContent::where('product_id', $items->product_id)->where('language_id', 1)->first()->name) . ' ]]');
            $book1->addChild("description", '<![CDATA[' . html_entity_decode($content) . ' ]]');
            $book1->addChild("product_code", $items->product_id);
            $book1->addChild("barcode", $items->product_id);
            $book1->addChild("brand", "akuzun");
            $book1->addChild("stock", 5);
            $book1->addChild("cost_price", 0);
            $book1->addChild("cost_price_supplier", 0);
            $book1->addChild("currency", "TRY");
            $book1->addChild("price", $items->price + ($items->price * 100) / 100);
            $book1->addChild("images", 'http://akuzun.ml/storage/' . ($items->product_id . ".jpg"));
            $book1->addChild("variants", '');
            $category_data = $book1->addChild("category_data");
            $categorytree = $category_data->addChild('category_path');
            $categorylists = explode("&", $items->category_tree);
            $i = 1;
            foreach ($categorylists as $categorylist) {
                $categorytree->addChild('category_'.$i.'', $categorylist);
                $i++;  if ($i == 3) {
                    break;
                }
            }
            $category_data->addChild('category_id');
        }


        $resultO = XmlProduct::all();

        foreach ($resultO as $itemso) {
            $book2 = $simplexml->addChild('product');
            $book2->addChild("product_id", $itemso->ProductId);
            $book2->addChild("active", 1);
            $book2->addChild("name", '<![CDATA[' . html_entity_decode($itemso->ProductName) . ' ]]');
            $book2->addChild("description", '<![CDATA[' . html_entity_decode($itemso->FullDescription) . ' ]]');
            $book2->addChild("product_code", $itemso->ProductSku);
            $book2->addChild("barcode", $itemso->ProductGtin);
            $book2->addChild("stock", $itemso->ProductStockQuantity);
            $book2->addChild("price", $itemso->ProductPrice + ($itemso->ProductPrice * 100) / 100);
            $book2->addChild("cost_price", 0);
            $book2->addChild("cost_price_supplier", 0);
            $book2->addChild("currency", "TRY");

            $x = XmlPictures::where('ProductId', $itemso->ProductId)->get();

            if ($x) {
                foreach ($x as $image) {
                    $picturesII[] = $image->PictureUrl;
                }
                $picturesI = implode(",", $picturesII);

                $book2->addChild("images", $picturesI);
            } else {
                $book2->addChild("images", '');
            }


            $varyantarray = XmlProductCombination::where('ProductId', $itemso->ProductId)->get();
            $varyants = $book2->addChild("varyants");

            foreach ($varyantarray as $varyant) {
                $varyantItem = $varyants->addChild('variant');
                $varyantItem->addChild('variant_id', $varyant->ProductCombinationId);
                $varyantItem->addChild('barcode', $varyant->VariantGtin);
                $varyantItem->addChild('product_code', $varyant->ProductId);
                $varyantItem->addChild('stock', $varyant->VariantStockQuantity);
                $varyantItem->addChild('price', $itemso->ProductPrice + ($itemso->ProductPrice * 100) / 100);
                $varyantItem->addChild('cost_price', 0);
                $varyantItemAttributes = $varyantItem->addChild('options');
                $varyantItemAttribute = $varyantItemAttributes->addChild('option1');
                $varyantItemAttribute->addChild('name', $varyant->VariantName);
                $varyantItemAttribute->addChild('value', $varyant->VariantValue);
            }

            $manufacturer = XmlManufacturer::where('ProductId', $itemso->ProductId)->first();

            $category_data = $book2->addChild("category_data");
            $categorytree = $category_data->addChild('category_path');
            $categorytree->addChild('category_1', $itemso->Breadcrumb);
            $category_data->addChild('category_id');
            $book2->addChild("brand", $manufacturer->ManufacturerName ?? 'Akuzun');
        }

        file_put_contents('books.xml', $simplexml->asXML());
        return redirect()->back();
    }
}
