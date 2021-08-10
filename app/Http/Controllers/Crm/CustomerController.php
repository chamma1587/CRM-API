<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;

use Validator;
use App\Traits\Filters;
use App\Traits\UuidManager;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Transformers\CustomerTransformer;
use Dingo\Api\Exception\ValidationHttpException;
use App\Repository\Crm\Contracts\CustomerRepositoryInterface;
use App\Repository\Common\Contracts\CommonRepositoryInterface;

class CustomerController extends Controller
{
    use Filters;
    use UuidManager;
    use Helpers;

    protected $customerRepository;
    protected $commonRepository;

    /**
     * @param customerRepositoryInterface $customerRepository
     */

    /**
     * @param CommonRepositoryInterface $commonRepository
    */
    
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CommonRepositoryInterface $commonRepository
    ) {
        $this->customerRepository   = $customerRepository;
        $this->commonRepository = $commonRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/crm/customers/{filters}",
     *      operationId="getCustomerList",
     *      tags={"Customers"},
     *      summary="Get list of customers",
     *      description="Returns list of customers",
     *      @OA\Parameter(
     *          name="filters",
     *          description="Search keyword and phone numbers",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *          example="se=chamara&phones=071111111,071457895"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Customer")
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function getCustomerList($filters)
    {
        $filter = Filters::getFilters($filters);
        $search = $filter['search'];       

        $customers = $this->customerRepository->getCustomerList($search);

        return $this->paginator($customers, new CustomerTransformer);
    }

    
    /**
    * @OA\Get(
    *      path="/api/crm/{customerId}/customer",
    *      operationId="getCustomer",
    *      tags={"Customers"},
    *      summary="Get Customer by uuid",
    *      description="Returns customer by uuid",
    *      @OA\Parameter(
    *          name="customerId",
    *          description="Customer uuid",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="string"
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/Customer")
    *       ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal server error",
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Not found",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function getCustomerInfo($customerUuid)
    {
        $customer =  $this->commonRepository->getByUuid('Customer', $customerUuid);

        if (!$customer) {
            return  $this->response->error('Customer not found', 404);
        }

        return $this->responseSuccess($customer, new CustomerTransformer);
    }

    
    /**
    * @OA\POST(
    *      path="/api/crm/customer",
    *      operationId="createCustomer",
    *      tags={"Customers"},
    *      summary="Create Customer",
    *      description="Create Customer",
    *       @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"first_name","last_name", "email", "phone_numbers"},
    *               @OA\Property(property="first_name", type="text"),
    *               @OA\Property(property="last_name", type="text"),
    *               @OA\Property(property="email", type="text"),
    *               @OA\Property(property="phone_numbers[]", type="array",
    *               @OA\Items(
    *                   type="integer"
    *                )
    *               )
    *            ),
    *        ),
    *    ),
    *
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/Customer")
    *       ),
    *       @OA\Response(
    *          response=422,
    *          description="Validation error",
    *          @OA\JsonContent(ref="#/components/schemas/Customer")
    *       ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal server error",
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Not found",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function createCustomer(Request $request)
    {
        $createCustomerPayload =  $request->all();
        
        $validationFields = [
            'first_name'    => 'required|string|max:50',
            'last_name'     => 'required|string|max:50',
            'email'         => 'required|string|email|max:50|unique:customers'
        ];

        $validator = Validator::make($createCustomerPayload, $validationFields);
        
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors());
        }

        $uuid = UuidManager::generateUuid();
        $createCustomerPayload['uuid'] = $uuid;

        $contactNumbers = $createCustomerPayload['phone_numbers'];

        $createCustomer = $this->customerRepository->createCustomer($createCustomerPayload, $contactNumbers);
        
        if (!$createCustomer) {
            return  $this->response->error('Customer not created. Please try again!', 500);
        }

        return $this->responseSuccess($createCustomer, new CustomerTransformer);
    }
    
    /**
     * @OA\PUT(
     *      path="/api/crm/{customerUuid}/customer",
     *      operationId="updateCustomer",
     *      tags={"Customers"},
     *      summary="Update Customer",
     *      description="Update Customer",
     *       @OA\Parameter(
     *          name="uuid",
     *          description="Uuid",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="first_name",
     *          description="Customer first name",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="last_name",
     *          description="Customer last name",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="email",
     *          description="Customer email",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone_numbers",
     *          description="Customer phone numbers",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="object"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Customer")
     *       ),
     *       @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(ref="#/components/schemas/Customer")
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function updateCustomer(Request $request, $customerUuid)
    {
        $customer =  $this->commonRepository->getByUuid('Customer', $customerUuid);

        if (!$customer) {
            return  $this->response->error('Customer not found', 404);
        }

        $payload =  $request->all();
        
        $validationFields = [
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'     => 'required|string|email|max:50|unique:customers,email,'.$customer->id
        ];

        $validator = Validator::make($payload, $validationFields);
        
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors());
        }

        $contactNumbers = $payload['phone_numbers'];

        $updateCustomer = $this->customerRepository->updateCustomer($payload, $contactNumbers, $customer->id);
        
        if (!$updateCustomer) {
            return  $this->response->error('Customer not updated. Please try again!', 500);
        }

        return $this->responseSuccess(null, null, 'Customers has been updated', 200);
    }

    
    /**
    * @OA\Delete(
    *      path="/api/crm/customer/{customerId}",
    *      operationId="deleteCustomer",
    *      tags={"Customers"},
    *      summary="Delete Customer by uuid",
    *      description="Delete customer by uuid",
    *      @OA\Parameter(
    *          name="customerId",
    *          description="Customer uuid",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="string"
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/Customer")
    *       ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal server error",
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Not found",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function deleteCustomer($customerUuid)
    {
        $customer =  $this->commonRepository->getByUuid('Customer', $customerUuid);

        if (!$customer) {
            return  $this->response->error('Customer not found', 404);
        }

        $deleteCustomer = $this->customerRepository->deleteCustomer($customer->id);

        if ($deleteCustomer) {
            return $this->responseSuccess(null, null, 'Customers has been deleted', 200);
        }

        return  $this->response->error('Something went wrong', 500);
    }

    
    /**
    * @OA\Post(
    *      path="/api/crm/customers/import",
    *      operationId="importCustomers",
    *      tags={"Customers"},
    *      summary="Import customers by csv file",
    *      description="Import customers by csv file",
    *      @OA\Parameter(
    *          name="file",
    *          description="Csv file for customer list",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="file"
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/Customer")
    *       ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal server error",
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Not found",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function importCustomers(Request $request)
    {
        try {
            Excel::import(new CustomersImport, request()->file('file'));
            return $this->responseSuccess(null, null, 'Customers has been Imported', 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            \Log::error($failures);
            foreach ($failures as $failure) {                
                $errors = $failure->errors();             
                return $this->error(isset($errors)? 'The row '.$failure->row() .' '. $errors[0]: 'Something went wrong!', 422);                
            }
            
        }
    }
}
