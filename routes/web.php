<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


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
// Route::get('/testPaytabs',function(){
//     $plugin = new PayTaps();
                
//     $base_url = $plugin->getBaseUrl();
//     $request_url = 'payment/request';
  
//     $data_PayTaps = [
//         "tran_type" => "sale",
//         "tran_class" => "ecom",
//         'payment_methods' => ['creditcard'],
//         "cart_id" => '12',
//         "cart_currency" => 'SAR',
//         "cart_amount" => 10,
//         "cart_description" => "managed form sample",
//         "paypage_lang" => "ar",
//         "callback" => 'https://webhook.site/e5c2cb5f-a65c-4ca1-ad92-84c6cb611b97', // Nullable - Must be HTTPS, otherwise no post data from paytabs
//         "return" => 'https://webhook.site/e5c2cb5f-a65c-4ca1-ad92-84c6cb611b97', // Must be HTTPS, otherwise no post data from paytabs , must be relative to your site URL
//         "customer_details" => [
//             "name" => 'bookingData name',
//             "email" =>  'email@gmail.com',
//             "phone" =>  '12345',
//             "street1" => 'street1',
//             "city" => 'city',
//             "state" => 'state',
//             "country" => 'SA',
//             "zip" => '1234',
//             "ip" => file_get_contents("https://api.ipify.org")
//         ],
//         "shipping_details" => [
//             "name" => ' bookingData name',
//             "email" =>  'email@gmail.com',
//             "phone" =>  '12345',
//             "street1" => 'street1',
//             "city" => 'city',
//             "state" => 'state',
//             "country" => 'SA',
//             "zip" => '1234',
//         ]
//     ];
    
//     $page = $plugin->send_api_request($request_url, $data_PayTaps);
//     //dd($page);
//     if (!empty($page['redirect_url'])) {
//      return    redirect()->away($page['redirect_url']);
//     }
// }); 

// Route::get('/return',function(){
//     //return view();
// })->name('return');

// Route::post('callback',function(Request $request){
// $request = json_decode($request,false);
// if($request->payment_result->response_status== 'A'){
//     //تم الدفع
//     // هنا تعمل ما يجب عمله بعد الدفع

// }
// })->name('callback');
Route::get('/clearCache',function(){
   
    /*
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');*/
});