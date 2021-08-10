<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;

class PhoneNumbersTransformer extends TransformerAbstract
{  

    public function transform(Model $model)
    {
        return [
            'uuid'         => $model->uuid,
            'phone_number' => $model->phone_number
        ];
    }
   
}
