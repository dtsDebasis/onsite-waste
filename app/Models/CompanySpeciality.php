<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySpeciality extends Model
{
    use HasFactory;

    protected $table = 'company_speciality';

    protected $fillable = [
        'company_id',
        'specality_id',
    ];

    public $orderBy = [];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function speciality()
    {
        return $this->belongsTo('App\Models\Speciality', 'specality_id');
    }
}
