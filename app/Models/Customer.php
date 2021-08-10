<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    use HasFactory;


    protected $fillable = [
        
        'uuid', 'first_name', 'last_name', 'email'
    ];

    
    /**
     * customer phone numbers
     *
     * @return void
     */
    public function phoneNumbers()
    {
        return $this->hasMany('App\Models\CustomerPhoneNumber');
    }

}
