<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin'], function () {
	Auth::routes(['verify' => true]);
});

Route::get('user/verify/{token}', 'App\Http\Controllers\Auth\RegisterController@verifyUser');
Route::get('admin', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('admin.login');

Route::group(['prefix' => 'admin', 'middleware' => 'permission'], function () {
	Route::get('/home', 'App\Http\Controllers\DashboardController@index')->name('admin.home');
	Route::get('logout', 'App\Http\Controllers\Auth\LoginController@adminLogout')->name('admin.logout');
	Route::get('export/users', 'App\Http\Controllers\UserController@export')->name('export.users');
	Route::post('providers/approve', 'App\Http\Controllers\ProviderController@approve')->name('providers.approve');

	Route::get('/profile', 'App\Http\Controllers\UserController@profile')->name('admin.profile');

	#customer
	Route::group(['prefix' => 'customers'], function () {
		Route::any('/', 'App\Http\Controllers\CustomerManagementController@index')->name('customers.index');
		Route::delete('destroy/{id}', 'App\Http\Controllers\CustomerManagementController@destroy')->name('customers.destroy');
		Route::post('import-data','App\Http\Controllers\CustomerManagementController@importData')->name('customers.import-data');
		Route::any('/create/{id}', 'App\Http\Controllers\CustomerManagementController@create')->name('customers.create');
		Route::any('/create/contact/{id}', 'App\Http\Controllers\CustomerManagementController@contact')->name('customers.create.contact');
		Route::post('/assign-contact-to-branch','App\Http\Controllers\CustomerManagementController@contactAssignToBranch')->name('customers.assign-contact-to-branch');
		Route::delete('contact-delete/{company_id}/{id}', 'App\Http\Controllers\CustomerManagementController@contactDelete')->name('customers.contact-delete');
		Route::any('/create/package/{id}', 'App\Http\Controllers\CustomerManagementController@package')->name('customers.create.package');
		Route::any('/create/location/{id}', 'App\Http\Controllers\CustomerManagementController@location')->name('customers.create.location');
		Route::post('/branch-assign-to-contact','App\Http\Controllers\CustomerManagementController@branchAssignToContact')->name('customers.branch-assign-to-contact');
		Route::any('/create/inventory/{id}', 'App\Http\Controllers\CustomerManagementController@inventory')->name('customers.create.inventory');

		Route::any('/create/document/{id}', 'App\Http\Controllers\CustomerManagementController@document')->name('customers.create.document');
		Route::any('/create/invoices/{id}', 'App\Http\Controllers\CustomerManagementController@invoices')->name('customers.create.invoices');
		Route::post('clone-package','App\Http\Controllers\CustomerManagementController@clonePackage')->name('cutomers.clone-package');
		Route::patch('package-update/{id}/{package_id}','App\Http\Controllers\CustomerManagementController@StoreUpdatePackage')->name('cutomers.update-package');
		Route::post('package-store/{id}','App\Http\Controllers\CustomerManagementController@StoreUpdatePackage')->name('cutomers.store-package');
		Route::delete('package/delete/{id}','App\Http\Controllers\CustomerManagementController@deletePackage')->name('customers.package-destroy');
		Route::match(['post','patch'],'branch-store-update/{company_id}','App\Http\Controllers\CustomerManagementController@branchStoreUpdate')->name('customers.create.branch-store-update');
		Route::delete('branch/delete/{company_id}/{id}','App\Http\Controllers\CustomerManagementController@branchRemove')->name('customers.branch-destroy');
		Route::get('ajax-get-branch-transaction-details','App\Http\Controllers\CustomerManagementController@branchTransactionDetails')->name('customers.branch-transaction-details');
		Route::match(['post','patch'],'ajax-update-branch-transaction-details','App\Http\Controllers\CustomerManagementController@updateBranchTransactionDetails')->name('customers.update-branch-transaction-details');
		Route::get('ajax-get-branch-package-list','App\Http\Controllers\CustomerManagementController@branchPackageList')->name('customers.branch-package-list');
		Route::post('ajax-post-branch-package-clone','App\Http\Controllers\CustomerManagementController@branchPackageClone')->name('customers.branch-package-clone');
		Route::delete('ajax-post-branch-package-removed','App\Http\Controllers\CustomerManagementController@branchPackageRemove')->name('customers.branch-package-remove');
		Route::get('ajax-get-branch-package-details', 'App\Http\Controllers\CustomerManagementController@branchPackageDetails')->name('customers.branch-package-details');
		Route::get('ajax-get-branch-assigned-contact-details', 'App\Http\Controllers\CustomerManagementController@branchAssignedContactDetails')->name('customers.branch-assigned-contact-details');
		Route::patch('ajax-update-branch-package-details','App\Http\Controllers\CustomerManagementController@branchPackageUpdate')->name('customers.branch-package-update');
		Route::get('/branches/{id}','App\Http\Controllers\CustomerManagementController@branchDetails')->name('customers.branches');
		Route::get('/branch-info-details/{company_id}/{id}','App\Http\Controllers\CustomerManagementController@branchInfoDetails')->name('customers.branche-info-details');

		Route::get('/create/hauling/{id}', 'App\Http\Controllers\CustomerManagementController@haulingList')->name('customers.create.hauling');
		Route::match(['post','patch'],'hauling-store-upadte/{company_id}','App\Http\Controllers\CustomerManagementController@haulingStoreUpdate')->name('customers.hauling-store-upadte');
		Route::post('ajax-post-inventory-reorder','App\Http\Controllers\CustomerManagementController@branchInventoryReorder')->name('customers.branch.inventory-re-order');
		Route::post('ajax-post-inventory-update','App\Http\Controllers\CustomerManagementController@branchInventoryUpdate')->name('customers.branch.inventory-update');
		Route::get('ajax-get-last-run-info','App\Http\Controllers\CustomerManagementController@branchInventoryLastRunInfo')->name('customers.branch.last-run-info');
		Route::post('ajax-post-machine-ping','App\Http\Controllers\CustomerManagementController@inventoryMachinePing')->name('customers.branch.machine-ping');
	});



	// Route::any('/packages', 'App\Http\Controllers\PackageController@index')->name('packages.index');
	// Route::any('/inventories', 'App\Http\Controllers\InventoryController@index')->name('inventories.index');
	Route::post('synchronizations/recurring-sync','App\Http\Controllers\SynchronizationController@recurringSync')->name('synchronizations.recurring-sync');
	Route::get('inventory/cycling-details','App\Http\Controllers\InventoryController@getCyclingDetails')->name('inventories.cycling-details');
	Route::post('inventory/update-cycling-details','App\Http\Controllers\InventoryController@updateCyclingDetails')->name('inventories.update-cycling-details');
	Route::get('te-500-info-details','App\Http\Controllers\BranchTe500Controller@infoDetails')->name('te-5000-informations.info-details');
	Route::post('user_update','App\Http\Controllers\UserController@user_update')->name('user_update');
	Route::resources([
		'users'       => 'App\Http\Controllers\UserController',
		'roles'       => 'App\Http\Controllers\RoleController',
		'contents'    => 'App\Http\Controllers\SiteContentController',
		'permissions' => 'App\Http\Controllers\PermissionController',
		'settings'    => 'App\Http\Controllers\SiteSettingController',
		'providers'   => 'App\Http\Controllers\ProviderController',
		'customer'   => 'App\Http\Controllers\CustomerManagementController',
		'synchronizations' => 'App\Http\Controllers\SynchronizationController',
		'inventories'      => 'App\Http\Controllers\InventoryController',
		'te-5000-informations' => 'App\Http\Controllers\BranchTe500Controller',
		'home-sections' => 'App\Http\Controllers\HomeSectionController',
		'customer-requests' => 'App\Http\Controllers\CustomerRequestController',
		'contacts' => 'App\Http\Controllers\ContactController',
	]);

	Route::group(['prefix' => 'master'], function () {
		Route::resource('leadsource', 'App\Http\Controllers\LeadSourceController', ['as' => 'master']);
		Route::resource('contact-roles', 'App\Http\Controllers\ContactRoleController', ['as' => 'master']);
		Route::resource('specialty', 'App\Http\Controllers\SpecialtyController', ['as' => 'master']);
		Route::resource('companyowners', 'App\Http\Controllers\CompanyOwnersController', ['as' => 'master']);
		Route::resource('relationships', 'App\Http\Controllers\RelationshipController', ['as' => 'master']);
		// Route::resource('tests', 'App\Http\Controllers\MasterTestController', ['as' => 'master']);
		// Route::resource('tests.options', 'App\Http\Controllers\MasterTestOptionController', ['as' => 'master']);
		// Route::resource('fixedquestions', 'App\Http\Controllers\MasterFixedQuestionsController', ['as' => 'master']);
		// Route::resource('personaltemplatetypes', 'App\Http\Controllers\PersonalTemplateController', ['as' => 'master']);


	});

	Route::group(['prefix' => 'location'], function () {
		Route::resource('countries', 'App\Http\Controllers\LocationCountryController', ['as' => 'location']);
		Route::resource('countries.states', 'App\Http\Controllers\LocationStateController', ['as' => 'location']);
		Route::resource('countries.states.cities', 'App\Http\Controllers\LocationCityController', ['as' => 'location']);
	});

	Route::group(['prefix' => 'permissions'], function () {
		Route::get('manage_role/{id}', 'App\Http\Controllers\PermissionController@manageRole')->name('permissions.manage_role');
		Route::patch('assign/{id}', "App\Http\Controllers\PermissionController@assignPermission")->name('permissions.assign');
	});

	Route::group(['prefix' => 'setting'], function () {
		Route::post('export', 'App\Http\Controllers\SiteSettingController@settingsExport')->name('settings.export');
		Route::post('import', 'App\Http\Controllers\SiteSettingController@settingsImport')->name('settings.import');
	});

	Route::group(['prefix' => 'menus'], function () {
		Route::get('/{parent_id?}', 'App\Http\Controllers\MenuController@index')->name('menus.index');
		Route::get('create/{parent_id}', 'App\Http\Controllers\MenuController@create')->name('menus.create');
		Route::get('edit/{parent_id}/{id}', 'App\Http\Controllers\MenuController@edit')->name('menus.edit');
		Route::post('store', 'App\Http\Controllers\MenuController@store')->name('menus.store');
		Route::patch('update/{id}', 'App\Http\Controllers\MenuController@update')->name('menus.update');
		Route::delete('destroy/{parent_id}/{id}', 'App\Http\Controllers\MenuController@destroy')->name('menus.destroy');
	});

	Route::group(['prefix' => 'templates'], function () {
		Route::get('index', 'App\Http\Controllers\SiteTemplateController@index')->name('templates.index');
		Route::get('create', 'App\Http\Controllers\SiteTemplateController@create')->name('templates.create');
		Route::get('edit/{id}', 'App\Http\Controllers\SiteTemplateController@edit')->name('templates.edit');
		Route::post('store', 'App\Http\Controllers\SiteTemplateController@store')->name('templates.store');
		Route::patch('update/{id}', 'App\Http\Controllers\SiteTemplateController@update')->name('templates.update');
		Route::delete('destroy/{id}', 'SApp\Http\Controllers\SiteTemplateController@destroy')->name('templates.destroy');
	});

	Route::group(['prefix' => 'knowledgecategories'], function () {
		Route::get('/{kw_category_id?}', 'App\Http\Controllers\KnowledgeCategoryController@index')->name('knowledgecategories.index');
		Route::get('create/{kw_category_id}', 'App\Http\Controllers\KnowledgeCategoryController@create')->name('knowledgecategories.create');
		Route::get('edit/{kw_category_id}/{id}', 'App\Http\Controllers\KnowledgeCategoryController@edit')->name('knowledgecategories.edit');
		Route::post('store', 'App\Http\Controllers\KnowledgeCategoryController@store')->name('knowledgecategories.store');
		Route::patch('update/{id}', 'App\Http\Controllers\KnowledgeCategoryController@update')->name('knowledgecategories.update');
		Route::delete('destroy/{kw_category_id}/{id}', 'App\Http\Controllers\KnowledgeCategoryController@destroy')->name('knowledgecategories.destroy');
	});


	Route::group(['prefix' => 'knowledgewizard'], function () {
		Route::get('index', 'App\Http\Controllers\KnowledgeWizardController@index')->name('knowledgewizard.index');
		Route::get('create', 'App\Http\Controllers\KnowledgeWizardController@create')->name('knowledgewizard.create');
		Route::get('edit/{id}', 'App\Http\Controllers\KnowledgeWizardController@edit')->name('knowledgewizard.edit');
		Route::post('store', 'App\Http\Controllers\KnowledgeWizardController@store')->name('knowledgewizard.store');
		Route::patch('update/{id}', 'App\Http\Controllers\KnowledgeWizardController@update')->name('knowledgewizard.update');
		Route::delete('destroy/{id}', 'App\Http\Controllers\KnowledgeWizardController@destroy')->name('knowledgewizard.destroy');
		Route::get('get-child-category/{id}','App\Http\Controllers\KnowledgeWizardController@getChildCategory')->name('knowledgewizard.child-category');
	});

	Route::group(['prefix' => 'knowledgecontent'], function () {
		Route::get('index', 'App\Http\Controllers\KnowledgeContentController@index')->name('knowledgecontent.index');
		Route::get('create', 'App\Http\Controllers\KnowledgeContentController@create')->name('knowledgecontent.create');
		Route::get('edit/{id}', 'App\Http\Controllers\KnowledgeContentController@edit')->name('knowledgecontent.edit');
		Route::post('store', 'App\Http\Controllers\KnowledgeContentController@store')->name('knowledgecontent.store');
		Route::patch('update/{id}', 'App\Http\Controllers\KnowledgeContentController@update')->name('knowledgecontent.update');
		Route::delete('destroy/{id}', 'App\Http\Controllers\KnowledgeContentController@destroy')->name('knowledgecontent.destroy');
		Route::get('get-child-category/{id}','App\Http\Controllers\KnowledgeContentController@getChildCategory')->name('knowledgecontent.child-category');
	});

	Route::group(['prefix' => 'tips'], function () {
		Route::get('index', 'App\Http\Controllers\TipsController@index')->name('tips.index');
		Route::get('create', 'App\Http\Controllers\TipsController@create')->name('tips.create');
		Route::get('edit/{id}', 'App\Http\Controllers\TipsController@edit')->name('tips.edit');
		Route::post('store', 'App\Http\Controllers\TipsController@store')->name('tips.store');
		Route::patch('update/{id}', 'App\Http\Controllers\TipsController@update')->name('tips.update');
		Route::delete('destroy/{id}', 'App\Http\Controllers\TipsController@destroy')->name('tips.destroy');
	});

	Route::group(['prefix' => 'banners'], function () {
		Route::get('index', 'App\Http\Controllers\BannersController@index')->name('banners.index');
		Route::get('create', 'App\Http\Controllers\BannersController@create')->name('banners.create');
		Route::get('edit/{id}', 'App\Http\Controllers\BannersController@edit')->name('banners.edit');
		Route::post('store', 'App\Http\Controllers\BannersController@store')->name('banners.store');
		Route::patch('update/{id}', 'App\Http\Controllers\BannersController@update')->name('banners.update');
		Route::delete('destroy/{id}', 'App\Http\Controllers\BannersController@destroy')->name('banners.destroy');
	});

	#packages
	Route::any('package/transactional', 'App\Http\Controllers\PackageController@transactionaPackage')->name('packages.transaction-package');
	Route::resource('packages','App\Http\Controllers\PackageController');
	Route::group(['prefix' => 'package'], function () {
		Route::any('index', 'App\Http\Controllers\PackageController@index')->name('package.index');

		Route::any('create', 'App\Http\Controllers\PackageController@create')->name('package.create');

		Route::any('editpackage/{id}', 'App\Http\Controllers\PackageController@editpackage')->name('package.editpackage');
		Route::any('edittransactionalpackages/{id}', 'App\Http\Controllers\PackageController@edittransactionalpackages')->name('package.edittransactionalpackages');


	});

	Route::group(['prefix' => 'inventory'], function () {
		Route::get('index', 'App\Http\Controllers\InventoryController@index')->name('inventory.index');
	});

	#pickup
	Route::group(['prefix' => 'pickup'], function () {
		Route::get('index', 'App\Http\Controllers\PickupController@index')->name('pickup.index');
		Route::any('create/{id}', 'App\Http\Controllers\PickupController@create')->name('pickup.create');
	});
	Route::get('pickups/completed-hauling-excel','App\Http\Controllers\HaulingController@generateExcel')->name('pickups.generate-excel');
	Route::get('pickups/ajax-get-manifest-details','App\Http\Controllers\HaulingController@manifestDetails')->name('pickups.manifest-details');
	Route::match(['post','patch'],'pickups/ajax-update-manifest-details','App\Http\Controllers\HaulingController@manifestUpdateDetails')->name('pickups.manifest-update-details');
	Route::post('pickups/status-update','App\Http\Controllers\HaulingController@updateStatus')->name('pickups.status-update');
	Route::resource('pickups','App\Http\Controllers\HaulingController');
	Route::get('guest/request-file-download/{type}/{id}','App\Http\Controllers\GuestController@downloadRequestFile')->name('guests.request-file-download');
	Route::resource('guests','App\Http\Controllers\GuestController');
	Route::post('request-info-reply','App\Http\Controllers\GuestController@requestInfoReply')->name('request-info.reply');

	Route::group(['prefix' => 'training'], function () {
		Route::get('index', 'App\Http\Controllers\TrainingController@index')->name('training.index');
		Route::get('create', 'App\Http\Controllers\TrainingController@create')->name('training.create');
	});

	Route::group(['prefix' => 'support'], function () {
		Route::get('index', 'App\Http\Controllers\SupportController@index')->name('support.index');
	});

	Route::group(['prefix' => 'sales'], function () {
		Route::get('index', 'App\Http\Controllers\SalesController@index')->name('sales.index');
	});

	Route::group(['prefix' => 'ui'], function () {
		Route::get('icons', 'App\Http\Controllers\SiteSettingController@uiIcons')->name('ui.icons');
	});

	// Ajax URLs
	Route::get('ajax-get-add-contact-person-template','App\Http\Controllers\CustomerManagementController@getAjaxContactPersonTemplate');
	Route::get('ajax-get-add-contact-person-details','App\Http\Controllers\CustomerManagementController@getAjaxContactPersonDetails');
	Route::get('ajax-get-branch-list','App\Http\Controllers\CustomerManagementController@getAjaxBranchList');

});

Auth::routes(['verify' => true]);
Route::get('/', 'App\Http\Controllers\HomeController@home')->name('home');
Route::group(['middleware' => 'auth'], function () {
	Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
});
