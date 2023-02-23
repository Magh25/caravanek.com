<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Requests\SendConsultRequest;
use Botble\RealEstate\Models\Category;
use Botble\RealEstate\Models\Type;
use Botble\RealEstate\Models\Consult;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use Botble\RealEstate\Repositories\Interfaces\ConsultInterface;
use Botble\RealEstate\Repositories\Interfaces\CurrencyInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Illuminate\Support\Facades\Redirect; 
use Illuminate\Support\Facades\DB;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Repositories\Interfaces\SlugInterface;
use EmailHandler;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Mimey\MimeTypes;
use RssFeed;
use RvMedia;
use SeoHelper;
use SlugHelper;
use Spatie\Feed\Feed;
use Spatie\Feed\FeedItem;
use Theme;
use Throwable; 
use Illuminate\Support\Facades\Log; 
use Botble\RealEstate\Http\Controllers\PayTaps;
use Botble\RealEstate\Repositories\Interfaces\TransactionInterface;

// Class 'Botble\RealEstate\Http\Controllers\PayTabs' not found 
class PublicController extends Controller
{

    /**
     * @param SendConsultRequest $request
     * @param BaseHttpResponse $response
     * @param ConsultInterface $consultRepository
     * @param PropertyInterface $propertyRepository
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postSendConsult(
        SendConsultRequest $request,
        BaseHttpResponse $response,
        ConsultInterface $consultRepository,
        PropertyInterface $propertyRepository,
        TransactionInterface $transactionRepository
    ) {

        try {
            /**
             * @var Consult $consult
             */
            $consult = $consultRepository->getModel();
            $error = false;

            $sendTo = null;
            $link = null;
            $subject = null;

            $property_id = (int)@$request->input('data_id');
            $vendor_id = 0; 
            $price_pn = 0;

            $property = $propertyRepository->findById($property_id, ['author','type']);
            $comission_percent = 0;
            $is_fixable = false;
            $addons = [];
            $spaces = [];
            if($property->type->slug == 'Accessory'){

                // Log::info($request);
                // return($request);
                // die();
            }

           
           
            if ($property) {
                $link = $property->url;
                $subject = "حجز جديد : ".$property->name;
                $price_pn = floatval($property->price);
                $vendor_id = (int)@$property->author->id;
                $comission_percent = floatval(@$property->type->commission);
                if( $property->author->email ) 
                    $sendTo = $property->author->email;

                $is_fixable = @DB::table('re_property_types')->select('is_fixable')->where('id', '=', (int)@$property->type_id)->get()->toArray()[0]->is_fixable == 1;
                /*
                if( !empty($property->addons) && $is_fixable){
                    $_addons = DB::select("SELECT * FROM re_addon WHERE status = 'published' AND id IN (".implode(',',$property->addons).") ORDER BY name ASC");
                    foreach($_addons as $adn){
                        $addons[$adn->id] = $adn;
                    }
                }
                */
            }

            
            if( $comission_percent > 100)
                $comission_percent = 100;

            $params = $request->input();


            $user_id = (int)$params['user_id'];

            $from_date = $this->formatDate($params['pickup']);

            if($params['bookingtype'] == 'Monthly'){  
                $effectiveDate = strtr($params['pickup'], '/', '-');
                 $to_date = date('Y-m-d', strtotime("+1 months", strtotime($effectiveDate)));
            }else{
                 $to_date = $this->formatDate($params['dropoff']);
            }  
            
            $no_nights = 1;
            $addonsSelected  = [];
            $addonsTotal = 0;
             
            if( $from_date && $to_date && $to_date > $from_date){
                $earlier = new \DateTime($from_date);
                $later = new \DateTime($to_date);
                $no_nights = (int)str_replace('-','',$earlier->diff($later)->format("%r%a"));
            } else
                $error = __("Selected Dates are not valid");

            $bookings = DB::select("SELECT * FROM re_consults WHERE property_id = $property_id AND user_id = $user_id AND status IN ('unread','approved') AND '".date("Y-m-d")."' < `to_date`");

