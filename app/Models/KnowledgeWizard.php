<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeWizard extends Model
{
    use SoftDeletes;

    protected $table        = 'kw_wizard';
    
    protected $fillable = [
        'status',
        'category_id',
        'sub_category_id',
        'title',
        'short_desc',
        'details',
        'benifits',
        'benifits_header',
		'rank'
    ];

    protected $appends = [
		'background'
	];

    protected $hidden = [
    	'category_id',
    	'sub_category_id',
        'back_img',
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

    public $orderBy = [];

    public function getBackgroundAttribute()
	{
		return File::file($this->back_img, 'no-profile.jpg');
	}

	public function back_img()
	{
		$entityType = isset(File::$fileType['kw_wizard']['type']) ? File::$fileType['kw_wizard']['type'] : 0;
		return $this->hasOne('App\Models\File', 'entity_id', 'id')
			->where('entity_type', $entityType)->latest();
	}

    public function getFilters($category = 0, $sub_category = 0)
	{
        $status         = \App\Helpers\Helper::makeSimpleArray($this->statuses, 'id,name');
		return [
            'reset' => route('knowledgewizard.index', [
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
		        // 'sub_category'        => [
		        //     'type'      => 'text',
		        //     'label'     => 'Sub-Category name'
		        // ],
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
                return $q->where("s.title", "LIKE", "%{$srch_params['sub_category']}%");
            })
            ->when(isset($srch_params['status']), function($q) use($srch_params){
                return $q->where($this->table . '.status', '=', $srch_params['status']);
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
        $background_img = null;
        if ($id) {
            $data = $this->getListing(['id' => $id]);

            if(!$data) {
				return \App\Helpers\Helper::resp('Not a valid data', 400);
			}
            $background_img = $data->back_img;
            $data->update($input);
        } else {
            $data   = $this->create($input);
		}
		$file   = \App\Models\File::upload($request, 'bk_img', 'kw_wizard', $data->id);
        if($file && $background_img){
            \App\Models\File::deleteFile($background_img, true);
        }
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
