<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory; use SoftDeletes;

    protected $table = 'company';

    protected $fillable = [
        'company_name',
        'company_number',
        'phone',
        'email',
        'website',
        'specality_id',
        'lead_source',
        'leadsource_2',
        'addressdata_id',
        'is_active',
        'owner',
    ];
    protected $hidden = [
        'deleted_at'
    ];

    public $orderBy = [];

    public function addressdata(){
        return  $this->belongsTo('App\Models\AddressData', 'addressdata_id');
    }
    public function speciality(){
        return  $this->hasMany('App\Models\CompanySpeciality', 'company_id','id');
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

            ->when(isset($srch_params['addressdata_id']), function($q) use($srch_params){
                return $q->where($this->table.".addressdata_id", "=", $srch_params['addressdata_id']);
            })
            ->when(isset($srch_params['address']), function($q) use($srch_params){
                return $q->where(function($q) use ($srch_params){
                    return $q->whereHas('addressdata', function($q) use ($srch_params){
                        return $q->where("addressline1", "LIKE", "%{$srch_params['address']}%")
                                ->orWhere("address1", "LIKE", "%{$srch_params['address']}%");
                    });
                });
            })
            ->when(isset($srch_params['company_name']), function($q) use($srch_params){
                return $q->where($this->table.".company_name", "LIKE", "%{$srch_params['company_name']}%");
            })
            ->when(isset($srch_params['company_number']), function($q) use($srch_params){
                return $q->where($this->table.".company_number", "LIKE", "%{$srch_params['company_number']}%");
            })
            ->when(isset($srch_params['phone']), function($q) use($srch_params){
                return $q->where($this->table.".phone", "LIKE", "%{$srch_params['phone']}%");
            })
            ->when(isset($srch_params['email']), function($q) use($srch_params){
                return $q->where($this->table.".email", "LIKE", "%{$srch_params['email']}%");
            })
            ->when(isset($srch_params['owner']), function($q) use($srch_params){
                return $q->where($this->table.".owner", "=", "{$srch_params['owner']}");
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