            if( !$error && !empty($bookings) ){
                $error = __("You have already booked this property");      
            }
            
            if( !$error ){
                $all_bookings = DB::select("SELECT from_date,to_date FROM re_consults WHERE property_id = $property_id AND status != 'canceled' AND `from_date` >='".date("Y-m-d")."'");
                $non_avl_dates = [];
                
                foreach($all_bookings as $b){
                    $b = (array)$b;
                    if( !empty($b['from_date']) &&  !empty($b['to_date']) ){
                        $begin = new \DateTime($b['from_date']);
                        $end   = new \DateTime($b['to_date']);

                        for($i = $begin; $i < $end; $i->modify('+1 day') ){
                            $non_avl_dates[$i->format("Y-m-d")] = 1;
                        }
                    }
                }
                if($property->type->slug == 'Accessory'){

                }else{

                    if( isset($non_avl_dates[$from_date]) ||  isset($non_avl_dates[$to_date]))
                        $error = "Booking not available on selected dates, please try other dates";
                }
            }
            // Log::info("sssssss--5--ssss");
            
            if( $error ){
                // return $response->setError()->setMessage( strstr($redirect_url,"&error=true") ); 
 
                // $redirect_url =  url()->current().'?error=true';  
                $redirect_url = $request->input('redirect_url');
                if(false !==  strstr($redirect_url, "&error=true")) {
                    return $response
                    ->setError()
                    ->setMessage(trans($error));
                    return Redirect::to($redirect_url); 
                }else{                    
                    return $response
                    ->setError()
                    ->setMessage(trans($error));
                    return Redirect::to($redirect_url.'&error=true');
                }
                // return $response->setError()->setMessage(__($redirect_url)); 
            }     


            
            if( !empty($params['addons']) ){
                foreach($params['addons'] as $aid){
                    $aid = explode('|^|',$aid);
                    if( isset($aid[1]) ){
                        $price = floatval($aid[1]);
                        $name = trim($aid[0]);

                        $addonsTotal += number_format($price*$no_nights,2,'.','');
                        $addonsSelected[] = ['name'=>$name,'price'=>$price];
                    }
                }
            }

            $spacesSelected  = [];
            $unitstypeSelected  = [];
            $spacesTotal = 0;
             
            if( !empty($params['spaces']) ){
                $aid = $params['spaces'];
                // foreach($params['spaces'] as $aid){
                    $aid = explode('|^|',$aid);
                    if( isset($aid[1]) ){
                        $price = floatval($aid[1]);
                        $name = trim($aid[0]); 
                        $spacesTotal += number_format($price*$no_nights,2,'.','');
                        $spacesSelected[] = ['name'=>$name,'price'=>$price];
                    }
                // }
            }   

            if($property->type->slug == 'Accessory'){ 
                

                // $total_price = number_format($price_pn*$no_nights,'2','.','')+$addonsTotal;
                // ------magh
                $guests = $params['guests'];
                $total_price_temp = number_format($price_pn*$guests,'2','.',''); 
                $property_type_taxes = number_format($total_price_temp * 15/100,'2','.','');
                $property_type_commission_fees = number_format($total_price_temp * $comission_percent/100,'2','.','');
                $total_price_last = number_format($total_price_temp + $property_type_commission_fees + $property_type_taxes,'2','.',''); 
            }else{ 

                $total_price = number_format($price_pn*$no_nights,'2','.','')+$addonsTotal+$spacesTotal; 
                // $guests = $params['guests'];
                $total_price_temp = number_format($total_price,'2','.',''); 
                $property_type_taxes = number_format($total_price_temp * 15/100,'2','.','');
                $property_type_commission_fees = number_format($total_price_temp * $comission_percent/100,'2','.','');
                $total_price_last = number_format($total_price_temp + $property_type_commission_fees + $property_type_taxes,'2','.',''); 


                // $total_price = number_format($price_pn*$no_nights,'2','.','')+$addonsTotal+$spacesTotal; 
                // $guests = $params['guests'];
                // $total_price_temp = number_format($total_price*$guests,'2','.',''); 
                // $property_type_taxes = number_format($total_price_temp * 15/100,'2','.','');
                // $property_type_commission_fees = number_format($total_price_temp * $comission_percent/100,'2','.','');
                // $total_price_last = number_format($total_price_temp + $property_type_commission_fees + $property_type_taxes,'2','.',''); 
            }

