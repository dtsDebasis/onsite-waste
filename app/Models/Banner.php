<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banner';

    protected $fillable = [
        'title',
        'short_desc',
        'button_link',
        'status',
    ];

    protected $hidden = [
    	'updated_at',
    	'deleted_at',
        'banner_img'
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

    protected $appends = [
		'banner'
	];

    public function getBannerAttribute()
	{
		return File::file($this->banner_img, 'no-profile.jpg');
	}

	public function banner_img()
	{
		$entityType = isset(File::$fileType['banner']['type']) ? File::$fileType['banner']['type'] : 0;
		return $this->hasOne('App\Models\File', 'entity_id', 'id')
			->where('entity_type', $entityType)->latest();
	}

    public $orderBy = [];

    public function getFilters()
	{
        $status         = \App\Helpers\Helper::makeSimpleArray($this->statuses, 'id,name');
		return [
            'reset' => route('banners.index'),
			'fields' => [
				'title'          => [
		            'type'      => 'text',
		            'label'     => 'Title'
		        ]
			]
		];
	}

    public function getListing($srch_params = [], $offset = 0)
    {
        $listing = self::select(
                $this->table . ".*"
            )
            ->when(isset($srch_params['with']), function ($q) use ($srch_params) {
				return $q->with($srch_params['with']);
			})
            ->when(isset($srch_params['title']), function($q) use($srch_params){
                return $q->where($this->table . ".title", "LIKE", "%{$srch_params['title']}%");
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
            $listing->orderBy($this->table . '.title', 'ASC');
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
        $banner_img = null;
        if ($id) {
            $data = $this->getListing(['id' => $id]);

            if(!$data) {
				return \App\Helpers\Helper::resp('Not a valid data', 400);
			}
            $banner_img = $data->banner_img;
            $data->update($input);
        } else {
            $data   = $this->create($input);
		}
		$file   = \App\Models\File::upload($request, 'banner', 'banner', $data->id);
        if($file && $banner_img){
            \App\Models\File::deleteFile($banner_img, true);
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