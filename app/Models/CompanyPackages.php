<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPackages extends Model
{
    use HasFactory;

    protected $table = 'company_packages';

    protected $fillable = [
        'package_id',
        'company_id',
        'branch_id',
        'cust_haul',
        'cust_box',
        'cust_price',
    ];

    public $orderBy = [];


    public function package()
    {
        return $this->belongsTo('App\Models\Packages', 'package_id');
    }
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
    public function companybranch()
    {
        return $this->belongsTo('App\Models\CompanyBranch', 'branch_id');
    }

}
