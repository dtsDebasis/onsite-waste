<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\File;
class GuestRequestInfo extends Model
{
    use HasFactory;

    protected $table = 'guest_request_infos';

    protected $fillable = [
        'id',
        'uniq_id',
        'user_id',
        'company_name',
        'company_email',
        'company_phone',
        'request_type',
        'description',
        'status',        
        'created_at'
    ];
    protected $hidden = [
        'updated_at',
        'deleted_at',
        'request_info_doc'
    ];
    protected $appends = ['request_info_document'];
    public function getRequestInfoDocumentAttribute() {
        $data = null;
        if(isset($this->request_info_doc)){
            foreach($this->request_info_doc as $val){
		        $data[] = File::file($val);
            }
        }
        return $data;
	}

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }
    public function request_info_doc() {
		$entityType = isset(File::$fileType['request_info_document']['type']) ? File::$fileType['request_info_document']['type'] : 0;
		return $data = $this->hasMany('App\Models\File', 'entity_id', 'id')
			->where('entity_type', $entityType);
	}
    public function reply_details(){
        return $this->hasMany('\App\Models\RequestReplyInfo','request_id','id');
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
            ->when(isset($srch_params['not_reply']), function($q) use($srch_params){
                return $q->doesntHave('reply_details');
                // return $q->whereNotExists(function($query) {                    
                //     return $query->selectRaw(1)
                //         ->from('request_reply_infos');
                // });
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

    public function uploadInfoDoc($data = [], $request)
	{
		$avatar = false;//$data->bol_file;
		$file 	= \App\Models\File::upload($request, 'info_documents', 'request_info_document', $data->id,0,$data->id);

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