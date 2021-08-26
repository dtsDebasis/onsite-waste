<?php

namespace App\Http\Controllers\Api;

use Auth;
use stdClass;
use Exception;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\GroupCategory;
use App\Models\LocationGroup;
use App\Models\GroupLocations;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class AnalyticsController extends Controller
{
    public $successStatus = 200;
    public $message = "Success!!";

    public function index(Request $request)
    {
        try {
            $current_user = auth()->user();
            $company_id = $current_user ? $current_user->company_id : 0;
            $input = $request->all();

            switch ($input['format']) {
                case 'graph':
                    return $this->getTableData($company_id, $input);
                    break;
                case 'table':
                    return $this->getTableData($company_id, $input);
                    break;

                default:
                    return $this->getTableData($company_id, $input);
                    break;
            }
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function getGraphData($company_id, $input)
    {
        return Helper::rj($this->message, $this->successStatus, $input);
    }

    public function getTableData($company_id, $input)
    {
        $dates = $this->getStartEndDate($input);
        $input['locations'] = $input['locations'] ?? null;
        $locations = $input['locations'] ? explode(',',$input['locations']) : null;

        $input['groups'] = $input['groups'] ?? null;
        $groups = $input['groups'] ? explode(',',$input['groups']) : null;

        $groups = LocationGroup::with(['normalization_details','grouplocationmap'=> function ($q) use($locations,$groups) {
            $q->when($locations, function ($query,$locations) {
                $query->whereIn('location_id', $locations);
            })->when($groups, function ($query,$groups) {
                $query->whereIn('group_id', $groups);
            });
        }])
        ->whereHas('grouplocationmap' , function (Builder  $q) use($locations,$groups) {
            $q->when($locations, function ($query,$locations) {
                $query->whereIn('location_id', $locations);
            })->when($groups, function ($query,$groups) {
                $query->whereIn('group_id', $groups);
            });
        })
        ->where('company_id', $company_id)
        ->get();
       $data = [];

       $group = $input['group'] ?? 'group_vs_group';
       switch ($group) {
           case 'group_vs_group':
               $data = $this->groupVsGroup($data,$groups,$dates,$input);
               break;
           case 'locations_in_group':
                $data = $this->locationInGroup($data,$groups,$dates,$input);
               break;
           case 'locations_vs_locations':
                $data = $this->locationInGroup($data,$groups,$dates,$input);
               break;
       }

       return Helper::rj($this->message, $this->successStatus, $data);
    }

    public function locationInGroup($data,$groups,$dates,$input)
    {
        $i = 0;
        foreach ($groups as $key => $group) {
            foreach ($group->grouplocationmap as $l_key => $locationData) {
                $location = $locationData->location;
                $data[$i]['id'] =  $location->id;
                $data[$i]['name'] =  $location->name;
                $data[$i]['uniq_id'] = $location->uniq_id;
                $data[$i]['normalization_fact'] = $location->normalization_fact;
                $data[$i]['normalization']['id'] = $group->normalization_details->id;
                $data[$i]['normalization']['name'] = $group->normalization_details->name;
                $date_type = $input['date_type'] ?? 'yearly';
                $type = $input['type'] ?? 'all';
                if ($date_type == 'quarterly') {
                    foreach ($dates as $q_key => $qtr) {
                        //$data[$i]['analytics'][$q_key]['sum'] = $this->getDataByLocation($type,$group,$location->id,$qtr->period_start,$qtr->period_end);
                        $data[$i]['analytics'][$q_key]['by_date'] = $group->getAnaliticsDataByDate($location->id,$type,$qtr->period_start,$qtr->period_end);
                        $data[$i]['analytics'][$q_key]['period'] = $qtr->period;
                    }
                }else{
                    //$data[$i]['analytics']['sum'] = $this->getDataByLocation($type,$group,$location->id,$dates['period_start'],$dates['period_end']);
                    $data[$i]['analytics']['by_date'] = $group->getAnaliticsDataByDate($location->id,$type,$dates['period_start'],$dates['period_end']);
                    $data[$i]['analytics']['period'] = date("F Y",strtotime($dates['period_start'])) .' to '.date("F Y",strtotime($dates['period_end']));
                }
                $i++;
            }
        }
        return $data;
    }

    public function groupVsGroup($data,$groups,$dates,$input)
    {
        foreach ($groups as $key => $group) {
            $data[$group->name]['color_code'] = $group->colorcode;
            $data[$group->name]['normalization']['id'] = $group->normalization_details->id;
            $data[$group->name]['normalization']['name'] = $group->normalization_details->name;
            $date_type = $input['date_type'] ?? 'yearly';
            $type = $input['type'] ?? 'all';

            if ($date_type == 'quarterly') {
                foreach ($dates as $q_key => $qtr) {
                    //$data[$group->name]['analytics'][$q_key]['sum'] = $this->getDataByGroup($type,$group,$group->grouplocationmap,$qtr->period_start,$qtr->period_end);
                    $data[$group->name]['analytics'][$q_key]['by_date'] = $group->getAnalyticsDataByMultiLocation($group->grouplocationmap,$type,$qtr->period_start,$qtr->period_end);
                    $data[$group->name]['analytics'][$q_key]['period'] = $qtr->period;
                }
            } else {
                //$data[$group->name]['analytics']['sum'] = $this->getDataByGroup($type,$group,$group->grouplocationmap,$dates['period_start'],$dates['period_end']);
                $data[$group->name]['analytics']['by_date'] = $group->getAnalyticsDataByMultiLocation($group->grouplocationmap,$type,$dates['period_start'],$dates['period_end']);
                $data[$group->name]['analytics']['period'] = date("F Y",strtotime($dates['period_start'])) .' to '.date("F Y",strtotime($dates['period_end']));
            }
       }

       return $data;
    }

    public function getDataByGroup($type,$group,$locations,$period_start,$period_end)
    {
        $data = [];
        switch ($type) {
            case 'all':
                $data['trips'] = $group->analyticsCount($locations,'trips',$period_start,$period_end);
                $data['boxes'] = $group->analyticsCount($locations,'boxes',$period_start,$period_end);
                $data['weight'] = $group->analyticsCount($locations,'weight',$period_start,$period_end);
                $data['spend'] = $group->analyticsCount($locations,'spend',$period_start,$period_end);
                $data['sb_cycles'] = $group->analyticsCount($locations,'sb_cycles',$period_start,$period_end);
                $data['rb_cycles'] = $group->analyticsCount($locations,'rb_cycles',$period_start,$period_end);

                break;

            default:
                $data[$type] = $group->analyticsCount($locations,$type,$period_start,$period_end);
                break;
        }

        return $data;
    }

    public function getDataByLocation($type,$group,$location,$period_start,$period_end)
    {
        $data = [];
        switch ($type) {
            case 'all':
                $data['trips'] = $group->analyticsCountBySingleLocation($location,'trips',$period_start,$period_end);
                $data['boxes'] = $group->analyticsCountBySingleLocation($location,'boxes',$period_start,$period_end);
                $data['weight'] = $group->analyticsCountBySingleLocation($location,'weight',$period_start,$period_end);
                $data['spend'] = $group->analyticsCountBySingleLocation($location,'spend',$period_start,$period_end);
                $data['sb_cycles'] = $group->analyticsCountBySingleLocation($location,'sb_cycles',$period_start,$period_end);
                $data['rb_cycles'] = $group->analyticsCountBySingleLocation($location,'rb_cycles',$period_start,$period_end);

                break;

            default:
                $data[$type] = $group->analyticsCountBySingleLocation($location,$type,$period_start,$period_end);
                break;
        }

        return $data;
    }

    public function getStartEndDate($input)
    {
        $date_format = 'y-m-d';
        $date_type = $input['date_type'] ?? 'yearly';
        switch ($date_type) {
            case 'monthly':
                $data['period_start'] = $input['start_date'];
                $data['period_end'] = $input['end_date'];
                break;
            case 'quarterly':
                $start_date = date('Y-m-01', strtotime(date($date_format, strtotime('-12 month'))));
                $end_date = date('Y-m-28', strtotime(date($date_format, strtotime('-0 month'))));
                $data = $this->get_quarters($start_date, $end_date);
                break;
            case 'year_to_date':
                $end_date = date('Y-m-28', strtotime(date($date_format, strtotime('-0 month'))));
                $data['period_start'] = $input['start_date'];
                $data['period_end'] = $end_date;
                break;

            default:
                $start_date = date('Y-m-01', strtotime(date($date_format, strtotime('-12 month'))));
                $end_date = date('Y-m-28', strtotime(date($date_format, strtotime('-0 month'))));

                $data['period_start'] = $start_date;
                $data['period_end'] = $end_date;
                break;
        }
        return $data;
    }

    // get month name from number
    function month_name($month_number){
        return date('F', mktime(0, 0, 0, $month_number, 10));
    }


    // get get last date of given month (of year)
    function month_end_date($year, $month_number){
        return date("t", strtotime("$year-$month_number-1"));
    }

    // return two digit month or day, e.g. 04 - April
    function zero_pad($number){
        if($number < 10)
            return "0$number";

        return "$number";
    }

    // Return quarters between tow dates. Array of objects
    function get_quarters($start_date, $end_date){

        $quarters = array();

        $start_month = date( 'm', strtotime($start_date) );
        $start_year = date( 'Y', strtotime($start_date) );

        $end_month = date( 'm', strtotime($end_date) );
        $end_year = date( 'Y', strtotime($end_date) );

        $start_quarter = ceil($start_month/3);
        $end_quarter = ceil($end_month/3);

        $quarter = $start_quarter; // variable to track current quarter

        // Loop over years and quarters to create array
        for( $y = $start_year; $y <= $end_year; $y++ ){
            if($y == $end_year)
                $max_qtr = $end_quarter;
            else
                $max_qtr = 4;

            for($q=$quarter; $q<=$max_qtr; $q++){

                $current_quarter = new stdClass();

                $end_month_num = $this->zero_pad($q * 3);
                $start_month_num = ($end_month_num - 2);

                $q_start_month = $this->month_name($start_month_num);
                $q_end_month = $this->month_name($end_month_num);

                $current_quarter->period = "Qtr $q ($q_start_month - $q_end_month) $y";
                $current_quarter->period_start = "$y-$start_month_num-01";      // yyyy-mm-dd
                $current_quarter->period_end = "$y-$end_month_num-" . $this->month_end_date($y, $end_month_num);

                $quarters[] = $current_quarter;
                unset($current_quarter);
            }

            $quarter = 1; // reset to 1 for next year
        }

        return $quarters;

    }

    public function category(Request $request)
    {
        try {
            $current_user = auth()->user();
            $company_id = $current_user ? $current_user->company_id : 0;
            $groupCategory = GroupCategory::with('locationgroup')->where('company_id',$company_id)->get();
            return Helper::rj($this->message, $this->successStatus, $groupCategory);
        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

    public function searchLocation(Request $request)
    {
        try {
            $input = $request->all();
            $rules = array(
                //'categories' => 'required',
                //'groups' => 'required',
                'search_text' => 'required|min:3'
            );
            $validator = \Validator::make($input, $rules);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }
            $categories = explode(',', $request->categories);
            $groups = explode(',', $request->groups);
            $search_text = $request->search_text;
            $findLocations = GroupLocations::with('location')
            ->whereHas('location', function ($query) use ($search_text) {
                $query->where('name', 'like', '%' . $search_text . '%');
            })->when(!empty($groups), function ($query, $groups) {
                return $query->whereIn('group_id',$groups);
            })->get();

            $locations = [];
            foreach ($findLocations as $key => $location) {
                if ($location->location) {
                    $locations[] = $location->location;
                }
            }
            return Helper::rj($this->message, $this->successStatus, $locations);

        } catch (Exception $e) {
            return Helper::rj($e->getMessage(), $e->getCode());
        }
    }

}
