<?php

use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;

function can($permission)
{
    $current_user = Auth::user();
    if (!$current_user) {
        return false;
    }
    $current_user_roles = array_unique($current_user->roles->pluck('id')->toArray());
    if (count($current_user_roles) <= 0) {
        return false;
    }
    $role_permission_map = array_unique(PermissionRole::whereIn('rid', $current_user_roles)->pluck('pid')->toArray());
    if (count($role_permission_map) <= 0) {
        return false;
    }
    $permissions = array_unique(Permission::whereIn('id',$role_permission_map)->pluck('p_type')->toArray());
    if (count($permissions) <= 0) {
        return false;
    }
    return in_array($permission,$permissions);
}
