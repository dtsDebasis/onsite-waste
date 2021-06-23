<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
	public $timestamps = false;
    protected $table='user_roles';
	protected $fillable = [
        'user_id', 
        'role_id', 
    ];
	
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function role()
    {
        //////eg: return $this->hasOne(Phone::class, 'foreign_key', 'local_key');
        return $this->hasOne('App\Models\Role', 'role_id', 'id');
    }
}
