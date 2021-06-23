<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeContentSpeciality extends Model
{
    use HasFactory;

    protected $table = 'kw_content_specialities';

    protected $fillable = [
        'id',
        'knowledge_content_id',
        'speciality_id',
        'created_at'
    ];
    protected $hidden = [
        'updated_at',
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
            ->when(isset($srch_params['knowledge_content_id']), function($q) use($srch_params){
                return $q->where($this->table.".knowledge_content_id", "=", $srch_params['knowledge_content_id']);
            })
            ->when(isset($srch_params['groupby']), function($q) use($srch_params){
                return $q->groupBy($this->table.".".$srch_params['groupby']);
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
                $listing = $listing->orderBy($this->table .'.tag', 'Asc')
                                ->paginate($offset);
            }
            else{
                $listing = $listing->orderBy($this->table .'.tag', 'ASC')
                                ->get();
            }
        return $listing;
            
    }
}