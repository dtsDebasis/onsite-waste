<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeCategory extends Model
{
    use HasFactory;

    protected $table    = 'kw_categories';

    protected $fillable = [
		'kw_category_id',
		'status',
		'title',
		'short_desc',
		'rank'
	];

    protected $hidden = [
        'created_at',
		'updated_at',
		'deleted_at',
    ];

	public $statusList = [
		0 => 'Disabled',
		1 => 'Enabled',
	];

	public $statusColorCodes = [
		0 => 'warning',
		1 => 'success',
		2 => 'danger',
		3 => 'secondary',
		4 => 'secondary',
		5 => 'danger',
	];

    public $orderBy = [];
	private $parent_rec = array();


	public function getFilters($reset_route = null) {		
		return [
			'reset'  => route($reset_route),
			'fields' => [
				'title'  => [
					'type'  => 'text',
					'label' => 'Name',
                ],
				'short_desc'  => [
					'type'  => 'text',
					'label' => 'Short Desc',
                ],
				'status'     => [
					'type'       => 'select',
					'label'      => 'Status',
					'options'    => $this->statusList,
				]
			],
		];
	}
	public function knowledge_content(){
		return $this->hasMany('App\Models\KnowledgeContent', 'category_id', 'id');
	}

    public function getListing($srch_params = [], $offset = 0) {
		$listing = self::select(
			$this->table . ".*"
		)
			->when(isset($srch_params['with']), function ($q) use ($srch_params) {
				return $q->with($srch_params['with']);
			})
			->when(isset($srch_params['created_at']), function ($q) use ($srch_params) {
				return $q->whereDate($this->table . ".created_at", $srch_params['created_at']);
			})
			->when(isset($srch_params['status']), function ($q) use ($srch_params) {
				return $q->where($this->table . ".status", $srch_params['status']);
			})
            ->when(isset($srch_params['title']), function ($q) use ($srch_params) {
				return $q->whereRaw("{$this->table}.title LIKE '%{$srch_params['title']}%'");
			})
			->when(isset($srch_params['kw_category_id']), function ($q) use ($srch_params) {
				return $q->where($this->table . ".kw_category_id", $srch_params['kw_category_id']);
			})
			->groupBy($this->table . '.id');

		
		if (isset($srch_params['id'])) {
			return $listing->where($this->table . '.id', '=', $srch_params['id'])
			// ->where($this->table . '.status', '=', 1)
				->first();
		}

		if (isset($srch_params['count'])) {
			return $listing->count();
		}

		if (isset($srch_params['orderBy'])) {
			$this->orderBy = \App\Helpers\Helper::manageOrderBy($srch_params['orderBy']);
			foreach ($this->orderBy as $key => $value) {
				$listing->orderBy($key, $value);
			}
		} else {
			$listing->orderBy($this->table . '.rank', 'ASC');
		}

		if (isset($srch_params['get_sql']) && $srch_params['get_sql']) {
			return [
				$listing->toSql(),
				$listing->getBindings(),
			];
		}

		if ($offset) {
			$listing = $listing->paginate($offset);
		} else {
			$listing = $listing->get();
		}

		return $listing;
	}

	public static function getMenu($pid = 0)
    {
        $menu = self::select()
            ->where([
                'status'    => '1',
                'kw_category_id' => $pid,
            ])
            ->orderBy('display_order', 'ASC')
            ->get();

        if (empty($menu)) {
            return false;
        }

        $menu     = $menu->toArray();
        $menus    = array();
        foreach ($menu as $key => $val) {
            if ($val['class'] && $val['method']) {
                $permission = \App\Models\Permission::checkPermission($val['class'], $val['method']);
                if ($permission) {
                    $menus[$key]                = $val;
                    $menus[$key]['child']       = self::getMenu($val['id']);
                }
            } else {
                $menus[$key]                    = $val;
                $menus[$key]['child']           = self::getMenu($val['id']);
            }
        }

        return $menus;
    }

	public function getParentMenu($id)
    {
        $p = $this->__getPreviousRecord($id);
        return $this->parent_rec;
    }

    private function __getPreviousRecord($id = 0)
    {
        $cat = self::where('id', $id)->first();

        if (!$cat) {
            $this->parent_rec = array_reverse($this->parent_rec);
            return false;
        }

        $this->parent_rec[] = $cat->toArray();
        $this->__getPreviousRecord($cat->master_service_category_id);
    }

	public function remove($id = null) {
		$data = $this->find($id);

		if (!$data) {
			return \App\Helpers\Helper::resp('Not a valid data', 400);
		}

		$data->delete();

		return \App\Helpers\Helper::resp('Record has been successfully deleted.', 200, $data);
	}
}
