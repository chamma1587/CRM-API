<?php

namespace App\Repository\Common\Contracts;

interface CommonRepositoryInterface
{
    
    /**
     * get details By Uuid
     *
     * @param  mixed $model
     * @param  mixed $uuid
     * @return void
     */
    public function getByUuid($model, $uuid);  

}