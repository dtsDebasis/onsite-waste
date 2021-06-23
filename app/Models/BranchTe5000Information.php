<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Models\File;

class BranchTe5000Information extends Model
{
    use HasFactory;
    protected $table = 'branch_te_5000_informations';
    protected $fillable = [
        'branch_id',
        'package_id',
        'te_5000_info',
        'te_5000_imei',
        'created_at'
    ];
    protected $hidden = [];
    protected $appends = [];
    
    public $orderBy = [];
    public function branch_details(){
        return $this->belongsTo('App\Models\CompanyBranch', 'branch_id','uniq_id');
    }
    public function package_details(){
        return $this->belongsTo('App\Models\Package', 'package_id','id');
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
            ->when(isset($srch_params['branch_id']), function($q) use($srch_params){
                return $q->where($this->table.".branch_id", "=", $srch_params['branch_id']);
            }) 
            ->when(isset($srch_params['te_5000_imei']), function($q) use($srch_params){
                return $q->where($this->table.".te_5000_imei", "LIKE", "%{$srch_params['te_5000_imei']}%");
            })
            ->when(isset($srch_params['location']), function($q) use($srch_params){
                return $q->where(function($q) use($srch_params){
                    return $q->whereHas('branch_details',function($q) use($srch_params){
                        return $q->where("name", "LIKE", "%{$srch_params['location']}%");
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

    public function uploadManifestDoc($data = [], $request)
	{
		$avatar = $data->manifest_doc;
        //dd($request->file('manifest_doc'));
		$file 	= \App\Models\File::upload($request, 'manifest_doc', 'manifest_document', $data->id);
        //dd($file);
        if($file && $avatar){
            \App\Models\File::deleteFile($avatar, true);
		}		
		return \App\Helpers\Helper::resp('Changes has been successfully saved.', 200, $file);
	}
}
