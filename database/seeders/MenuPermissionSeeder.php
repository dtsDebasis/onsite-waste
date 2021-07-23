<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use Illuminate\Database\Seeder;

class MenuPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::updateMenuPermission('Banner Management','Banner List');
        self::updateMenuPermission('Tips Management','Tip List');
        self::updateMenuPermission('Home section Management','Home Section Management');

        self::updateMenuPermission('Knowledge Categories','Knowledge Category List');
        self::updateMenuPermission('Knowledge Wizard','Knowledge Wizard List');
        self::updateMenuPermission('Knowledge Content','Knowledge Content List');

        self::updateMenuPermission('Customer Management','Customer List');
        self::updateMenuPermission('Guest Management','Guest List,Request From Guest List');
        self::updateMenuPermission('PickUp Management','Pickup List');
        self::updateMenuPermission('Inventory Management','Inventory List');
        self::updateMenuPermission('Customer Requests','Customer Request List');
        self::updateMenuPermission('Product Management','Package Price Update');
        self::updateMenuPermission('Employee Management','Employee List');

        self::updateMenuPermission('Lead Source','Lead Source List');
        self::updateMenuPermission('Specialty','Speciality List');
        self::updateMenuPermission('Company Owners','Company Owner List');
        self::updateMenuPermission('Contact Role','Contact Role List');
        self::updateMenuPermission('Package Names','Package Name List');

        self::updateMenuPermission('Permissions','Manage Permission');
        self::updateMenuPermission('Access Levels','Manage Access Levels');

        self::updateMenuPermission('Site Setting','Site Settings Update');
        self::updateMenuPermission('Site Contents','Site Content List');
    }

    public function updateMenuPermission($menuName,$permission)
    {
        $menus = AdminMenu::where('menu',$menuName)->get();
        foreach ($menus as $key => $menu) {
            if ($menu) {
                $menu->permission = $permission;
                $menu->save();
            }
        }
    }
}
