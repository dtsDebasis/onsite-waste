<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyBranch extends Model
{
    use HasFactory; use SoftDeletes;

    protected $table = 'company_branch';

    protected $fillable = [
        'id',
        'uniq_id',
        'recurring_id',
        'company_id',
        'company_number',
        'addressdata_id',
        'name',
        'phone',
        'owner',
        'lead_source',
        'leadsource_2',
        'is_primary',
        'sh_rop',
        'sh_container_type',
        'rb_rop',
        'rb_container_type',
        'status'
    ];
    protected $hidden = [
        'deleted_at'
    ];

    public $orderBy = [];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function addressdata(){
        return  $this->belongsTo('App\Models\AddressData', 'addressdata_id');
    }

    public function branchspecialty(){
        return  $this->hasMany('App\Models\BranchSpecialty', 'company_branch_id','id');
    }

    public function package_details(){
        return $this->belongsTo('App\Models\Package', 'id','branch_id');
    }

    public function branchusers(){
        return $this->hasMany('App\Models\BranchUser', 'companybranch_id','id');
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
            ->when(isset($srch_params['company_number']), function($q) use($srch_params){
                return $q->where($this->table.".company_number", "=", $srch_params['company_number']);
            })
            ->when(isset($srch_params['recurring_id']), function($q) use($srch_params){
                return $q->where($this->table.".recurring_id", "=", $srch_params['recurring_id']);
            })
            ->when(isset($srch_params['addressdata_id']), function($q) use($srch_params){
                return $q->where($this->table.".addressdata_id", "=", $srch_params['addressdata_id']);
            })
            ->when(isset($srch_params['status']), function($q) use($srch_params){
                return $q->where($this->table.".status", "=", $srch_params['status']);
            })
            ->when(isset($srch_params['name']), function($q) use($srch_params){
                return $q->where($this->table.".name", "LIKE", "%{$srch_params['name']}%");
            })
            ->when(isset($srch_params['is_primary']), function($q) use($srch_params){
                return $q->where($this->table.".is_primary", "=", "{$srch_params['is_primary']}");
            })
            ->when(isset($srch_params['ids']), function($q) use($srch_params){
                return $q->whereIn($this->table.".id", $srch_params['ids']);
            });
            if(isset($srch_params['id'])){
                return $listing->where($this->table .'.id', '=', $srch_params['id'])
                                ->first();
            }
            if(isset($srch_params['uniq_id'])){
                return $listing->where($this->table .'.uniq_id', '=', $srch_params['uniq_id'])
                                ->first();
            }

            if(isset($srch_params['single_record'])){
                return $listing->latest()->first();
            }
            if(isset($srch_params['count'])){
                return $listing->count();
            }
            if (isset($srch_params['location'])) {
                $listing->where($this->table . ".name", "LIKE", "%{$srch_params['location']}%");

            }
            if($offset){
                $listing = $listing->orderBy($this->table .'.id', 'Asc')
                                ->paginate($offset);
            }
            else{
                if(isset($srch_params['pluck']) && is_array($srch_params['pluck'])){
                    if(count($srch_params['pluck']) > 1){
                        $listing = $listing->pluck($srch_params['pluck'][0],$srch_params['pluck'][1])->toArray();
                    }
                    else{
                        $listing = $listing->pluck($srch_params['pluck'][0])->toArray();
                    }
                }
                else{
                    $listing = $listing->orderBy($this->table .'.id', 'ASC')
                                ->get();
                }
            }
        return $listing;

    }

}
