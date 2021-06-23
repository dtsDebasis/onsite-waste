<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\File;

class RequestReplyInfo extends Model
{
    use HasFactory;

    protected $table = 'request_reply_infos';

    protected $fillable = [
        'id',
        'request_id',
        'from_user_id',
        'to_user_id',
        'description', 
        'created_at'
    ];
    protected $hidden = [
        'updated_at',
        'deleted_at',
        'request_reply_doc'
    ];
    protected $appends = ['request_reply_document'];
    public function guest_request_info(){
        return $this->belongsTo('App\Models\GuestRequestInfo', 'request_id','id');
    }
    public function from_user_details(){
        return $this->belongsTo('App\Models\User', 'from_user_id','id');
    }
    public function to_user_details(){
        return $this->belongsTo('App\Models\User', 'to_user_id','id');
    }
    public function getRequestReplyDocumentAttribute() {
        $data = null;
        if(isset($this->request_reply_doc)){
            foreach($this->request_reply_doc as $val){
		        $data[] = File::file($val);
            }
        }
        return $data;
	}
    public function request_reply_doc() {
		$entityType = isset(File::$fileType['request_reply_document']['type']) ? File::$fileType['request_reply_document']['type'] : 0;
		return $data = $this->hasMany('App\Models\File', 'entity_id', 'id')
			->where('entity_type', $entityType);
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
            ->when(isset($srch_params['request_id']), function($q) use($srch_params){
                return $q->where($this->table.".request_id", "=", $srch_params['request_id']);
            })
            ->when(isset($srch_params['from_user_id']), function($q) use($srch_params){
                return $q->where($this->table.".from_user_id", "=","{$srch_params['from_user_id']}");
            })
            ->when(isset($srch_params['to_user_id']), function($q) use($srch_params){
                return $q->where($this->table.".to_user_id", "=", "{$srch_params['to_user_id']}");
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

    public function uploadReplyDoc($data = [], $request)
	{
		$avatar = false;//$data->bol_file;
		$file 	= \App\Models\File::upload($request, 'upload_file', 'request_reply_document', $data->id);

        if($file && $avatar){
            \App\Models\File::deleteFile($avatar, true);
		}		
		return \App\Helpers\Helper::resp('Changes has been successfully saved.', 200, $file);
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