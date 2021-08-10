<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Class UuidManager
 * @package App\Traits
 *
 *  manage uuid tokens
 */
trait UuidManager
{

    public static function generateUuid()
    {
        return Str::uuid();
    }   
   

}