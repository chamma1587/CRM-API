<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;

class CustomerTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['phoneNumbers'];
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
            'email'      => $model->email
        ];
    }

    public function includePhoneNumbers(Model $model)
    {
        if(!empty($model->phoneNumbers))
            return $this->collection($model->phoneNumbers, new PhoneNumbersTransformer());
    }
}
