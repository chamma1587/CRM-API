<?php
namespace App\Repository\Common;

use DB;
use App\Repository\Common\Contracts\CommonRepositoryInterface;

class CommonRepository implements CommonRepositoryInterface
{
    
    /**
     * get Details By Uuid
     *
     * @param  String $model
     * @param  String $uuid
     * @return void
     */
    public function getByUuid($model, $uuid){    

        $modelName = 'App\\Models\\'.$model; 
        return  $modelName::where('uuid', $uuid)->first();                
    } 

   

}