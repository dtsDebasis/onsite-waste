<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Models\File;
class Manifest extends Model
{
    use HasFactory;

    protected $table = 'manifests';

    protected $fillable = [
        'hauling_id',
        'uniq_id',
        'person_name',
        'date',
        'number_of_container',
        'items_weight',
        'branch_address',
        'status',
        'created_at'
    ];
    protected $hidden = ['manifest_doc'];
    protected $appends = ['manifest_document'];
    public function getManifestDocumentAttribute() {
        $data = [];
        if(isset($this->manifest_doc) && count($this->manifest_doc)){
            foreach($this->manifest_doc as $key => $val){
		        $data[] = File::file($val);
            }
        }
        return $data;
	}
    public function manifest_doc() {
		$entityType = isset(File::$fileType['manifest_document']['type']) ? File::$fileType['manifest_document']['type'] : 0;
		return $data = $this->hasMany('App\Models\File', 'entity_id', 'id')
			->where('entity_type', $entityType);
	}
    public $orderBy = [];

    public function hauling_details(){
        return $this->belongsTo('App\Models\CompanyHauling', 'hauling_id','id');
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
            ->when(isset($srch_params['hauling_id']), function($q) use($srch_params){
                return $q->where($this->table.".hauling_id", "=", $srch_params['hauling_id']);
            })
            ->when(isset($srch_params['uniq_id']), function($q) use($srch_params){
                return $q->where($this->table.".uniq_id", "=", $srch_params['uniq_id']);
            })

            ->when(isset($srch_params['date']), function($q) use($srch_params){
                return $q->whereDate($this->table.".date", "=", $srch_params['date']);
            })
            ->when(isset($srch_params['branch_address']), function($q) use($srch_params){
                return $q->where($this->table.".branch_address", "LIKE", "%{$srch_params['branch_address']}%");
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

    public function uploadManifestDoc($data = [], $request=[])
	{
		$avatar = $data->manifest_doc;
        //dd($request->file('manifest_doc'));
		$file 	= \App\Models\File::upload($request, 'manifest_doc', 'manifest_document', $data->id);
        //dd($file);
        if($file && $avatar){
            foreach($avatar as $val){
                \App\Models\File::deleteFile($val, true);
            }
		}
		return \App\Helpers\Helper::resp('Changes has been successfully saved.', 200, $file);
	}
}
