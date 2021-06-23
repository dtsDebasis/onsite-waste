<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchUser extends Model
{
    use HasFactory;

    protected $table = 'branch_users';

    protected $fillable = [
        'user_id',
        'company_id',
        'companybranch_id'
    ];

    public $orderBy = [];


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
    public function companybranch()
    {
        return $this->belongsTo('App\Models\CompanyBranch', 'companybranch_id');
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
            ->when(isset($srch_params['companybranch_id']), function($q) use($srch_params){
                return $q->where($this->table.".companybranch_id", "=", $srch_params['companybranch_id']);
            })
            ->when(isset($srch_params['branch_status']), function($q) use($srch_params){
                return $q->whereHas('CompanyBranch',function($q) use ($srch_params){
                    return $q->where("status", "=", $srch_params['branch_status']);
                });
            })
  
            ->when(isset($srch_params['user_id']), function($q) use($srch_params){
                return $q->where($this->table.".user_id", "=", "{$srch_params['user_id']}");
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