            // ------magh  
            $bookingData = [
                'property_id' => $property_id,
                'user_id' => $user_id,
                'vendor_id' => $vendor_id,
                'name' => trim(@$params['name']),
                'email' => trim(@$params['email']),
                'phone' => trim(@$params['phone']),
                'property_type' => trim(@$property->type->name),
                'property_name' => trim(@$property->name),
                'from_date' => $from_date,
                'to_date' => $to_date,
                'guests' => (int)@$params['guests'] ?: 1,
                'price_pn' => $price_pn,
                'addons' => json_encode($addonsSelected),
                'spaces' => json_encode($spacesSelected),
                'unitstype'=> trim(@$params['unitstype']),
                'bookingtype'=> trim(@$params['bookingtype']),
                'total_addons' => $addonsTotal,
                'total_price' => $total_price_last,
                'commission' => $property_type_commission_fees,
                'no_nights' => $no_nights,
                'created_at' => time(),
                'updated_at' => time()
            ];

            // Log::info($bookingData);



            try {
                // For Begin a transaction
                DB::beginTransaction();
            
                $consult->fill($bookingData);
                $consultRepository->createOrUpdate($consult); 





            if( !empty($sendTo) ){
                if($property->type->slug == 'Accessory'){
                    
                    $subject = "فاتورة شراء : ".$property->name;
                    EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
                    ->setVariableValues([
                        'consult_name'    => $consult->name ?? 'N/A',
                        'consult_email'   => $consult->email ?? 'N/A',
                        'consult_phone'   => $consult->phone ?? 'N/A',
                        'consult_content' => 'N/A',
                        'consult_link'    => $link ?? 'N/A',
                        'consult_subject' => $subject ?? 'N/A',
                    ])
                    ->sendUsingTemplate('notice', $sendTo);
                // ----------by magh ------

                EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
                    ->setVariableValues([
                        'consult_name'    => $consult->name ?? 'N/A',
                        'consult_email'   => $consult->email ?? 'N/A',
                        'consult_phone'   => $consult->phone ?? 'N/A',
                        'consult_content' => 'The author of the advertisement is '.$property->author->name .' => phone:'.$property->author->phone,
                        'consult_link'    => $link ?? 'N/A',
                        'consult_subject' => $subject ?? 'N/A',
                    ])
                    ->sendUsingTemplate('notice');
                // ----------by magh ------

                $first_name = auth('account')->user()->first_name;
                $last_name = auth('account')->user()->last_name;
                $email = auth('account')->user()->email;
                $phone = auth('account')->user()->phone;
                $fullName = $first_name.' '.$last_name;  
               
                EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
                    ->setVariableValues([
                        'consult_name'    => 'اسم المعلن '.$property->author->name ?? 'N/A',
                        'consult_email'   => 'بريد المعلن '.$property->author->email ?? 'N/A',
                        'consult_phone'   => 'جوال المعلن '.$property->author->phone ?? 'N/A',
                        'consult_content' => '  اسم القطعة:  ('.$property->name.') <br>  عدد القطع:  ('.$params['guests'].') <br>   اجمالي السعر: ( '.$total_price_last.' )',
                        'consult_link'    => $link ?? 'N/A',
                        'consult_subject' => $subject ?? 'N/A',
                    ])
                    ->sendUsingTemplate('notice_accessory', $consult->email, $args = [], $debug = false, $type = 'plugins', $subject = "تم ارسال طلب شراء ملحق ");
                
 
                
                }else{
                    
                    EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
                        ->setVariableValues([
                            'consult_name'    => $consult->name ?? 'N/A',
                            'consult_email'   => $consult->email ?? 'N/A',
                            'consult_phone'   => $consult->phone ?? 'N/A',
                            'consult_content' => 'N/A',
                            'consult_link'    => $link ?? 'N/A',
                            'consult_subject' => $subject ?? 'N/A',
                        ])
                        ->sendUsingTemplate('notice', $sendTo);
                    // ----------by magh ------
    
                    EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
                        ->setVariableValues([
                            'consult_name'    => $consult->name ?? 'N/A',
                            'consult_email'   => $consult->email ?? 'N/A',
                            'consult_phone'   => $consult->phone ?? 'N/A',
                            'consult_content' => 'The author of the advertisement is '.$property->author->name .' => phone:'.$property->author->phone,
                            'consult_link'    => $link ?? 'N/A',
                            'consult_subject' => $subject ?? 'N/A',
                        ])
                        ->sendUsingTemplate('notice');
                    // ----------by magh ------
    
                    // $first_name = auth('account')->user()->first_name;
                    // $last_name = auth('account')->user()->last_name;
                    // $email = auth('account')->user()->email;
                    // $phone = auth('account')->user()->phone;
                    // $fullName = $first_name.' '.$last_name;  
                   
                    EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
                        ->setVariableValues([
                            'consult_name'    => 'اسم المعلن '.$property->author->name ?? 'N/A',
                            'consult_email'   => 'بريد المعلن '.$property->author->email ?? 'N/A',
                            'consult_phone'   => 'جوال المعلن '.$property->author->phone ?? 'N/A',
                            'consult_content' => 'اجمالي السعر: ( '.$total_price_last.' ) <br> من تاريخ ('.$from_date.') الى تاريخ ('.$to_date.')',
                            'consult_link'    => $link ?? 'N/A',
                            'consult_subject' => $subject ?? 'N/A',
                        ])
                        ->sendUsingTemplate('notice_to_admin', $consult->email, $args = [], $debug = false, $type = 'plugins', $subject = "تم ارسال طلب حجز جديد");
                    
                }

            }
        



            // Commit the transaction
                DB:: commit();

                // Do something
            
            
                if(false){
                    
                    $last_id = $transactionRepository->select(['id'])->latest()->first();
                    $last_id = is_null($last_id) ? 1 : $last_id->id +1;
                    $plugin = new PayTaps();
                    
                    $base_url = $plugin->getBaseUrl();
                    $request_url = 'payment/request';
                  
                    $data_PayTaps = [
                        "tran_type" => "sale",
                        "tran_class" => "ecom",
                        'payment_methods' => ['all'],
                        "cart_id" => "$last_id",
                        "cart_currency" => 'SAR',
                        "cart_amount" => $bookingData['total_price'],
                        "cart_description" => "managed form sample",
                        "paypage_lang" => "ar",
                        "callback" => route('callback_url_api'), // Nullable - Must be HTTPS, otherwise no post data from paytabs
                        "return" => route('return_url_api'), // Must be HTTPS, otherwise no post data from paytabs , must be relative to your site URL
                        "customer_details" => [
                            "name" => $bookingData['name'],
                            "email" =>  $bookingData['email'],
                            "phone" =>  $bookingData['phone'],
                            "street1" => 'street1',
                            "city" => 'city',
                            "state" => 'state',
                            "country" => 'sa',
                            "zip" => '1234',
                            "ip" => file_get_contents("https://api.ipify.org")
                        ],
                        "shipping_details" => [
                            "name" => $bookingData['name'],
                            "email" =>  $bookingData['email'],
                            "phone" =>  $bookingData['phone'],
                            "street1" => 'street1',
                            "city" => '02',
                            "state" => 'state',
                            "country" => 'sa',
                            "zip" => '1234',
                        ]
                    ];
                    //https://caravanek.com/return-url-from-paytabs
                    $page = $plugin->send_api_request($request_url, $data_PayTaps);
                    if (!empty($page['redirect_url'])) {
    
                        //$account = $this->transactionRepository->findOrFail();
                        $transactionRepository->createOrUpdate([
                            "credits" => $consult->total_price, 	 	
                            "description" => "description", 	 	
                            "user_id" => $user_id, 	 
                            "account_id" => $vendor_id, 	 	
                            "type" => "add", 	 
                            "consult_id" => $consult->id,
                            'return_url' => url()->previous(),
                            "status" => 0,
                         ]);
                        //Transaction::create([status=>0])
                       return  $page['redirect_url'];
                       //return  redirect()->away($page['redirect_url']);
                    }else{
                        Log::info('empty redirect');
                    }
        
                }


                //return $response->setMessage(trans('plugins/real-estate::consult.email.success'));



                
              
            } catch (\Throwable $e) {
                // An error occured
                DB::rollback();
                
                // and throw the error again.
                throw $e;
            }

