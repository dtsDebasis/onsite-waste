<?php

namespace App\Models;

use App\Models\Analytics;
use App\Models\CompanyBranch;
use App\Models\CompanyHauling;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\Analytics\ImportTripAnalytics;
use App\Jobs\Analytics\ImportBoxesAnalytics;
use App\Jobs\Analytics\ImportSpendAnalytics;
use App\Jobs\Analytics\ImportWeightAnalytics;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Analytics extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'date',
        'trips',
        'boxes',
        'weight',
        'spend',
        'cycles'
    ];

    public function branch(Type $var = null)
    {
        return $this->hasOne('App\Models\CompanyBranch', 'id','branch_id');
    }

    public static function processAnalytics($start_date,$end_date,$branch_id,$type)
    {
        $branch = $branch_id == 0 ? null : $branch_id;
        $haulingIds = self::getHaulingIds($branch);
        foreach ($haulingIds as $hauling_id => $branch_id) {
           self::addAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type);
        }
    }

    public static function addAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type)
    {
        switch ($type) {
            case 'trips':
                ImportTripAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                //self::addTripAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type);
                break;
            case 'boxes':
                ImportBoxesAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                //self::addBoxAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type);
                break;
            case 'weight':
                ImportWeightAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                //self::addWeightAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type);
                break;
            case 'spend':
                ImportSpendAnalytics::dispatch($branch_id,$start_date,$end_date,$type);
                //self::addSpendAnalytics($branch_id,$start_date,$end_date,$type);
                break;
            case 'cycles':
                //TODO::Move to job
                self::addCycleAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type);
                break;
            case 'all':
                ImportTripAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                ImportBoxesAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                ImportWeightAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                ImportSpendAnalytics::dispatch($branch_id,$start_date,$end_date,$type);

                break;
        }
    }

    public static function addTripAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type)
    {
        $queryParam = [
            'hauling_id' => $hauling_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => 1
        ];

        //Dynamic Scope : analyticsQuery Manifest Model
        $getManifestsCount = Manifest::analyticsQuery($queryParam)->count();
        $analytics = self::getAnalytics($branch_id,$start_date,'trips');
        $analytics->increment('trips',$getManifestsCount);
    }

    public static function addBoxAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type)
    {
        $queryParam = [
            'hauling_id' => $hauling_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => 1
        ];

        //Dynamic Scope : analyticsQuery Manifest Model
        $getManifestsBoxSum = Manifest::analyticsQuery($queryParam)->sum('number_of_container');
        $analytics = self::getAnalytics($branch_id,$start_date,'boxes');
        $analytics->increment('boxes',$getManifestsBoxSum);
    }

    public static function addWeightAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type)
    {
        $queryParam = [
            'hauling_id' => $hauling_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => 1
        ];

        //Dynamic Scope : analyticsQuery Manifest Model
        $getManifestsWeightSum = Manifest::analyticsQuery($queryParam)->sum('items_weight');
        $analytics = self::getAnalytics($branch_id,$start_date,'weight');
        $analytics->increment('weight',$getManifestsWeightSum);
    }

    public static function addSpendAnalytics($branch_id,$start_date,$end_date,$type)
    {
        $getTotalInvoiceValue = 0;
        $branch = CompanyBranch::where('id',$branch_id)->first();
        $api_key = \Config::get('settings.RECURLY_KEY');
        $client = new \Recurly\Client($api_key);
        $options = [
            'params' => [
                //'limit' => 1,
                'begin_time' => $start_date,
                'end_time' => $end_date
            ]
        ];
        if ($branch && $branch->recurring_id) {
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

        $analytics = self::getAnalytics($branch_id,$start_date,'spend');
        $analytics->increment('spend',$getTotalInvoiceValue);
    }

    public static function addCycleAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type)
    {
        //TODO::Dependency on client
    }

    public static function getHaulingIds($branch)
    {
        $query = CompanyHauling::where('branch_id','!=',null);
        if ($branch) {
            $query = $query->where('branch_id',$branch);
        }

        $getHaulingIds = $query->pluck('branch_id','id');
        return $getHaulingIds;
    }

    public static function getAnalytics($branch_id,$start_date,$type)
    {
        $findAnalytics = Analytics::where('branch_id',$branch_id)
        ->where('date',$start_date)->first();
        if ($findAnalytics) {
            $analytics = $findAnalytics;
        }else{
            $analytics = new Analytics;
            $analytics->branch_id = $branch_id;
            $analytics->date = $start_date;
        }

        switch ($type) {
            case 'trips':
                $analytics->trips = 0;
                break;
            case 'boxes':
                $analytics->boxes = 0;
                break;
            case 'weight':
                $analytics->weight = 0;
                break;
            case 'spend':
                $analytics->spend = 0;
                break;
            case 'cycles':
                $analytics->cycles = 0;
                break;
        }

        $analytics->save();

        return $analytics;
    }
}
