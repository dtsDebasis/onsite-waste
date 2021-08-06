<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use Illuminate\Http\Request;
use App\Jobs\UpdateBranchAnalytics;

class AnalyticsController extends Controller
{
    public function index($type='all',$num_of_month = 12, $branch_id = 0)
    {
        $date_format = 'y-m-d';
        for ($i=1; $i <= $num_of_month; $i++) {
            $start_date = date('Y-m-01', strtotime(date($date_format, strtotime('-'.$i.' month'))));
            $end_date = date('Y-m-t', strtotime(date($date_format, strtotime('-'.$i.' month'))));
            //Analytics::processAnalytics($start_date,$end_date,$branch_id,$type);
            UpdateBranchAnalytics::dispatch($start_date,$end_date,$branch_id,$type);
        }
    }
}
