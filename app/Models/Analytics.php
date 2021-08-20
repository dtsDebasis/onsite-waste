<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Analytics;
use App\Models\CompanyBranch;
use App\Models\CompanyHauling;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\Analytics\ImportTripAnalytics;
use App\Jobs\Analytics\ImportBoxesAnalytics;
use App\Jobs\Analytics\ImportSpendAnalytics;
use App\Jobs\Analytics\ImportCyclesAnalytics;
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
        'sb_cycles',
        'rb_cycles'
    ];

    protected $appends = [
        'normalized_trips',
        'normalized_boxes',
        'normalized_weight',
        'normalized_spend',
        'normalized_sb_cycles',
        'normalized_rb_cycles',
    ];

    public function getNormalizedTripsAttribute()
    {
        $normalization_fact = $this->branch->normalization_fact ?? 1;
        return round($this->trips / $normalization_fact);
    }

    public function getNormalizedBoxesAttribute()
    {
        $normalization_fact = $this->branch->normalization_fact ?? 1;
        return round($this->boxes / $normalization_fact);
    }

    public function getNormalizedWeightAttribute()
    {
        $normalization_fact = $this->branch->normalization_fact ?? 1;
        return round($this->weight / $normalization_fact);
    }

    public function getNormalizedSpendAttribute()
    {
        $normalization_fact = $this->branch->normalization_fact ?? 1;
        return round($this->spend / $normalization_fact);
    }

    public function getNormalizedSbCyclesAttribute()
    {
        $normalization_fact = $this->branch->normalization_fact ?? 1;
        return round($this->sb_cycles / $normalization_fact);
    }

    public function getNormalizedRbCyclesAttribute()
    {
        $normalization_fact = $this->branch->normalization_fact ?? 1;
        return round($this->rb_cycles / $normalization_fact);
    }

    public function branch(Type $var = null)
    {
        return $this->hasOne('App\Models\CompanyBranch', 'id','branch_id');
    }

    public static function processAnalytics($start_date,$end_date,$branch_id,$type)
    {
        $branch = $branch_id == 0 ? null : $branch_id;
        if ($branch) {
            $analytics = Analytics::where('branch_id',$branch)->where('date',$start_date)->get();
        } else {
            $analytics = Analytics::where('date',$start_date)->get();
        }
        foreach ($analytics as $key => $data) {
            switch ($type) {
                case 'trips':
                    $data->trips = 0;
                    break;
                case 'boxes':
                    $data->boxes = 0;
                    break;
                case 'weight':
                    $data->weight = 0;
                    break;
                case 'spend':
                    $data->spend = 0;
                case 'cycles':
                    $data->sb_cycles = 0;
                    $data->rb_cycles = 0;
                    break;
                case 'all':
                    $data->trips = 0;
                    $data->boxes = 0;
                    $data->weight = 0;
                    $data->spend = 0;
                    $data->sb_cycles = 0;
                    $data->rb_cycles = 0;
                    break;
            }
            $data->save();
        }
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
                ImportCyclesAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                //self::addCycleAnalytics($hauling_id,$branch_id,$start_date,$end_date,$type);
                break;
            case 'all':
                ImportTripAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                ImportBoxesAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                ImportWeightAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);
                ImportSpendAnalytics::dispatch($branch_id,$start_date,$end_date,$type);
                ImportCyclesAnalytics::dispatch($hauling_id,$branch_id,$start_date,$end_date,$type);

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
        $branch = CompanyBranch::select('uniq_id')->find($branch_id);
        if ($branch) {
            $location_id = $branch->uniq_id;
            //$location_id = 2685160229; //Sample
            $url = 'locations/'.$location_id.'/cycleHistory' ;
            $data = \App\Helpers\Helper::callAPI('GET',$url,['groupBy' => 'month']);
            $result = json_decode($data)->results;
            if (count($result)) {
                foreach ($result as $key => $data) {
                    if ($data->waste_type == 'SB') {
                        $analytics = self::getAnalytics($branch_id,$data->timePeriodStart,'sb_cycles');
                        $analytics->increment('sb_cycles',$data->count);
                    } else {
                        $analytics = self::getAnalytics($branch_id,$data->timePeriodStart,'rb_cycles');
                        $analytics->increment('rb_cycles',$data->count);
                    }
                }
            }
        }
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
        $findYodaysAnalytics = Analytics::where('branch_id',$branch_id)
        ->where('date',$start_date)->whereDate('updated_at', '<=', Carbon::now()->subMinutes(50)->toDateTimeString())->first();
        if (!$findYodaysAnalytics) {
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
                case 'sb_cycles':
                    $analytics->sb_cycles = 0;
                    break;
                case 'rb_cycles':
                    $analytics->rb_cycles = 0;
                    break;
            }
        }

        $analytics->save();

        return $analytics;
    }
}
