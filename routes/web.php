<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XmlController;
use App\Models\xml;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {

   $data['xml'] =  Xml::all();
    return view('welcome',$data);
});
*/
Route::post('xml.save', [XmlController::class, 'save'])->name('xml.save');
Route::get('xml.parser', [XmlController::class, 'parser'])->name('xml.parser');
Route::get('xml.databaseXml', [XmlController::class, 'databaseXml'])->name('xml.databaseXml');


Route::get('/category', [\App\Http\Controllers\Trendyol\IndexController::class,'categoryUrlCrawler']);
//Route::get('/products/{pi}', [\App\Http\Controllers\Trendyol\IndexController::class,'allProducts']);
Route::get('/prod', [\App\Http\Controllers\Trendyol\IndexController::class,'allProducts']);
Route::get('/json/{pi}', [\App\Http\Controllers\Trendyol\IndexController::class,'jsonSave']);
Route::get('/json', [\App\Http\Controllers\Trendyol\IndexController::class,'jsonSave']);
Route::get('/view', [\App\Http\Controllers\Trendyol\IndexController::class,'view']);

Route::get('/joom-send', [\App\Http\Controllers\Joom\IndexController::class,'send']);


Route::get('/xml-products-save', [\App\Http\Controllers\XmlProduct\indexController::class,'index']);

Route::group(['middleware'=>'auth'], function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

    Route::resource('/seller', \App\Http\Controllers\Seller\IndexController::class);
    Route::get('/category-match', [\App\Http\Controllers\HomeController::class,'categoryMatch'])->name('categoryMatch');
    Route::get('/product-list', [\App\Http\Controllers\HomeController::class,'productList'])->name('productList');
});





require __DIR__.'/auth.php';
