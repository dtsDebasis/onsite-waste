<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\File;
class HomeSectionSetting extends Model
{
    use HasFactory;

    protected $table = 'home_section_settings';

    protected $fillable = [
        'id',
        'section_id',
        'customer_type',
        'locations',   
        'created_at'
    ];
    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    

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
        $listing = self::select($select)->with($with_det)
            ->when(isset($srch_params['section_id']), function($q) use($srch_params){
                return $q->where($this->table.".section_id", "=", $srch_params['section_id']);
            })
            ->when(isset($srch_params['customer_type']), function($q) use($srch_params){
                return $q->where(function($q) use ($srch_params){
                    return $q->where($this->table.".customer_type", "=",$srch_params['customer_type'])
                            ->orWhere($this->table.".customer_type", "=",0);
            
                });
            })
            ->when(isset($srch_params['branch_ids']), function($q) use($srch_params){
                return $q->where(function($q) use ($srch_params){
                    $ids = $srch_params['branch_ids'];
                    return $q->whereRaw('FIND_IN_SET("1,5", locations)')
                            ->orWhereNull($this->table.".locations");
            
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