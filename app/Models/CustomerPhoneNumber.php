<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = [

        'uuid', 'phone_number', 'contact_id'
    ];
}