            //======================================---------------- 
            //======================================---------------- 

            /*$order_description = 'Description';
            $pay= paypage::sendPaymentCode('all')
            ->sendTransaction('sale')
            ->sendCart($consultRepository->id, $total_price_last, $order_description)
            ->sendCustomerDetails(  
                $bookingData->name, 
                $bookingData->email, 
                $bookingData->phone, 
                "address",
                "city ",
                $customer->state,
                "country", 
                "12",
                $customer->ip)
            ->sendShippingDetails('same as billing')
            ->sendURLs(route('return'),route('callback_url'))
            ->sendLanguage('ar')
            ->create_pay_page();



            return $pay;*/
           












            // $order_id = 1;
            // $order_description = 'Description';
            // $pay= paypage::sendPaymentCode('all')
            // ->sendTransaction('sale')
            // ->sendCart($order_id, $total_price_last, $order_description)
            // ->sendCustomerDetails(  
            //     $bookingData->name, 
            //     $bookingData->email, 
            //     $bookingData->phone, 
            //     $customer->address,
            //     $customer->city,
            //     $customer->state,
            //     $customer->country, 
            //     $customer->zip,
            //     $customer->ip)
            // ->sendShippingDetails('same as billing')
            // ->sendURLs(route('return'),route('callback_url'))
            // ->sendLanguage('ar')
            // ->create_pay_page();





        } catch (Exception $exception) {
            info($exception->getMessage());
            return $response
                ->setError()
                ->setMessage(trans('plugins/real-estate::consult.email.failed'));
        }




    }


    public function callback_url(Request $request,
      BaseHttpResponse $response ,
      TransactionInterface $transactionRepository)
    {
       $transaction = $transactionRepository->findById($request->cart_id);
       $status = $request->payment_result['response_status'];
       if($status =='A'){
            $transaction->status = 3;// success 
       }elseif($status == 'H'){
        $transaction->status = 2;// on hold, we will use a cron job for it

       }else{
        $transaction->status = 1; // failed
        //delete consult
        //$transaction->consult()->delete(); 
        DB::table('re_consults')->where('id',$transaction->consult_id)->delete();
       }
       $transaction->reference = $request->tran_ref;
       $transaction->details = $request;
       $transaction->save();
       return true;
    }

    public function return_url(Request $request, 
        BaseHttpResponse $response , 
        TransactionInterface $transactionRepository)
    {
        // Log::info('callback');
       //$request =(object) $request;
       /* dd($request->cart_id);
       $transaction = $transactionRepository->findById($request->cart_id);
       $status = $request->payment_result->response_status;

       if($status =='A'){
            $transaction->status = 3;// success
       }elseif($status == 'H'){
        $transaction->status = 2;// on hold, we will use a cron job for it

       }else{
        $transaction->status = 1; // failed
       }
       $transaction->reference = $request->tran_ref;
       $transaction->save();
       return true;*/
       
       SeoHelper::setTitle(__('Payment return'));

      
       

       
       Theme::breadcrumb()
           ->add(__('Home'), route('public.index'));

   //  dd(url()->current());

       return Theme::scope('real-estate.payment')->render();
   

    }



    public function contact(Request $request,   BaseHttpResponse $response ){

        SeoHelper::setTitle(__('contact'));

      
       

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'));
  
        return Theme::scope('real-estate.contact')->render();

    }



    public function getConsultAfterPayment(){
       
       $transaction = DB::table('re_transactions')
       //->join('re_properties','re_transactions.property_id','=','re_properties.id')
       ->where('user_id',  auth('account')->id())
       ->latest()
       ->first();
        
       return $transaction;
    }

    private function formatDate($_date){
        $date = null;
        $_date = explode('/',$_date);
        if( !empty($_date[0]) &&  !empty($_date[1]) &&  !empty($_date[2]) ){
            $date = $_date[2].'-'.$_date[1].'-'.$_date[0];
            if( !strtotime($date) ) $date = null;
        }
        return $date;
    }

    /**
     * @param string $key
     * @param SlugInterface $slugRepository
     * @param PropertyInterface $propertyRepository
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getProperty(string $key, SlugInterface $slugRepository, PropertyInterface $propertyRepository)
    { 
        $slug = $slugRepository->getFirstBy([
            'slugs.key'      => $key,
            'reference_type' => Property::class,
            'prefix'         => SlugHelper::getPrefix(Property::class),
        ]); 

        if (!$slug) {
            abort(404);
        } 

        $property = $propertyRepository->getProperty($slug->reference_id); 

        if (!$property) {
            abort(404);
        }

        $property->loadMissing(config('plugins.real-estate.real-estate.properties.relations'));

        if ($property->slugable->key !== $key) {
            return redirect()->to($property->url);
        }

        // dd($property->content);
        SeoHelper::setTitle($property->name)->setDescription(Str::words($property->content, 120));

        $meta = new SeoOpenGraph;
        if ($property->image) {
            $meta->setImage(RvMedia::getImageUrl($property->image));
        }
        $meta->setDescription(Str::words($property->content, 120));
        $meta->setUrl($property->url);
        $meta->setTitle($property->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add($property->name, $property->url);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PROPERTY_MODULE_SCREEN_NAME, $property);

        if (function_exists('admin_bar')) {
            admin_bar()->registerLink(__('Edit this property'), route('property.edit', $property->id));
        }

        $images = [];
        foreach ($property->images as $image) {
            $images[] = RvMedia::getImageUrl($image, null, false, RvMedia::getDefaultImage());
        }

        /*Get features and features group start here*/
        $property_id = $property->id;
        $features = DB::table('re_features') 
            ->join('feature_categories', 're_features.id', '=', 'feature_categories.feature_id')
            ->select('re_features.id','re_features.name','re_features.type','re_features.select_options','feature_categories.feature_id','feature_categories.property_id','feature_categories.value')
            ->where('feature_categories.property_id', '=', $property_id)
            ->get()->toArray();

        // approved
        $consults = DB::table('re_consults')->select('property_id','from_date','to_date')->where('property_id', '=', $property_id)->where('status', '=', 'approved')->get()->toArray();


        /*Get features and features group end here*/

        return Theme::scope('real-estate.property', compact('property', 'images', 'features','consults'))->render();
    }

    private function getAllBookedDates($date1, $date2, $format = 'Y-m-d' ) {
        $dates = array();
        $t1 = strtotime($date1);
        $t2 = strtotime($date2);
        while( $t1 < $t2 ) {
            $dates[] = date($format, $t1);
            $t1 = strtotime('+1 day', $t1);
        }
        return $dates;
    }
    
    public function parseParamFilters($request,$source = '',$id = null){

        // location
        // latlng
        // pickup
        // dropoff
        // guests
        $perPage = (int)$request->input('per_page') ? (int)$request->input('per_page') : (int)theme_option('number_of_properties_per_page',
            12);
        if( !$perPage) $perPage = 12;
        
        $filters = [ 'sort_by'     => $request->input('sort_by')];

        if( $source == 'type')      $filters['type'] = $id;
        if( $source == 'category')  $filters['category_id'] = $id;
        
        $location = $request->input('location');
        $latlng = $request->input('latlng');
        if( !empty($location)){ 
            if( !empty($latlng) ){
                $filters['latlng'] = $latlng;
            } else {
                $city_id = @DB::table('cities')->select('id')->where('name', 'LIKE',trim($location))->get()->toArray()[0]->id;
                if( $city_id )
                    $filters['city_id'] = $city_id;
                else
                    $filters['location'] = trim($location);
            }   
        }


        $keyword = $request->input('keyword');
        if( !empty($keyword) )
            $filters['keyword'] = $keyword;



        $guests = $request->input('guests');
        if( !empty($guests) )
            $filters['guests'] = $guests;

        $pickup = $request->input('pickup');
        $dropoff = $request->input('dropoff');

        if( !empty($pickup) && !empty($dropoff) ){
            $pickup = explode("/",$pickup);
            $dropoff = explode("/",$dropoff);
            $_pickup = trim($pickup[2]).'-'.trim($pickup[1]).'-'.trim($pickup[0]);
            $_dropoff = trim($dropoff[2]).'-'.trim($dropoff[1]).'-'.trim($dropoff[0]);
            
            $bookedDates = [];
            $disIds = [];
            $AllBookings = DB::select("SELECT property_id,from_date,to_date FROM re_consults WHERE status IN ('unread','approved') AND '".date("Y-m-d")."' < `to_date`");
            foreach($AllBookings as $bk){
                $bk_dates = $this->getAllBookedDates(trim($bk->from_date),trim($bk->to_date));
                if( in_array($_pickup,$bk_dates) || in_array($_dropoff,$bk_dates) ){
                    $disIds[] = (int)$bk->property_id;
                }
            }
            
            if( !empty($disIds))
                $filters['not_ids']= $disIds;
        }
    
        $max_price = ceil($request->input('max_price'));
        $min_price = floor($request->input('min_price'));
        $rating = intval($request->input('rating'));
        $category_ids = $request->input('category_ids');
        
        if( !empty($category_ids) ) 
            $filters['category_ids'] = $category_ids;

        if( $max_price )
            $filters['max_price'] = $max_price;

        if( $min_price)
            $filters['min_price'] = $min_price;

        if( $rating)
            $filters['rating'] = $rating;     

        $params = [
            'paginate' => [
                'per_page'      => $perPage,
                'current_paged' => (int)$request->input('page', 1),
            ],
            'order_by' => ['re_properties.created_at' => 'DESC'],
            'with'     => config('plugins.real-estate.real-estate.properties.relations'),
        ];

        return [$filters,$params];
    }

    /**
     * @param Request $request
     * @param PropertyInterface $propertyRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Response
     */
    public function getProperties(
        Request $request,
        PropertyInterface $propertyRepository,
        CategoryInterface $categoryRepository,
        BaseHttpResponse $response
    ) {
        SeoHelper::setTitle(__('Latest Advertisements').' - '.theme_option('site_title'));

        list($filters, $params) = $this->parseParamFilters($request,'all');
        // Log::info($filters, $params );
        $properties = $propertyRepository->getProperties($filters, $params);
        
        if ($request->ajax()) {
           
            return $response->setData(Theme::partial('real-estate.properties.items', ['properties' => $properties]));
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Properties'), route('public.properties'));

        $categories = $categoryRepository->pluck('name', 'id');
        $type = false;

        return Theme::scope('real-estate.properties', compact('type','properties', 'categories'))->render();
    }

    /**
     * @param Request $request
     * @param PropertyInterface $propertyRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Response
     */
    public function getPropertyType(
        $key,
        Request $request,
        SlugInterface $slugRepository,
        PropertyInterface $propertyRepository,
        CategoryInterface $categoryRepository,
        BaseHttpResponse $response,
        TypeInterface $typeRepository
    ) {
        $slug = $key;
        $type = $typeRepository->getFirstBy(
            ['slug' => $slug],
            ['*']
        ); 
        if (!$type) {
            abort(404);
        } 
        if($type->name == 'Accessory'){
            $type_name = 'Accessories';
        }else{
            $type_name = $type->name;
        }

        SeoHelper::setTitle(__($type_name))->setDescription(__($type_name).' - '.theme_option('seo_title'));

        $meta = new SeoOpenGraph;
        $meta->setDescription(__($type_name).' - '.theme_option('seo_title'));
        $meta->setUrl('/listing/'.$slug);
        $meta->setTitle(__($type_name));
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__($type_name),'');

        list($filters, $params) = $this->parseParamFilters($request,'type',$type->slug);

        $properties = $propertyRepository->getProperties($filters, $params);
        $categories = $categoryRepository->pluck('name', 'id');

        return Theme::scope('real-estate.property-type', compact('type', 'properties','categories'))->render();
    }

    /**
     * @param Request $request
     * @param PropertyInterface $propertyRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Response
     */
    public function getPropertyCategory(
        $key,
        Request $request,
        SlugInterface $slugRepository,
        PropertyInterface $propertyRepository,
        CategoryInterface $categoryRepository
    ) {
        $slug = $slugRepository->getFirstBy([
            'slugs.key'      => $key,
            'reference_type' => Category::class,
            'prefix'         => SlugHelper::getPrefix(Category::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $category = $categoryRepository->getFirstBy(
            ['id' => $slug->reference_id],
            ['*'],
            ['slugable']
        );

        if (!$category) {
            abort(404);
        }
        
        SeoHelper::setTitle($category->name)->setDescription(Str::words($category->description, 120));

        $meta = new SeoOpenGraph;
        $meta->setDescription($category->description);
        $meta->setUrl($category->url);
        $meta->setTitle($category->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add($category->name, $category->url);

        list($filters, $params) = $this->parseParamFilters($request,'category',$category->id);

        $properties = $propertyRepository->getProperties($filters, $params);
        $type = false;
        return Theme::scope('real-estate.property-category', compact('category', 'properties','type'))->render();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param null $title
     * @param CurrencyInterface $currencyRepository
     * @return BaseHttpResponse
     */
    public function changeCurrency(
        Request $request,
        BaseHttpResponse $response,
        CurrencyInterface $currencyRepository,
        $title = null
    ) {
        if (empty($title)) {
            $title = $request->input('currency');
        }

        if (!$title) {
            return $response;
        }

        $currency = $currencyRepository->getFirstBy(['title' => $title]);

        if ($currency) {
            cms_currency()->setApplicationCurrency($currency);
        }

        return $response;
    }

    /**
     * @param PropertyInterface $propertyRepository
     * @return Feed
     */
    public function getPropertyFeeds(PropertyInterface $propertyRepository)
    {
        if (!is_plugin_active('rss-feed')) {
            abort(404);
        }

        $data = $propertyRepository->getProperties([], [
            'take' => 20,
            'with' => ['slugable', 'category', 'author'],
        ]);

        $feedItems = collect([]);

        foreach ($data as $item) {
            $imageURL = RvMedia::getImageUrl($item->image, null, false, RvMedia::getDefaultImage());

            $feedItems[] = FeedItem::create()
                ->id($item->id)
                ->title(clean($item->name))
                ->summary(clean($item->description))
                ->updated($item->updated_at)
                ->enclosure($imageURL)
                ->enclosureType((new MimeTypes)->getMimeType(File::extension($imageURL)))
                ->enclosureLength(RssFeed::remoteFilesize($imageURL))
                ->category($item->category->name)
                ->link((string)$item->url)
                ->author($item->author_id ? $item->author->name : '');
        }

        return RssFeed::renderFeedItems($feedItems, 'Properties feed',
            'Latest properties from ' . theme_option('site_title'));
    }
}
