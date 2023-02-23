<?php
use Botble\RealEstate\Models\Category;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Http\Controllers\PropertyController;
use Botble\RealEstate\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

if (App::environment('production')) {
    URL::forceScheme('https');
}

Route::get('/', function () {
    return response('Health Status check called.', 200)->header('Content-Type', 'application/json');
});
Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
	Route::get('/', function () {
	    return response('Api Version is 1.0.', 200)->header('Content-Type', 'application/json');
	});
	Route::get('/get-features-by-category/{category_id}', [PropertyController::class, 'get_features_by_category']); 
}); 



Route::post('/callback_url', [PublicController::class,'callback_url'] )->name('callback_url_api');
Route::any('/return_url', [PublicController::class,'return_url'] )->name('return_url_api');

