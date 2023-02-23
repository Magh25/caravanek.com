<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

use Botble\RealEstate\Http\Controllers\PayTaps;
use Botble\RealEstate\Repositories\Interfaces\TransactionInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CheckTransactions
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $transactions;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Get list of uncomplete/pending Transactions
        $this->transactions = DB::table('re_transactions')
            //->where('status',  2)
            ->where(function($q){
                $q->where('status', 0)
                ->where('created_at','<',now()->subMinutes(5));
            })
            ->get();
            //Log::info($this->transactions);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->transactions->count() > 0) {

            $plugin = new PayTaps();
            $request_url = 'payment/query';

            foreach ($this->transactions as $transaction) {
                if ($transaction->status == 0) {
                    //$transaction->consult()->delete();
                    DB::table('re_consults')->where('id',$transaction->consult_id)->delete();
                } else {
                    $data = [
                        "tran_ref" => $transaction->reference
                    ];
                    $response = $plugin->send_api_request($request_url, $data);
                    $status = $response->payment_result->response_status;
                    if ($status == 'A') {
                        $transaction->status = 3; // success
                    } elseif ($status == 'H') {
                        $transaction->status = 2; // on hold, we will use a cron job for it

                    } else {
                        $transaction->status = 1; // failed
                        //delete consult
                        //$transaction->consult()->delete();
                        DB::table('re_consults')->where('id',$transaction->consult_id)->delete();
                    }
                    $transaction->save();
                }
            }
        }
    }
}
