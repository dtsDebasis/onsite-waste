<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class GroupLocations extends Model
{
    use HasFactory;

    protected $table = 'group_locations_map';

    protected $fillable = [
        'location_id',
        'group_id',
        'category_id',
    ];

    protected $hidden = [
    	'updated_at',
    ];
}