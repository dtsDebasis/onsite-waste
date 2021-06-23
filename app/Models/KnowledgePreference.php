<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgePreference extends Model
{
    use HasFactory;

    protected $table = 'kw_user_preference';

    protected $fillable = [
        'id',
        'knowledge_wizard_id',
        'user_id'
    ];
    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];


    public function knowledge_wizard()
	{
		return $this->hasOne('App\Models\KnowledgeWizard', 'id', 'knowledge_wizard_id');
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
            ->when(isset($srch_params['user_id']), function($q) use($srch_params){
                return $q->where($this->table.".user_id", "=", $srch_params['user_id']);
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