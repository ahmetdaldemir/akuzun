<?php

namespace App\Http\Controllers\Trendyol;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductStoreHistory;
use App\Models\Store;
use DOMDocument;
use Goutte\Client;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /*
    product
    product_history
    store
    category
    product => id,price,category_tree,images,
    product_store_history => id,product_id,price,platform

    product_content => id,product_id,language_id,name,description
    language => id,name




 1 Sisteme Magazalar Kaydedilir = store_name, store_id
 2 = Magaz aSeçilir Ürün getir diye bir buton ile ürünlerin mongo dibye kaydı sağlanır
 Kuyruk tetiklenir
 Daha önceden magazanın ürünleri veritabanına kayıt edilmiş ise sadece fiyat alanı güncellenir , değilse ürün tamamen mysql e eklenir
 Ürün mongodan silinir
 Eğer ürün magazaya ait değil ama veritabanında aynı isimde ürün var ise klonlanmaz farklı bir tabloda product_id, store _id,price,created_at,updated_at
 olarak kaydedilir
 günde  1 çalışan farklı bir kuyruk sisteme kayıtılı ürünlerin trendyoldaki fiyatlarını tekrardan sisteme çeker güncelleme var ise update girer
 Hatta eski fiyat product_id, store _id,price,created_at,updated_at bu tabloya yazılır


      https://public.trendyol.com/discovery-web-searchgw-service/v2/api/infinite-scroll
      /sr?mid=197697&os=1&pi=9&culture=tr-TR&userGenderId=1&pId=0&scoringAlgorithmId=2&
      categoryRelevancyEnabled=false&isLegalRequirementConfirmed=false&searchStrategyType=DEFAULT&
      productStampType=TypeA&fixSlotProductAdsIncluded=false

       https://public.trendyol.com/discovery-web-searchgw-service/v2/api/infinite-scroll
        /sr?mid=197697&os=1&pi=8&culture=tr-TR&userGenderId=1&pId=0&scoringAlgorithmId=2&categoryRelevancyEnabled=false&
        isLegalRequirementConfirmed=false&searchStrategyType=DEFAULT&productStampType=TypeA&fixSlotProductAdsIncluded=false



https://merchant.joom.com/docs/api/v3

    Müşteri Kimlik Numarası
d9e8cf21659e4bed

    Gizli anahtar
8bf7b5f5b2461f9f95dfc13770e4e5e2

    Yeniden yönlendirme URL'si
https://connect.entegresan.com/joom.php

    */




    private $results = array();

    public function categoryUrlCrawler()
    {
        $client_link = new Client();
        $url = "https://www.trendyol.com";
        $category = $client_link->request('GET',$url);
        $this->results['html'] = $category->filter('.main-nav')->html();
        $main = array_filter(explode('class="tab-link">' ,$this->results['html']));

        for ($i = 1; $i<count($main); $i++) {
            $sub_main = array_filter(explode('<a' ,$main[$i]));
            for ($a = 1; $a<count($sub_main); $a++) {
                $pattern = "/>(.*)/";
                preg_match($pattern, strip_tags($sub_main[$a]), $category_name);
                preg_match('/"+(.*)" /',strip_tags($sub_main[$a]),$category_url);
                $name[] = trim($category_name[1],'>');
//                $category[$category_name] = $category_url[1];
//                dd($category);
            }

            dd($name);
        }
        exit();
    }

    public function allProducts()
    {
        // sayfalama için toplam ürün sayısı / 24 + 1 round ile de 1 üste yuvarla ve
        // for ile dön pi değerini değişerek sayfalama yapsın ve tüm ürünler çekilsin category bazlı olarak

        // merchant id değişikliğini ilkinde değil de ikincisinde de kullanılabilir bakılacak


        $category_url_1 = 'https://public.trendyol.com/discovery-web-searchgw-service/v2/api/filter/kadin-x-g1?culture=tr-TR&userGenderId=1&pId=0&scoringAlgorithmId=2&categoryRelevancyEnabled=false&isLegalRequirementConfirmed=false&productStampType=TypeA&fixSlotProductAdsIncluded=false&pi=1';
        $store_url_1 = 'https://public.trendyol.com/discovery-web-searchgw-service/v2/api/infinite-scroll/sr?mid='."447462".'&os=1&culture=tr-TR&userGenderId=1&pId=0&scoringAlgorithmId=2&categoryRelevancyEnabled=false&isLegalRequirementConfirmed=false&searchStrategyType=DEFAULT&productStampType=TypeA&fixSlotProductAdsIncluded=false&pi='.'1';
        $brand_url = 'https://public.trendyol.com/discovery-web-searchgw-service/v2/api/infinite-scroll/okyanus-home-x-b146047?culture=tr-TR&userGenderId=1&pId=0&scoringAlgorithmId=2&categoryRelevancyEnabled=false&isLegalRequirementConfirmed=false&searchStrategyType=DEFAULT&productStampType=TypeA&fixSlotProductAdsIncluded=false&pi='.'1';
        $pageIndex = file_get_contents($brand_url);
        $pageIndex = json_decode($pageIndex);
        /*if ($page->isSuccess == true && $page->statusCode == 200) {
            foreach ($page->result->products as $val) {
                $this->detail($val->url, $val->id,"json");
                if (empty(Product::where('product_id',$val->id)->first())) {
                    $product = new Product;
                    $product->price = $val->price->sellingPrice;
                    $product->category_tree = $val->categoryHierarchy;
                    $product->category_id = $val->categoryId;
                    $product->product_id = $val->id;
                    $product->merchant_id = $val->merchantId;
                    $product->images = json_encode($val->images, true);
                    $product->save();
                }
                if (empty(Store::where('merchant_id',$val->merchantId)->first())) {
                    $store = new Store;
                    $store->merchant_id = $val->merchantId;
                    $store->store_name = $val->brand->name; // barka adı satıcı adı olmalı bence
                    $store->save();
                }
                if (empty(Category::where('category_id',$val->categoryId)->first())) {
                    $store = new Category;
                    $store->category_id = $val->categoryId;
                    $store->category_name = $val->categoryName;
                    $store->save();
                }
                if (empty(ProductContent::where('product_id',$val->id)->first())) {
                    $product_content = new ProductContent;
                    $product_content->product_id = $val->id;
                    $product_content->name = $val->name;
                    $product_content->description = $this->results[$val->id]['detail_name'];
                    $product_content->language_id = 1;
                    $product_content->save();
                }
                if (empty(ProductStoreHistory::where('product_id',$val->id)->first())) {
                    $product_content = new ProductStoreHistory();
                    $product_content->product_id = $val->id;
                    $product_content->price = $val->price->sellingPrice;
                    $product_content->platform = $val->brand->name;
                    $product_content->save();
                }
            }
        }*/

        for ($i = 1;$i<=$pageIndex->result->totalCount/24; $i++) {
            $category_url = 'https://public.trendyol.com/discovery-web-searchgw-service/v2/api/infinite-scroll/okyanus-home-x-b146047?culture=tr-TR&userGenderId=1&pId=0&scoringAlgorithmId=2&categoryRelevancyEnabled=false&isLegalRequirementConfirmed=false&searchStrategyType=DEFAULT&productStampType=TypeA&fixSlotProductAdsIncluded=false&pi='.$i;
            $page = file_get_contents($category_url);
            $page = json_decode($page);
            if ($page->isSuccess == true && $page->statusCode == 200) {
                foreach ($page->result->products as $val) {
                    $this->detail($val->url, $val->id,"json");
                    if (empty(Product::where('product_id',$val->id)->first())) {
                        $product = new Product;
                        $product->price = $val->price->sellingPrice;
                        $product->category_tree = $val->categoryHierarchy;
                        $product->category_id = $val->categoryId;
                        $product->product_id = $val->id;
                        $product->merchant_id = $val->merchantId;
                        $product->images = json_encode($val->images, true);
                        $product->save();
                    }
                    if (empty(Store::where('merchant_id',$val->merchantId)->first())) {
                        $store = new Store;
                        $store->merchant_id = $val->merchantId;
                        $store->store_name = $val->brand->name; // barka adı satıcı adı olmalı bence
                        $store->save();
                    }
                    if (empty(Category::where('category_id',$val->categoryId)->first())) {
                        $store = new Category;
                        $store->category_id = $val->categoryId;
                        $store->category_name = $val->categoryName;
                        $store->save();
                    }
                    if (empty(ProductContent::where('product_id',$val->id)->first())) {
                        $product_content = new ProductContent;
                        $product_content->product_id = $val->id;
                        $product_content->name = $val->name;
                        $product_content->description = $this->results[$val->id]['detail_name'];
                        $product_content->attributeValue = $val->variants->attributeValue;
                        $product_content->attributeName = $val->variants->attributeName;
                        $product_content->language_id = 1;
                        $product_content->save();
                    }
                    if (empty(ProductStoreHistory::where('product_id',$val->id)->first())) {
                        $product_content = new ProductStoreHistory();
                        $product_content->product_id = $val->id;
                        $product_content->price = $val->price->sellingPrice;
                        $product_content->platform = $val->brand->name;
                        $product_content->save();
                    }
                }
            }
            sleep(5);
        }

        return json_encode($page->isSuccess);
    }

    private function detail($link, $product_id, $type="html")
    {
        $client_link = new Client();
        if ($type=="html"){
            $url = "https://www.trendyol.com".explode('?',$link)[0];
        } else {
            $url = "https://www.trendyol.com".$link;
        }
        $page_detail = $client_link->request('GET',$url);

       // $this->results[$product_id]['detail_name'] = $page_detail->filter('.detail-name')->text();
        $this->results[$product_id]['detail_name'] = $page_detail->filter('.detail-name')->text().' '.$page_detail->filter('.detail-desc-list li:first-child')->text();

//        $this->results[$product_id]['detail_desc'] = $page_detail->filter('.detail-desc-list')->text();
//        $page_detail->filter('.product-slide-container')->each(function ($itm) use ($product_id){
//            $this->results[$product_id]['image'] = str_replace('mnresize/128/192/','',$itm->filter('.styles-module_slider__o0fqa > .product-slide > img')->attr('src'));
//        });
    }

    public function view()
    {
        $items = \App\Models\Product::orderBy('created_at','DESC')->get();
        return view('view')
            ->with('items', $items);
    }

    public function jsonSave($pi)
    {
        $url = 'https://public.trendyol.com/discovery-web-searchgw-service/v2/api/infinite-scroll/sr?mid=197697&os=1&culture=tr-TR&userGenderId=1&pId=0&scoringAlgorithmId=2&categoryRelevancyEnabled=false&isLegalRequirementConfirmed=false&searchStrategyType=DEFAULT&productStampType=TypeA&fixSlotProductAdsIncluded=false&'.'pi='.$pi;
        $page = file_get_contents($url);
        $page = json_decode($page);
        if ($page->isSuccess == true && $page->statusCode == 200) {
            foreach ($page->result->products as $val) {
                $this->detail($val->url, $val->id,"json");
                $crawler = new \App\Models\Crawler;
                $crawler->product_id = $val->id;
                $crawler->plp_name = $val->name;
                $crawler->plp_name_ttl = $val->name;
                $crawler->plp_image = $val->stamps[0]->imageUrl ?? "";
                $crawler->plp_price = $val->price->sellingPrice;
                $crawler->link = $val->url;
                $crawler->detail_name = $this->results[$val->id]['detail_name'];
                $crawler->detail_desc = $this->results[$val->id]['detail_desc'];
                $crawler->image = $this->results[$val->id]['image'] ?? "";
                $crawler->save();
            }
        }

        return json_encode($page->isSuccess);
    }
}
