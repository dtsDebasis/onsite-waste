<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index($type='trips',$num_of_month = 1, $branch_id = 0)
    {
        $date_format = 'y-m-d';
        for ($i=1; $i <= $num_of_month + 1 ; $i++) {
            $start_date = date('Y-m-01', strtotime(date($date_format, strtotime('-'.$i.' month'))));
            $end_date = date('Y-m-t', strtotime(date($date_format, strtotime('-'.$i.' month'))));

            switch ($type) {
                case 'trips':
                    Analytics::trips($start_date,$end_date,$branch_id);
                    break;
                case 'boxes':
                    Analytics::boxes($start_date,$end_date,$branch_id);
                    break;
                case 'weight':
                    Analytics::weight($start_date,$end_date,$branch_id);
                    break;
                case 'spend':
                    Analytics::spend($start_date,$end_date,$branch_id);
                    break;
                case 'cycles':
                    Analytics::cycles($start_date,$end_date,$branch_id);
                    break;
                case 'all':
                    Analytics::trips($start_date,$end_date,$branch_id);
                    Analytics::boxes($start_date,$end_date,$branch_id);
                    Analytics::weight($start_date,$end_date,$branch_id);
                    Analytics::spend($start_date,$end_date,$branch_id);
                    Analytics::cycles($start_date,$end_date,$branch_id);
                    break;
            }
        }

    }
}
