<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchSpecialty extends Model
{
    use HasFactory;

    protected $table = 'branch_specialties';

    protected $fillable = [
        'specality_id',
        'company_branch_id',
    ];

    public $orderBy = [];

    // public function company()
    // {
    //     return $this->belongsTo('App\Models\Company', 'company_id');
    // }
    
    public function companybranch()
    {
        return $this->belongsTo('App\Models\CompanyBranch', 'company_branch_id');
    }

    public function speciality_details()
    {
         return $this->belongsTo('App\Models\Speciality', 'specality_id', 'id');
    }

}
