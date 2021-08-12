<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

/**
 * Class ModelEventThrower
 * @package App\Traits
 *
 *  Automatically throw Add, Update, Delete events of Model.
 */
trait ModelEventThrower {

    protected static function boot() {
        parent::boot();

        static::saving(function($model){           
            if($model->uuid == null){
                $model->uuid = (string) Str::uuid();
            }  
        });
       
    }

}