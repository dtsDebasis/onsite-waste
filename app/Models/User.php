<?php

namespace App\Models;

use Hash;
use DB;
use App\Models\File;
use App\Helpers\Helper;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use HasApiTokens, Notifiable, SoftDeletes;

	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'status', // 0 = inactive and email unverified, 1 = active, 2 = inactive and email verified, 3 = user rejected by admin
		'username',
		'first_name',
		'last_name',
		'user_type',
		'customer_type',
		'permission_level',
		'company_id',
		'email',
		'phone',
		'designation',
		'password',
		'remember_token',
		'verified',
		'login_attempt',
		'company_relationship'
	];

	public $statuses = [
		0=> [
			'id' => 0,
			'name' => 'Pending email verification',
			'badge' => 'warning'
		],
		1=> [
			'id' => 1,
			'name' => 'Approved',
			'badge' => 'success'
		],
		2=> [
			'id' => 2,
			'name' => 'Pending acceptance',
			'badge' => 'danger'
		],
		3=> [
			'id' => 3,
			'name' => 'Rejected',
			'badge' => 'secondary'
		],
		4=> [
			'id' => 3,
			'name' => 'Blocked',
			'badge' => 'secondary'
		],
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		// 'email',
		'email_verified_at',
		'role_title',
		'password',
		'remember_token',
		'created_at',
		'updated_at',
		'deleted_at',
		'profile_pic',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	// for avatar
	protected $appends = [
		'avatar',
		'full_name',
	];

	public static $passwordValidator = [
		'required',
		'min:6', // must be at least 6 characters in length
		'max:16', // must be less than 16 characters in length
		// 'regex:/[a-z]/', // must contain at least one lowercase letter
		'regex:/[a-zA-Z]/', // must contain atleast one uppercase letter
		'regex:/[0-9]/', // must contain at least one digit
		// 'regex:/[@$!%*#?&]/' // must contain a special character.
	];

	public static $passwordRequirementText = 'Password must contains 6-16 characters in length, one lowercase, one uppercase letter, and a digit.';

	public $orderBy = [];

	public function getFilters()
	{
		$roleModel   = new \App\Models\Role();
		$userMinRole = $this->myRoleMinLevel(\Auth::user()->id);
		$roles       = $roleModel->getListing([
			'level_gte' => $userMinRole,
			'orderBy'   => 'roles__level',
		])
			->pluck('title', 'slug')
			->all();
		$status 		= \App\Helpers\Helper::makeSimpleArray($this->statuses, 'id,name');
		return [
			'reset'  => route('users.index'),
			'fields' => [
				'full_name'  => [
					'type'  => 'text',
					'label' => 'Name',
				],
				'email'      => [
					'type'  => 'text',
					'label' => 'Email',
				],
				'phone'      => [
					'type'  => 'text',
					'label' => 'Phone',
				],
				'role'  => [
					'type'    => 'select',
					'label'   => 'Role',
					'options' => $roles,
				],
				'status'     => [
					'type'       => 'select',
					'label'      => 'Status',
					'attributes' => [
						'id' => 'select-status',
					],
					'options'    => $status,
				],
				'created_at' => [
					'type'  => 'date',
					'label' => 'Created At',
				],
			],
		];
	}

	public function getFullNameAttribute()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	public function getAvatarAttribute()
	{
		return File::file($this->profile_pic, 'no-profile.jpg');
	}

	public function profile_pic()
	{
		$entityType = isset(File::$fileType['user_avatar']['type']) ? File::$fileType['user_avatar']['type'] : 0;
		return $this->hasOne('App\Models\File', 'entity_id', 'id')
			->where('entity_type', $entityType);
	}
	public function verifyUser()
	{
		return $this->hasOne('App\Models\VerifyUser');
	}

	public function roles()
	{
		// return $this->hasMany('App\Models\UserRole');
		return $this->belongsToMany('App\Models\Role', 'user_roles');
	}

	public function AauthAcessToken()
	{
		return $this->hasMany('\App\Models\OauthAccessToken');
	}
	public function company()
	{
		return $this->belongsTo('App\Models\Company', 'company_id');
	}
	public function guest_company()
	{
		return $this->hasOne('App\Models\GuestCompanyInfo', 'user_id','id');
	}

	public function getListing($srch_params = [], $offset = 0)
	{
		$select = [
			$this->table . ".*",
			"r.title AS role_title",
		];

		$listing = self::select($select)
			->when(isset($srch_params['with']), function ($q) use ($srch_params) {
				return $q->with($srch_params['with']);
			})
			->addSelect(\DB::raw("CONCAT({$this->table}.first_name, ' ', {$this->table}.last_name) AS full_name"))
			->join("user_roles AS ur", function ($join) {
				$join->on($this->table . ".id", "ur.user_id");
			})
			->join("roles AS r", function ($join) {
				$join->on("r.id", "ur.role_id");
			})
			->when(isset($srch_params['role_slug']), function ($q) use ($srch_params) {
				return $q->where('r.slug', $srch_params['role_slug']);
			})
			->when(isset($srch_params['id_gte']), function ($q) use ($srch_params) {
				return $q->where($this->table . ".id", ">=", $srch_params['id_gte']);
			})
			->when(isset($srch_params['id_greater_than']), function ($q) use ($srch_params) {
				return $q->where($this->table . ".id", ">", $srch_params['id_greater_than']);
			})
			->when(isset($srch_params['name']), function ($q) use ($srch_params) {
				return $q->whereRaw("{$this->table}.first_name LIKE '{$srch_params['name']}%'");
			})
			->when(isset($srch_params['full_name']), function ($q) use ($srch_params) {
				return $q->whereRaw("CONCAT({$this->table}.first_name, ' ', {$this->table}.last_name) LIKE '%{$srch_params['full_name']}%'");
			})
			->when(isset($srch_params['email']), function ($q) use ($srch_params) {
				return $q->whereRaw("{$this->table}.email LIKE '%{$srch_params['email']}%'");
			})
			->when(isset($srch_params['role']), function ($q) use ($srch_params) {
				return $q->where("r.slug", $srch_params['role']);
			})
			->when(isset($srch_params['role_gte']), function ($q) use ($srch_params) {
				return $q->where("r.level", ">=", $srch_params['role_gte']);
			})
			->when(isset($srch_params['created_at']), function ($q) use ($srch_params) {
				return $q->whereDate($this->table . ".created_at", $srch_params['created_at']);
			})
			->when(isset($srch_params['status']), function ($q) use ($srch_params) {
				return $q->where($this->table . ".status", $srch_params['status']);
			})
			->when(isset($srch_params['company_id']), function ($q) use ($srch_params) {
				return $q->where($this->table . ".company_id", $srch_params['company_id']);
			})
			->when(isset($srch_params['search_by']), function ($q) use ($srch_params) {
				return $q->where(function($q) use($srch_params){
					return $q->whereRaw("CONCAT({$this->table}.first_name, ' ', {$this->table}.last_name) LIKE '%{$srch_params['search_by']}%'")
							->orWhereRaw("{$this->table}.email LIKE '%{$srch_params['search_by']}%'")
							->orWhereRaw("{$this->table}.phone LIKE '%{$srch_params['search_by']}%'")
							->orWhereHas('company',function($q) use($srch_params){
								return $q->where("company_name","LIKE","%{$srch_params['search_by']}%");
							});
				});
			});

		if (isset($srch_params['username'])) {
			return $listing->where($this->table . '.username', '=', $srch_params['username'])
				->first();
		}
		if (isset($srch_params['id'])) {
			return $listing->where($this->table . '.id', '=', $srch_params['id'])
				->first();
		}

		if (isset($srch_params['count'])) {
			return $listing->get()->count();
		}

		if (isset($srch_params['orderBy'])) {
			$this->orderBy = \App\Helpers\Helper::manageOrderBy($srch_params['orderBy']);
			foreach ($this->orderBy as $key => $value) {
				$listing->orderBy($key, $value);
			}
		} else {
			$listing->orderBy('id', 'ASC');
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

		if ($listing != null && $listing->isEmpty()) {
			$listing = [];
		}

		return $listing;
	}

	public function myRoleMinLevel($user_id)
	{
		$levels = $this->myRoles([
			'user_id' => $user_id,
			'first'   => true,
		], false);

		return $levels->level;
	}

	public function myRoles($srch_params = [], $requiredRoles = true)
	{
		if (!isset($srch_params['user_id'])) {
			$srch_params['user_id'] = \Auth::user()->id;
		}

		$roles = self::select("r.*")
			->join("user_roles AS ur", function ($join) use ($srch_params) {
				$join->on("users.id", "ur.user_id");
			})
			->join("roles AS r", function ($join) use ($srch_params) {
				$join->on("r.id", "ur.role_id");
			})
			->when($srch_params['user_id'], function ($q) use ($srch_params) {
				return $q->where("users.id", $srch_params['user_id']);
			})
			->orderBy('r.level', 'ASC');

		if (isset($srch_params['first']) && $srch_params['first']) {
			return $roles->first();
		}

		$roles = $roles->get();

		if ($requiredRoles) {
			return $roles->pluck('slug')->toArray();
		}

		return $roles;
	}

	public function store($input = [], $id = 0, $request = null)
	{
		if (!empty($input['password'])) {
			$input['password'] = Hash::make($input['password']);
		} else {
			$input = array_except($input, array('password'));
		}

		$data           = null;
		$avatar         = [];
		$user_id        = \Auth::user()->id;
		$isOwnAcc       = true;
		$responseStatus = 200;

		//
		// if this is not own account, it will
		// require role.
		//
		if ($user_id != $id) {
			$isOwnAcc = false;
		}

		if (!$id) {
			$input['username'] = Helper::randomString(15);
			$data              = $this->create($input);

			$responseStatus = 201;
		} else {
			if (isset($input['email'])) {
				unset($input['email']);
			}
			$data = $this->getListing([
				'id'              => $id,
				'id_greater_than' => $user_id,
			]);

			if (!$data) {
				return \App\Helpers\Helper::resp('Not a valid data', 400);
			}

			if ($data->update($input)) {
				$data = $this->getListing([
					'id'              => $id,
					'id_greater_than' => $user_id,
				]);
			}
		}

		$this->uploadAvatar($data, $id, $request);

		if(isset($input['delete_files'])) {
			$entityType = isset(File::$fileType['user_avatar']['type']) ? File::$fileType['user_avatar']['type'] : 0;
			\App\Models\File::deleteFiles([
				'id_in' 		=> $input['delete_files'],
				'entity_type' 	=> $entityType,
				'entity_id'		=> $data->id
			], true);
		}



		//
		// if not owner changing their profile
		// then set role
		//
		if (!$isOwnAcc && isset($input['role_id']) && $input['role_id']) {
			if ($id) {
				\App\Models\UserRole::where('user_id', $id)->delete();
			}

			if (is_array($input['role_id'])) {
				$userRoles = [];
				foreach ($input['role_id'] as $roleId) {
					$userRoles[] = [
						'user_id' => $data->id,
						'role_id' => $roleId,
					];
				}

				\App\Models\UserRole::insert($userRoles);
			} else {
				\App\Models\UserRole::create([
					'user_id' => $data->id,
					'role_id' => $input['role_id'],
				]);
			}
		}

		return \App\Helpers\Helper::resp('Changes has been successfully saved.', $responseStatus, $data);
	}

	public function uploadAvatar($data = [], $id, $request)
	{
		$avatar = $data->profile_pic;
		$file   = \App\Models\File::upload($request, 'avatar', 'user_avatar', $data->id);

		// if file has successfully uploaded
		// and previous file exists, it will
		// delete old file.
		if ($file && $avatar) {
			\App\Models\File::deleteFile($avatar, true);
		}

		return \App\Helpers\Helper::resp('Changes has been successfully saved.', 200, $file);
	}

	public function remove($id = null)
	{
		$data =  DB::table('users')->where('id',$id);
		if (!$data) {
			return \App\Helpers\Helper::resp('Not a valid data', 400);
		}

		$data->delete();

		return \App\Helpers\Helper::resp('Record has been successfully deleted.', 200, $data);
	}


	public function BranchUsers()
	{
		//////eg: return $this->hasMany(Comment::class, 'foreign_key', 'local_key');
		return $this->hasMany('\App\Models\BranchUser', 'user_id');
	}
	public function BranchUser()
	{
		//////eg: return $this->hasMany(Comment::class, 'foreign_key', 'local_key');
		return $this->hasOne('\App\Models\BranchUser', 'user_id')
		//->where('companybranch_id', $companybranch_id);
		;
	}

    public static function sendPasswordChangeMail($email = null)
    {
        //dd($email);
        if ($email) {
            $send = Password::broker()->sendResetLink(['email' => $email]);
            return true;
        }
        return false;
    }

}
