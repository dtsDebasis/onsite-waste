<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\File;
class HomeSection extends Model
{
    use HasFactory;

    protected $table = 'home_sections';

    protected $fillable = [
        'id',
        'name',
        'slug',   
        'created_at'
    ];
    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    public function settings_details(){
        return $this->hasOne('\App\Models\HomeSectionSetting','section_id','id');
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
        $listing = self::select($select)->with($with_det)
            ->when(isset($srch_params['slug']), function($q) use($srch_params){
                return $q->where($this->table.".slug", "=", $srch_params['slug']);
            })
            ->when(isset($srch_params['name']), function($q) use($srch_params){
                return $q->where($this->table.".name", "LIKE","%{$srch_params['name']}%");
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