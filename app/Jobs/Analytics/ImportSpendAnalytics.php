<?php

namespace App\Jobs\Analytics;

use App\Models\Analytics;
use App\Models\CompanyBranch;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportSpendAnalytics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $hauling_id;
    protected $branch_id;
    protected $start_date;
    protected $end_date;
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($hauling_id,$branch_id,$start_date,$end_date,$type)
    {
        $this->hauling_id = $hauling_id;
        $this->branch_id = $branch_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $getTotalInvoiceValue = 0;
       $branch = CompanyBranch::where('id',$this->branch_id)->first();

       if ($branch && $branch->recurring_id) {
            $api_key = \Config::get('settings.RECURLY_KEY');
            $client = new \Recurly\Client($api_key);
            $options = [
                'params' => [
                    'begin_time' => $this->start_date,
                    'end_time' => $this->end_date
                ]
            ];
            $account = $client->getAccount($branch->recurring_id);

            $invoices = $client->listAccountInvoices($account->getId(),$options);

            foreach($invoices as $invoice) {
                if ($invoice->getTotal() && $invoice->getState() == "paid") {
                    $getTotalInvoiceValue = $getTotalInvoiceValue + $invoice->getTotal();
                }
            }
       }else {
         $getTotalInvoiceValue = 0;
       }

       $analytics = Analytics::getAnalytics($this->branch_id,$this->start_date,'spend');
       $analytics->increment('spend',$getTotalInvoiceValue);
    }
}
