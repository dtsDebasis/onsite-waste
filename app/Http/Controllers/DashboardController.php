<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller {

	public function index() {
		$data                     = [];
		$data['breadcrumb']       = [
			'#' => 'Dashboard',
		];	
		$data['customers'] = app('App\Models\Company')->getListing(['count'=>1]);
		$data['locations'] = app('App\Models\CompanyBranch')->getListing(['count'=>1]);
		$data['active_locations'] = app('App\Models\CompanyBranch')->getListing(['count'=>1,'status' => 1]);
		$data['booked_locations'] = app('App\Models\CompanyBranch')->getListing(['count'=>1,'status' => 0]);
		return view('admin.index', $data);
	}
}
