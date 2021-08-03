<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyHauling extends Model
{
    use HasFactory;

    protected $table = 'company_haulings';

    protected $fillable = [
        'company_id',
        'branch_id',
        'package_id',
        'supplies_requested',
        'request_type',
        'description',
        'driver_name',
        'date',
        'user_id',
        'number_of_boxes',
        'is_additional',
        'status',
        'created_at'
    ];

    public $orderBy = [];
    public function branch_details(){
        return $this->belongsTo('App\Models\CompanyBranch', 'branch_id','id');
    }
    public function company_details(){
        return $this->belongsTo('App\Models\Company', 'company_id','id');
    }
    public function package_details(){
        return $this->belongsTo('App\Models\Package', 'package_id','id');
    }
    public function manifest_details(){
        return $this->hasOne('App\Models\Manifest', 'hauling_id','id');
    }
    public function getListing($srch_params = [], $offset = ''){
        $with_det = [];
        if(isset($srch_params['with'])){
            $with_det = $srch_params['with'];
        }
        $select = '*';
        if(isset($srch_params['select'])){
            $select = $srch_params['select'];//implode(',',$srch_params['select']);
        }
        $listing = self::select($select)->with($with_det)//->where($this->table .'.deleted_at',NULL)
            ->when(isset($srch_params['company_id']), function($q) use($srch_params){
                return $q->where($this->table.".company_id", "=", $srch_params['company_id']);
            })
            ->when(isset($srch_params['addressdata_id']), function($q) use($srch_params){
                return $q->whereHas('branch_details', function($q) use ($srch_params){
                    return $q->where("addressdata_id", "=", $srch_params['addressdata_id']);
                });

            })
            ->when(isset($srch_params['manifestid']), function($q) use($srch_params){
                return $q->whereHas('manifest_details', function($q) use ($srch_params){
                    return $q->where("uniq_id", "=", $srch_params['manifestid']);
                });

            })
            ->when(isset($srch_params['branch_id']), function($q) use($srch_params){
                if(is_array($srch_params['branch_id'])){
                    return $q->whereIn($this->table.".branch_id", $srch_params['branch_id']);
                }
                else{
                    return $q->where($this->table.".branch_id", "=", $srch_params['branch_id']);
                }
            })
            ->when(isset($srch_params['date']), function($q) use($srch_params){
                return $q->whereDate($this->table.".date", "=", $srch_params['date']);
            })
            ->when((isset($srch_params['start_date']) && isset($srch_params['end_date'])), function($q) use($srch_params){
                return $q->whereDate($this->table.".date", ">=", $srch_params['start_date'])
                            ->whereDate($this->table.".date", "<=", $srch_params['end_date']);
            })
            ->when(isset($srch_params['status']), function($q) use($srch_params){
                if(is_array($srch_params['status'])){
                    return $q->whereIn($this->table.".status", $srch_params['status']);
                }
                else{
                    return $q->where($this->table.".status", "=", "{$srch_params['status']}");
                }
            })
            ->when(isset($srch_params['search']), function($q) use($srch_params){
                return $q->where(function($q) use ($srch_params) {
                    return $q->whereHas('branch_details', function($q) use($srch_params){
                        return $q->where("name","LIKE","%{$srch_params['search']}%")
                                ->orWhereHas('addressdata', function($q) use($srch_params){
                                    return $q->where("addressline1","LIKE","%{$srch_params['search']}%")
                                            ->orWhere("locality","LIKE","%{$srch_params['search']}%")
                                            ->orWhere("state","LIKE","%{$srch_params['search']}%");

                        })
                        ->orWhere("driver_name","LIKE","%{$srch_params['search']}%")
                        ->orWhereHas('package_details', function($q) use ($srch_params){
                            return $q->where("name","LIKE","%{$srch_params['search']}%");
                        });
                    });
                });
            });
            if(isset($srch_params['id'])){
                return $listing->where($this->table .'.id', '=', $srch_params['id'])
                                ->first();
            }
            if(isset($srch_params['single_record'])){
                return $listing->latest()->first();
            }
            if(isset($srch_params['count'])){
                return $listing->count();
            }
            if($offset){
                $listing = $listing->orderBy($this->table .'.id', 'DESC')
                                ->paginate($offset);
            }
            else{
                $listing = $listing->orderBy($this->table .'.id', 'DESC')
                                ->get();
            }
        return $listing;

    }
}
