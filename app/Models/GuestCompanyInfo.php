<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestCompanyInfo extends Model
{
    use HasFactory;

    protected $table = 'guest_company_infos';

    protected $fillable = [
        'id',
        'user_id',
        'company_name',
        'company_email',
        'company_phone',
        'status',        
        'created_at'
    ];
    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id','id');
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
            ->when(isset($srch_params['user_id']), function($q) use($srch_params){
                return $q->where($this->table.".user_id", "=", $srch_params['user_id']);
            })
            ->when(isset($srch_params['company_name']), function($q) use($srch_params){
                return $q->where($this->table.".company_name", "LIKE","%{$srch_params['company_name']}%");
            })
            ->when(isset($srch_params['company_email']), function($q) use($srch_params){
                return $q->where($this->table.".company_email", "LIKE", "%{$srch_params['company_email']}%");
            })
            ->when(isset($srch_params['company_phone']), function($q) use($srch_params){
                return $q->where($this->table.".company_phone", "LIKE", "%{$srch_params['company_phone']}%");
            })
            ->when(isset($srch_params['search_by']), function ($q) use ($srch_params) {
				return $q->where(function($q) use($srch_params){
					return $q->whereRaw("{$this->table}.company_name LIKE '%{$srch_params['search_by']}%'")
							->orWhereRaw("{$this->table}.company_email LIKE '%{$srch_params['search_by']}%'")
							->orWhereRaw("{$this->table}.company_phone LIKE '%{$srch_params['search_by']}%'")
							->orWhereHas('user',function($q) use($srch_params){
								return $q->whereRaw("CONCAT(users.first_name, ' ', users.last_name) LIKE '%{$srch_params['search_by']}%'");
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