<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'id',
        'name',
        'service_type',
        'monthly_rate',
        'boxes_included',
        'compliance',
        'frequency_type',
        'frequency_number',
        'company_id',
        'branch_id',
        'last_hauling_date',
        'is_active',
        'created_at'
    ];
    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    public function companybranch(){
        return $this->belongsTo('App\Models\CompanyBranch', 'branch_id','id');
    }

    public $orderBy = [];

    public function getListing($srch_params = [], $offset = ''){
        $with_det = [];
        if(isset($srch_params['with'])){
            $with_det = $srch_params['with'];
        }
        $select = '*';
        if(isset($srch_params['select'])){
            $select = $srch_params['select'];//implode(',',$srch_params['select']);
        }
        $listing = self::select($select)->with($with_det)->where($this->table .'.deleted_at',NULL)
            ->when(isset($srch_params['company_id']), function($q) use($srch_params){
                return $q->where($this->table.".company_id", "=", $srch_params['company_id']);
            })
            ->when(isset($srch_params['branch_id']), function($q) use($srch_params){
                if(is_array($srch_params['branch_id'])){
                    return $q->whereIn($this->table.".branch_id", $srch_params['branch_id']);
                }
                else{
                    return $q->where($this->table.".branch_id", "=", $srch_params['branch_id']);
                }
            })
            ->when(isset($srch_params['assigned_and_not_assign']), function($q) use($srch_params){
                return $q->where(function($q) use ($srch_params){
                    return $q->where($this->table.".branch_id", "=", $srch_params['assigned_and_not_assign'])
                            ->orWhere($this->table.".branch_id", "=", 0);
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
                $listing = $listing->orderBy($this->table .'.id', 'Asc')
                                ->paginate($offset);
            }
            else{
                $listing = $listing->orderBy($this->table .'.id', 'ASC')
                                ->get();

            }


        return $listing;

    }
}


/*
1. amount
2. frequency (type and number)
3. boxes_included
4. includes_te(boolean)
5. includes_compliance(boolean)
6. te_monthly_rate
7. container_monthly_rate
8. duration  (type and number)
*/
