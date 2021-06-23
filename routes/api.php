<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1/', 'middleware' => ['api']], function () {
	Route::post('login/', 'App\Http\Controllers\Auth\Api\LoginController@login');
	Route::post('register', 'App\Http\Controllers\Auth\Api\RegisterApiController@register');
	Route::post('forgot/password', 'App\Http\Controllers\Auth\Api\LoginController@forgotPassword');
	Route::post('set/password', 'App\Http\Controllers\Auth\Api\LoginController@setPassword');
	Route::post('verify/email', 'App\Http\Controllers\Auth\Api\LoginController@verifyEmail');
	Route::get('captcha', 'App\Http\Controllers\Api\InitController@captcha');
	Route::post('store-last-cycling-information', 'App\Http\Controllers\Api\ExternalInfoApiController@storeLastCyclingInfo');
	Route::post('update-te-5000-information', 'App\Http\Controllers\Api\ExternalInfoApiController@updateTE500Information');
	Route::post('contact-request','App\Http\Controllers\Api\ExternalInfoApiController@createContact');
});

Route::group(['prefix' => 'v1/', 'middleware' => ['auth:api']], function () {
	Route::post('auth/logout', 'App\Http\Controllers\Auth\Api\LoginController@logout');
	Route::get('init', 'App\Http\Controllers\Api\InitController@initialDetails');
	

	Route::group(['prefix' => 'users'], function () {
		Route::post('avatar', 'App\Http\Controllers\Api\UserController@uploadAvatar');
		Route::post('update-password', 'App\Http\Controllers\Api\UserController@updatePassword');	
		Route::get('profile-details', 'App\Http\Controllers\Api\UserController@profileDetails');
		Route::post('profile-update', 'App\Http\Controllers\Api\UserController@profileUpdate');	
	});

	Route::group(['prefix' => 'knowledgecenter'], function () {
		Route::get('getcategory', 'App\Http\Controllers\Api\KnowledgeCenterController@getCategory');
		Route::get('getknowledgewizard', 'App\Http\Controllers\Api\KnowledgeCenterController@getKnowledgeWizard');
		Route::get('getKnowledgeContent', 'App\Http\Controllers\Api\KnowledgeCenterController@getKnowledgeContent');		
		Route::post('addUserPreference', 'App\Http\Controllers\Api\KnowledgeCenterController@addUserPreference');
		Route::get('getUserPreference', 'App\Http\Controllers\Api\KnowledgeCenterController@getUserPreference');
		Route::get('getTagList', 'App\Http\Controllers\Api\KnowledgeCenterController@getTagList');
		Route::get('content-by-wizard-category','App\Http\Controllers\Api\KnowledgeCenterController@getContentByWizardCat');
		Route::get('content-with-category','App\Http\Controllers\Api\KnowledgeCenterController@getContentWithCat');
	});

	Route::group(['prefix' => 'tips'], function () {
		Route::get('getTips', 'App\Http\Controllers\Api\TipsController@getTips');
	});

	Route::group(['prefix' => 'banners'], function () {
		Route::get('getBanners', 'App\Http\Controllers\Api\BannersController@getBanners');
	});
	
	Route::post('send-request-info','App\Http\Controllers\Api\GuestRequestApiController@postRequest');
	
	Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
		Route::get('manifest-list','PickupApiController@manifestList');
		Route::get('manifest-list-export','PickupApiController@manifestListExport');
		Route::get('hauling-list','PickupApiController@haulingList');
		Route::get('branch-list','PickupApiController@branchList');
		Route::post('inventory-manifest-request-sent','PickupApiController@customerRequestStore');
		
		Route::get('inventory-list','PickupApiController@inventoryList');
		Route::get('invoice-list','PickupApiController@invoiceList');
		Route::get('invoice-list-export','PickupApiController@getInvoiceExcelLink');
		Route::get('package-lists','PickupApiController@packageLists');
		Route::get('transaction-package-list','PickupApiController@getTransactionPackageDetails');
		
		Route::get('contact-information','ExternalInfoApiController@contactDetails');
	});
	

});

