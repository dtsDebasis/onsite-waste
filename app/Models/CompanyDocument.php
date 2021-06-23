<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
    use HasFactory;

    protected $table = 'company_document';

    protected $fillable = [
        'company_id',
        'file_id',
    ];

    public $orderBy = [];
}
