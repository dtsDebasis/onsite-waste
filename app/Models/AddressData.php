<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressData extends Model
{
    use HasFactory;

    protected $table = 'address_data';

    protected $fillable = [
        'addressline1',
        'address1',
        'address2',
        'locality',
        'state',
        'postcode',
        'country',
        'latitude',
        'longitude',
    ];

    public $orderBy = [];
}
