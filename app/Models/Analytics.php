<?php

namespace App\Models;

use App\Models\CompanyBranch;
use App\Models\CompanyHauling;
use Illuminate\Database\Eloquent\Model;
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

    public static function trips($start_date,$end_date,$branch_id){
        $branch = $branch_id == 0 ? null : $branch_id;

        $haulingIds = self::getHaulingIds($branch);
        foreach ($haulingIds as $hauling_id => $branch_id) {
            $getManifestsCount = Manifest::where('hauling_id',$hauling_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where('status',1)
            ->count();

            $analytics = self::getAnalytics($branch_id,$start_date,'trips');
            $analytics->increment('trips',$getManifestsCount);
        }
    }

    public static function boxes($start_date,$end_date,$branch_id){
        $branch = $branch_id == 0 ? null : $branch_id;

        $haulingIds = self::getHaulingIds($branch);
        foreach ($haulingIds as $hauling_id => $branch_id) {
            $getManifestsBoxSum = Manifest::where('hauling_id',$hauling_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->sum('number_of_container');

            $analytics = self::getAnalytics($branch_id,$start_date,'boxes');
            $analytics->increment('boxes',$getManifestsBoxSum);
        }
    }

    public static function weight($start_date,$end_date,$branch_id){
        $branch = $branch_id == 0 ? null : $branch_id;

        $haulingIds = self::getHaulingIds($branch);
        foreach ($haulingIds as $hauling_id => $branch_id) {
            $getManifestsWeightSum = Manifest::where('hauling_id',$hauling_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->sum('items_weight');

            $analytics = self::getAnalytics($branch_id,$start_date,'weight');
            $analytics->increment('weight',$getManifestsWeightSum);
        }
    }

    public static function spend($start_date,$end_date,$branch_id){
        //TODO
    }

    public static function cycles($start_date,$end_date,$branch_id){
        //TODO
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
        }

        $analytics->save();

        return $analytics;
    }
}
