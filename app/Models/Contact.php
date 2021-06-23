<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'email',
        'phone',
        'description',
        'status',
        'created_at'
    ];

    public $orderBy = [];
    
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
           
            
            ->when(isset($srch_params['date']), function($q) use($srch_params){
                return $q->whereDate($this->table.".created_at", "=", $srch_params['date']);
            })
            ->when(isset($srch_params['name']), function($q) use($srch_params){
                return $q->where($this->table.".name", "LIKE", "%{$srch_params['name']}%");
            })
            ->when(isset($srch_params['email']), function($q) use($srch_params){
                return $q->where($this->table.".email", "LIKE", "%{$srch_params['email']}%");
            })
            ->when(isset($srch_params['phone']), function($q) use($srch_params){
                return $q->where($this->table.".phone", "LIKE", "%{$srch_params['phone']}%");
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
