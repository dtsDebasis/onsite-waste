<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeContent extends Model
{
    use SoftDeletes;

    protected $table        = 'kw_content';
    
    protected $fillable = [
        'status',
        'category_id',
        'sub_category_id',
        'title',
        'short_desc',
        'details',
        'location',
        'type',
		'rank',
        'waste_type',
        'service_type'
    ];

    protected $hidden = [
    	'updated_at',
    	'deleted_at'
    ];


    public $statuses = [
        0=> [
            'id' => 0,
            'name' => 'Disabled',
            'badge' => 'warning'
        ],
        1=> [
            'id' => 1,
            'name' => 'Enabled',
            'badge' => 'success'
        ],
    ];

    public $types = [
        'blog' => 'Blog',
        'research' => 'Research',
        'videos' => 'Videos',
        'resources' => 'Resources',
        'faq' => 'Faq'
    ];

    public $waste_types = [
        "te" => "TE Only",
        "pickup" => "Pickup Only", 
        "hybrid" => "Both TE and Pickup"
    ];

    protected $appends = [
		'dispimage'
	];
    

    public $orderBy = [];

    public function getDispimageAttribute()
	{
		return File::file($this->disp_img, 'no-profile.jpg');
	}

	public function disp_img()
	{
		$entityType = isset(File::$fileType['kw_content']['type']) ? File::$fileType['kw_content']['type'] : 0;
		return $this->hasOne('App\Models\File', 'entity_id', 'id')
			->where('entity_type', $entityType);
	}

    public function getFilters($category = 0, $sub_category = 0)
	{
        $status         = \App\Helpers\Helper::makeSimpleArray($this->statuses, 'id,name');
		return [
            'reset' => route('knowledgecontent.index', [
                'category'   => $category
                ]),
			'fields' => [
				'title'          => [
		            'type'      => 'text',
		            'label'     => 'Title'
		        ],
		        'short_desc'         => [
		            'type'      => 'text',
		            'label'     => 'Short Description'
		        ],
		        'category'        => [
		            'type'      => 'text',
		            'label'     => 'Category name'
		        ],
		        'sub_category'        => [
		            'type'      => 'text',
		            'label'     => 'Sub-Category name'
		        ],
                'type'        => [
		            'type'      => 'text',
		            'label'     => 'Type'
		        ],
		        'status'     => [
                    'type'       => 'select',
                    'label'      => 'Status',
                    'attributes' => [
                        'id' => 'select-status',
                    ],
                    'options'    => $status,
                ],
			]
		];
	}
    public function tags(){
        return $this->hasMany('App\Models\KnowledgeContentTag', 'knowledge_content_id', 'id');
    }


    public function states(){
        // return $this->hasManyThrough('App\Models\KnowledgeContentState', 'knowledge_content_id', 'id');
        return $this->belongsToMany('App\Models\LocationState', 'kw_content_states', 'kw_content_id', 'state_id');
    }
    public function locations(){
        // return $this->hasManyThrough('App\Models\LocationState', 'kw_content_states', 'kw_content_id', 'state_id');
    }
    public function specialities(){
        return $this->hasManyThrough('App\Models\KnowledgeContentTag', 'knowledge_content_id', 'id');
    }

    
    public function getListing($srch_params = [], $offset = 0)
    {
        $listing = self::select(
                $this->table . ".*",
                'c.title as category_name'
            )
            ->join("kw_categories AS c", function($join){
                $join->on('c.id', $this->table . '.category_id')
                    ->whereNull('c.deleted_at');
            })
            ->when(isset($srch_params['with']), function ($q) use ($srch_params) {
				return $q->with($srch_params['with']);
			})
            ->when(isset($srch_params['title']), function($q) use($srch_params){
                return $q->where($this->table . ".title", "LIKE", "%{$srch_params['title']}%");
            })
            ->when(isset($srch_params['short_desc']), function($q) use($srch_params){
                return $q->where($this->table . ".short_desc", "LIKE", "%{$srch_params['short_desc']}%");
            })
            ->when(isset($srch_params['category']), function($q) use($srch_params){
                return $q->where("c.title", "LIKE", "%{$srch_params['category']}%");
            })
            ->when(isset($srch_params['sub_category']), function($q) use($srch_params){
                return $q->where("c.title", "LIKE", "%{$srch_params['sub_category']}%");
            })
            ->when(isset($srch_params['status']), function($q) use($srch_params){
                return $q->where($this->table . '.status', '=', $srch_params['status']);
            })
            ->when(isset($srch_params['types']), function($q) use($srch_params){
                if(is_array($srch_params['types'])) {
                    return $q->whereIn($this->table . '.type', $srch_params['types']);
                } else {
                    return $q->where($this->table . '.type','IN', $srch_params['types']);
                }
            })
            ->when(isset($srch_params['tags']), function($q) use($srch_params){
                return $q->whereHas('tags',function($q) use ($srch_params){
                    return $q->where(function($q) use ($srch_params){
                        $tags = explode(',',$srch_params['tags']);
                        foreach($tags as $tkey=>$tval){
                            if($tkey == 0){
                                $q->where('tag','LIKE',"%{$tval}%");
                            }
                            else{
                                $q->orWhere('tag','LIKE',"%{$tval}%");
                            }
                        }
                    });
                });
            })
            ->when(isset($srch_params['categories']), function($q) use($srch_params){
                return $q->where(function($q) use ($srch_params){
                    $tags = explode(',',$srch_params['categories']);
                    foreach($tags as $tkey=>$tval){
                        if($tkey == 0){
                            $q->where($this->table . '.category_id',$tval);
                            //$q->orWhere($this->table . '.sub_category_id',$tval);
                        }
                        else{
                            $q->orWhere($this->table . '.category_id',$tval);
                            //$q->orWhere($this->table . '.sub_category_id',$tval);
                        }
                    }
                });
            })
            ->when(isset($srch_params['category_id']), function($q) use($srch_params){
                return $q->where($this->table . '.category_id', '=', $srch_params['category_id']);
            })
            ->when(isset($srch_params['sub_category_id']), function($q) use($srch_params){
                return $q->where($this->table . '.sub_category_id', '=', $srch_params['sub_category_id']);
            });

        if(isset($srch_params['id'])){
            return $listing->where($this->table . '.id', '=', $srch_params['id'])
                            ->first();
        }

   
        if(isset($srch_params['orderBy'])){
            $this->orderBy = \App\Helpers\Helper::manageOrderBy($srch_params['orderBy']);
            foreach ($this->orderBy as $key => $value) {
                $listing->orderBy($key, $value);
            }
        } else {
            $listing->orderBy($this->table . '.rank', 'ASC');
        }

        if($offset){
            $listing = $listing->paginate($offset);
        } else {
            $listing = $listing->get();
        }

        return $listing;
    }

    public function store($input = [], $id = 0, $request = null)
	{
		$data 						= null;
        if ($id) {
            $data = $this->getListing(['id' => $id]);

            if(!$data) {
				return \App\Helpers\Helper::resp('Not a valid data', 400);
			}

            $data->update($input);
        } else {
            $data   = $this->create($input);
		}
		$file   = \App\Models\File::upload($request, 'dsp_img', 'kw_content', $data->id);
		return \App\Helpers\Helper::resp('Changes has been successfully saved.', 200, $data);
    }
    
    public function remove($id = null)
	{
		$data = $this->getListing([
			'id'    => $id,
		]);

		if(!$data) {
			return \App\Helpers\Helper::resp('Not a valid data', 400);
		}

		$data->delete();

		return \App\Helpers\Helper::resp('Record has been successfully deleted.', 200, $data);
	}
}
