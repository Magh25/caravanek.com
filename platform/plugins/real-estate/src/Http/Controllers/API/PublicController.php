<?php
namespace Botble\RealEstate\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

use Botble\Base\Http\Responses\BaseHttpResponse;

use Botble\RealEstate\Repositories\Interfaces\TransactionInterface;

class PublicController extends Controller
{

    public function callback_url(Request $request,
    BaseHttpResponse $response ,
    TransactionInterface $transactionRepository)
    {
        Log::info('callback');
        $request = json_decode($request);

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
        return true;
    }

    public function return_url()
    {
        //
        return true;
    }

}
