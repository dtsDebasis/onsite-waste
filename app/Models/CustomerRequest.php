<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    use HasFactory;

    protected $table = 'customer_requests';

    protected $fillable = [
        'company_id',
        'branch_id',
        'user_id',
        'type',
        'description',
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
    public function user_details(){
        return $this->belongsTo('App\Models\User', 'user_id','id');
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
            ->when((isset($srch_params['addressdata_id']) || isset($srch_params['location'])), function($q) use($srch_params){
                return $q->whereHas('branch_details', function($q) use ($srch_params){
                    if(isset($srch_params['addressdata_id'])){
                        $q->where("addressdata_id", "=", $srch_params['addressdata_id']);
                    }
                    if(isset($srch_params['location'])){
                        $q->where("name", "LIKE", "%{$srch_params['location']}%");
                    }
                    return $q;
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
                return $q->whereDate($this->table.".created_at", "=", $srch_params['date']);
            })
            ->when(isset($srch_params['type']), function($q) use($srch_params){
                return $q->where($this->table.".type", "=", $srch_params['type']);
            })
                      
            ->when(isset($srch_params['status']), function($q) use($srch_params){
                if(is_array($srch_params['status'])){
                    return $q->whereIn($this->table.".status", $srch_params['status']);
                }
                else{
                    return $q->where($this->table.".status", "=", "{$srch_params['status']}");
                }
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
