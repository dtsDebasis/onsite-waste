<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCorporateContacts extends Model
{
    use HasFactory;

    protected $table = 'company_corporate_contacts';

    protected $fillable = [
        'company_id',
        'user_id',
        'is_owner',
    ];


    public function company(){
        return  $this->belongsTo('App\Models\Company', 'company_id');
    }
    
    public function user(){
        return  $this->belongsTo('App\Models\User', 'user_id');
    }


}
