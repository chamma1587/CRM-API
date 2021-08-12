<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

 /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="CRM API Documentation",
     *      description="CRM API Documentation",
     *      @OA\Contact(
     *          email="ct.ranatunga@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="CRM API Server"
     * )

     *
     * @OA\Tag(
     *     name="CRM",
     *     description="API Endpoints of CRM"
     * )
     * @OA\PathItem(
     *   path="/api"
     * )
     */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
    * Success response
    *
    * @param mixed $modely
    * @param Transformer $transformer
    * @param string $message
    * @param $string $code
    *
    * @return  $mixed
    */
    public function responseSuccess($model, $transformer, $message = 'Success', $code = 200)
    {
        $meta = [
            'message'=> $message,
            'status_code' => $code,
        ];
        if (!$model) {
            return $this->response->array($meta);
        }
            
        return $this->response->item($model, $transformer)->setMeta($meta);
    }
     


    public function errorResponse($message = 'Error', $code = 500)
    {
        $meta = [
            'message'=> $message,
            'status_code' => $code,
        ];

        return $this->response->array($meta);
    }
}
