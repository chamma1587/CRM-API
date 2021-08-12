<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;

class CustomerTransformer extends TransformerAbstract
{
    /**
    * @param Model $model
    * @return array
    */

    public function transform(Model $model)
    {
        return [
            'uuid'       => $model->uuid,
            'first_name' => $model->first_name,
            'last_name'  => $model->last_name,
            'email'      => $model->email,
            'phone_numbers'  => json_decode($model->phone_numbers),
        ];
    }

}
