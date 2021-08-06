<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Aws;


class LocationGroup extends Model
{
    use HasFactory;

    protected $table = 'locationgroups';

    protected $fillable = [
        'name',
        'company_id',
        'normalization_id',
        'colorcode',
        'status',
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

    public $orderBy = [];

    public function group()
    {
        return $this->hasOneThrough('App\Models\CompanyBranch', 'App\Models\GroupLocations','location_id','id');
    }

    public function grouplocationmap()
    {
        return $this->hasMany('App\Models\GroupLocations','group_id','id');
    }

    public function customer_details(){
        return $this->hasOne('App\Models\Company', 'id','company_id');
    }

    public function normalization_details(){
        return $this->hasOne('App\Models\NormalisationType', 'id','normalization_id');
    }

    public function getFilters()
	{
        $status         = \App\Helpers\Helper::makeSimpleArray($this->statuses, 'id,name');
		return [
            'reset' => route('master.normalizationtype.index'),
			'fields' => [
				'name'          => [
		            'type'      => 'text',
		            'label'     => 'Package Name'
		        ]
			]
		];
	}

    public function getListing($srch_params = [], $offset = 0)
    {
        // $listing = self::select(
        //         $this->table . ".*"
        //     )
            $listing = self::select(
                $this->table . ".*",
                'c.name'
            )
            ->join("company_branch AS c", function($join){
                $join->on('c.id', $this->table . '.company_id')
                    ->whereNull('c.deleted_at');
            })
            ->when(isset($srch_params['with']), function ($q) use ($srch_params) {
				return $q->with($srch_params['with']);
			})
            ->when(isset($srch_params['withCount']), function ($q) use ($srch_params) {
				return $q->withCount($srch_params['withCount']);
			})
            ->when(isset($srch_params['status']), function($q) use($srch_params){
                return $q->where($this->table . '.status', '=', $srch_params['status']);
            })
            ->when(isset($srch_params['name']), function($q) use($srch_params){
                return $q->where($this->table . ".name", "LIKE", "%{$srch_params['name']}%");
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
            $listing->orderBy($this->table . '.name', 'ASC');
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