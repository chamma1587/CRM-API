<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelEventThrower;

/**
 * @OA\Schema(
 *     title="Customer",
 *     description="Customer model",
 *     @OA\Xml(
 *         name="Customer"
 *     )
 * )
 */
class Customer extends Model
{
    use HasFactory, ModelEventThrower;
   

    protected $fillable = [
        
        'uuid', 'first_name', 'last_name', 'email', 'phone_numbers'
    ];   
   

}
