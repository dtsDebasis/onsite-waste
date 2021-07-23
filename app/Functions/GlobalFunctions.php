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
    $permissionArray = explode(',',$permission);
    if (count($permissionArray) == 1) {
        return in_array($permission,$permissions);
    } elseif (count($permissionArray) > 1) {
        foreach ($permissionArray as $key => $value) {
            if (in_array($value,$permissions)) {
                return true;
            }
        }
        return false;
    } else {
        return false;
    }
}

function checkRouteAccess($request)
{
    $current_user = Auth::user();
    if (!$current_user) {
        return false;
    }
    $current_route_name = $request->route()->getName();
    if ($current_route_name) {
        $permission = Permission::where('method',$current_route_name)->first();
        if ($permission) {
            $permission_name = $permission->p_type;
            if(can($permission_name)){
                return true;
            }
            return abort('403');
        }else {
            return true;
            //dd($current_route_name);
        }
    }else {
        return true; //May need to change to false
    }
}
