<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XmlController;
use App\Models\Xml;

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

Route::get('/', function () {

   $data['xml'] =  Xml::all();
    return view('welcome',$data);
});
Route::post('xml.save', [XmlController::class, 'save'])->name('xml.save');
Route::get('xml.parser', [XmlController::class, 'parser'])->name('xml.parser');


Route::get('/category', [\App\Http\Controllers\Trendyol\IndexController::class,'categoryUrlCrawler']);
//Route::get('/products/{pi}', [\App\Http\Controllers\Trendyol\IndexController::class,'allProducts']);
Route::get('/prod', [\App\Http\Controllers\Trendyol\IndexController::class,'allProducts']);
Route::get('/json/{pi}', [\App\Http\Controllers\Trendyol\IndexController::class,'jsonSave']);
Route::get('/json', [\App\Http\Controllers\Trendyol\IndexController::class,'jsonSave']);
Route::get('/view', [\App\Http\Controllers\Trendyol\IndexController::class,'view']);

Route::get('/joom-send', [\App\Http\Controllers\Joom\IndexController::class,'send']);

