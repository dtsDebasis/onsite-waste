<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::bannerSection();
        self::tipSection();
        self::homeSelection();
        self::knowledgeCategory();
        self::knowledgeWizard();
        self::knowledgeContent();
        self::customerManagement();
        self::guestManagement();
        self::pickupManagement();
        self::inventoryManagement();
        self::customerRequest();
        self::productManagement();
        self::employeeManagement();
        self::leadSourceMaster();
        self::specialityMaster();
        self::companyOwnerMaster();
        self::contactRoleMaster();
        self::packageNameMaster();
        self::siteContent();
        self::siteSettings();
        self::masterMenu();
        self::dashboard();
        self::customerPackageTab();
    }


    public function dashboard()
    {
        Permission::updateOrCreate([
            'p_type' => 'View Dashboard', //Never Change This
            'class' => 'Dashboard',
            'method' => 'dummy'
        ]);
    }

    public function customerPackageTab()
    {
        Permission::updateOrCreate([
            'p_type' => 'Package List', //Never Change This
            'class' => 'Package',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Package Add', //Never Change This
            'class' => 'Package',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Package Edit', //Never Change This
            'class' => 'Package',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Package Delete', //Never Change This
            'class' => 'Package',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Package Price Update', //Never Change This
            'class' => 'Package',
            'method' => 'dummy'
        ]);
    }

    public function masterMenu()
    {
        Permission::updateOrCreate([
            'p_type' => 'Manage Permission', //Never Change This
            'class' => 'Roles & Permission Management',
            'method' => 'dummy'
        ]);

        Permission::updateOrCreate([
            'p_type' => 'Manage Access Levels', //Never Change This
            'class' => 'Roles & Permission Management',
            'method' => 'dummy'
        ]);
    }
    //Site Settings
    public function siteSettings()
    {
        Permission::updateOrCreate([
            'p_type' => 'Site Settings Update', //Never Change This
            'class' => 'Site Settings',
            'method' => 'dummy'
        ]);
    }
    public function siteContent()
    {
        Permission::updateOrCreate([
            'p_type' => 'Site Content List', //Never Change This
            'class' => 'Site Content',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Site Content Search', //Never Change This
            'class' => 'Site Content',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Site Content Add', //Never Change This
            'class' => 'Site Content',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Site Content Edit', //Never Change This
            'class' => 'Site Content',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Site Content Delete', //Never Change This
            'class' => 'Site Content',
            'method' => 'dummy'
        ]);
    }

    public function packageNameMaster()
    {
        Permission::updateOrCreate([
            'p_type' => 'Package Name List', //Never Change This
            'class' => 'Package Name',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Package Name Search', //Never Change This
            'class' => 'Package Name',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Package Name Add', //Never Change This
            'class' => 'Package Name',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Package Name Edit', //Never Change This
            'class' => 'Package Name',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Package Name Delete', //Never Change This
            'class' => 'Package Name',
            'method' => 'dummy'
        ]);
    }

    public function contactRoleMaster()
    {
        Permission::updateOrCreate([
            'p_type' => 'Contact Role List', //Never Change This
            'class' => 'Contact Role',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Contact Role Search', //Never Change This
            'class' => 'Contact Role',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Contact Role Add', //Never Change This
            'class' => 'Contact Role',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Contact Role Edit', //Never Change This
            'class' => 'Contact Role',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Contact Role Delete', //Never Change This
            'class' => 'Contact Role',
            'method' => 'dummy'
        ]);
    }

    public function companyOwnerMaster()
    {
        Permission::updateOrCreate([
            'p_type' => 'Company Owner List', //Never Change This
            'class' => 'Company Owner',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Company Owner Search', //Never Change This
            'class' => 'Company Owner',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Company Owner Add', //Never Change This
            'class' => 'Company Owner',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Company Owner Edit', //Never Change This
            'class' => 'Company Owner',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Company Owner Delete', //Never Change This
            'class' => 'Company Owner',
            'method' => 'dummy'
        ]);
    }

    public function specialityMaster()
    {
        Permission::updateOrCreate([
            'p_type' => 'Speciality List', //Never Change This
            'class' => 'Speciality',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Speciality Search', //Never Change This
            'class' => 'Speciality',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Speciality Add', //Never Change This
            'class' => 'Speciality',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Speciality Edit', //Never Change This
            'class' => 'Speciality',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Speciality Delete', //Never Change This
            'class' => 'Speciality',
            'method' => 'dummy'
        ]);
    }

    public function leadSourceMaster()
    {
        Permission::updateOrCreate([
            'p_type' => 'Lead Source List', //Never Change This
            'class' => 'Lead Source',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Lead Source Search', //Never Change This
            'class' => 'Lead Source',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Lead Source Add', //Never Change This
            'class' => 'Lead Source',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Lead Source Edit', //Never Change This
            'class' => 'Lead Source',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Lead Source Delete', //Never Change This
            'class' => 'Lead Source',
            'method' => 'dummy'
        ]);
    }

    public function employeeManagement()
    {
        Permission::updateOrCreate([
            'p_type' => 'Employee List', //Never Change This
            'class' => 'Employee',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Employee Search', //Never Change This
            'class' => 'Employee',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Employee Add', //Never Change This
            'class' => 'Employee',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Employee Edit', //Never Change This
            'class' => 'Employee',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Employee Delete', //Never Change This
            'class' => 'Employee',
            'method' => 'dummy'
        ]);
    }

    public function productManagement()
    {
        Permission::updateOrCreate([
            'p_type' => 'Product Default Price Update', //Never Change This
            'class' => 'Product',
            'method' => 'dummy'
        ]);
    }

    public function customerRequest()
    {
        Permission::updateOrCreate([
            'p_type' => 'Customer Request List', //Never Change This
            'class' => 'Customer Request',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Customer Request Search', //Never Change This
            'class' => 'Customer Request',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Customer Request View', //Never Change This
            'class' => 'Customer Request',
            'method' => 'dummy'
        ]);
    }
    public function inventoryManagement()
    {
        Permission::updateOrCreate([
            'p_type' => 'Inventory List', //Never Change This
            'class' => 'Inventory',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Inventory Search', //Never Change This
            'class' => 'Inventory',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Inventory Update', //Never Change This
            'class' => 'Inventory',
            'method' => 'dummy'
        ]);
    }

    public function pickupManagement()
    {
        Permission::updateOrCreate([
            'p_type' => 'Pickup List', //Never Change This
            'class' => 'Pickup',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Pickup Search', //Never Change This
            'class' => 'Pickup',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Pickup Add', //Never Change This
            'class' => 'Pickup',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Pickup Edit', //Never Change This
            'class' => 'Pickup',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Pickup Delete', //Never Change This
            'class' => 'Pickup',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Pickup Add Manifest', //Never Change This
            'class' => 'Pickup',
            'method' => 'dummy'
        ]);
    }
    public function guestManagement()
    {
        Permission::updateOrCreate([
            'p_type' => 'Guest List', //Never Change This
            'class' => 'Guest',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Guest Search', //Never Change This
            'class' => 'Guest',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Guest Convert To Customer', //Never Change This
            'class' => 'Guest',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Request From Guest List', //Never Change This
            'class' => 'Guest',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Request From Guest Search', //Never Change This
            'class' => 'Guest',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Request From Guest Reply', //Never Change This
            'class' => 'Guest',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Request From Guest Invoice Download', //Never Change This
            'class' => 'Guest',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Request From Guest Manifest Download', //Never Change This
            'class' => 'Guest',
            'method' => 'dummy'
        ]);
    }

    public function customerManagement(Type $var = null)
    {
        Permission::updateOrCreate([
            'p_type' => 'Customer List', //Never Change This
            'class' => 'Customer',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Customer Search', //Never Change This
            'class' => 'Customer',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Customer Add', //Never Change This
            'class' => 'Customer',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Customer Import', //Never Change This
            'class' => 'Customer',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Customer Edit', //Never Change This
            'class' => 'Customer',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Customer Delete', //Never Change This
            'class' => 'Customer',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Customer Branch List', //Never Change This
            'class' => 'Customer',
            'method' => 'dummy'
        ]);
    }
    public function knowledgeContent()
    {
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Content List', //Never Change This
            'class' => 'Knowledge Content',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Content Add', //Never Change This
            'class' => 'Knowledge Content',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Content Edit', //Never Change This
            'class' => 'Knowledge Content',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Content Delete', //Never Change This
            'class' => 'Knowledge Content',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Content Search', //Never Change This
            'class' => 'Knowledge Content',
            'method' => 'dummy'
        ]);
    }

    public function knowledgeWizard()
    {
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Wizard List', //Never Change This
            'class' => 'Knowledge Wizard',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Wizard Add', //Never Change This
            'class' => 'Knowledge Wizard',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Wizard Edit', //Never Change This
            'class' => 'Knowledge Wizard',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Wizard Delete', //Never Change This
            'class' => 'Knowledge Wizard',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Wizard Search', //Never Change This
            'class' => 'Knowledge Wizard',
            'method' => 'dummy'
        ]);
    }

    public function knowledgeCategory()
    {
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Category List', //Never Change This
            'class' => 'Knowledge Category',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Category Add', //Never Change This
            'class' => 'Knowledge Category',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Category Edit', //Never Change This
            'class' => 'Knowledge Category',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Category Delete', //Never Change This
            'class' => 'Knowledge Category',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Category Update', //Never Change This
            'class' => 'Knowledge Category',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Knowledge Category Search', //Never Change This
            'class' => 'Knowledge Category',
            'method' => 'dummy'
        ]);
    }

    public function homeSelection()
    {
        Permission::updateOrCreate([
            'p_type' => 'Home Section Management', //Never Change This
            'class' => 'Home Selection',
            'method' => 'dummy'
        ]);
    }

    public function tipSection()
    {
        Permission::updateOrCreate([
            'p_type' => 'Tip Search', //Never Change This
            'class' => 'Tip',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Tip Add', //Never Change This
            'class' => 'Tip',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Tip Edit', //Never Change This
            'class' => 'Tip',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Tip Delete', //Never Change This
            'class' => 'Tip',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Tip List', //Never Change This
            'class' => 'Tip',
            'method' => 'dummy'
        ]);
    }

    public function bannerSection()
    {
        Permission::updateOrCreate([
            'p_type' => 'Banner Search', //Never Change This
            'class' => 'Banner',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Banner Add', //Never Change This
            'class' => 'Banner',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Banner Edit', //Never Change This
            'class' => 'Banner',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Banner Delete', //Never Change This
            'class' => 'Banner',
            'method' => 'dummy'
        ]);
        Permission::updateOrCreate([
            'p_type' => 'Banner List', //Never Change This
            'class' => 'Banner',
            'method' => 'dummy'
        ]);
    }
}
