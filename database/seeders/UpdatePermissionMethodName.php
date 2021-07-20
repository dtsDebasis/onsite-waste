<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class UpdatePermissionMethodName extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //self::updateMethod('View Dashboard','admin.home');
        self::updateMethod('Banner List','banners.index');
        self::updateMethod('Tip List','tips.index');
        self::updateMethod('Home Selection Update','home-sections.index');
        self::updateMethod('Knowledge Category List','knowledgecategories.index');
        self::updateMethod('Knowledge Content List','knowledgecontent.index');
        self::updateMethod('Knowledge Wizard List','knowledgewizard.index');
        self::updateMethod('Customer List','customers.index');
        self::updateMethod('Guest List','guests.index');
        self::updateMethod('Pickup List','pickups.index');
        self::updateMethod('Customer Request List','customer-requests.index');
        self::updateMethod('Employee List','users.index');
        self::updateMethod('Manage Permission','roles.index');
        self::updateMethod('Manage Access Levels','permissions.index');
        self::updateMethod('Lead Source List','master.leadsource.index');
        self::updateMethod('Speciality List','master.specialty.index');
        self::updateMethod('Company Owner List','master.companyowners.index');
        self::updateMethod('Contact Role List','master.contact-roles.index');
        self::updateMethod('Package Name List','master.packagename.index');
        self::updateMethod('Site Settings Update','settings.edit');
        self::updateMethod('Site Content List','contents.index');
    }

    public function updateMethod($permission,$methodName)
    {
        $getPermission = Permission::where('p_type',$permission)->first();
        if ($getPermission && $methodName) {
            $getPermission->method = $methodName;
            $getPermission->save();
        }
    }
}
