<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Models\File;

class CompanyHaulingCancellationRequest extends Model
{
    use HasFactory;

    protected $table = 'company_hauling_cancellation_requests';

    protected $fillable = [
        'id',
        'company_hauling_id',
        'user_id',
        'description',
        'status',
        'created_at'
    ];
    protected $hidden = [];
    protected $appends = [];
    
    public $orderBy = [];

    public function hauling_details(){
        return $this->belongsTo('App\Models\CompanyHauling', 'company_hauling_id','id');
    }
    
    public function getListing($srch_params = [], $offset = ''){
        $with_det = [];
        if(isset($srch_params['with'])){
            $with_det = $srch_params['with'];
        } 
        $select = $this->table.'.*';
        if(isset($srch_params['select'])){
            $select = $srch_params['select'];//implode(',',$srch_params['select']);
        }
        $listing = self::select($select)->with($with_det)//->where($this->table .'.deleted_at',NULL)
            ->when(isset($srch_params['company_hauling_id']), function($q) use($srch_params){
                return $q->where($this->table.".company_hauling_id", "=", $srch_params['company_hauling_id']);
            })
            
            ->when(isset($srch_params['date']), function($q) use($srch_params){
                return $q->whereDate($this->table.".date", "=", $srch_params['date']);
            }) 
            ->when(isset($srch_params['user_id']), function($q) use($srch_params){
                return $q->where($this->table.".user_id", "=", "{$srch_params['branch_address']}");
            })           
            ->when(isset($srch_params['status']), function($q) use($srch_params){
                if(is_array($srch_params['status'])){
                    return $q->whereIn($this->table.".status", $srch_params['status']);
                }
                else{
                    return $q->where($this->table.".status", "=", "{$srch_params['status']}");
                }
            })
            ->when(isset($srch_params['branch_id']), function($q) use($srch_params){
                return $q->whereHas('hauling_details',function($q) use ($srch_params){
                    if(is_array($srch_params['branch_id'])){
                        return $q->whereIn('branch_id',$srch_params['branch_id']);
                    }
                    else{
                        return $q->where('branch_id',$srch_params['branch_id']);
                    }
                });
            })
            ->when(isset($srch_params['ids']), function($q) use($srch_params){               
                return $q->whereIn('id',$srch_params['ids']);
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
