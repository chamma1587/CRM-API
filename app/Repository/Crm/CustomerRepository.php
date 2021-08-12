<?php

namespace App\Repository\Crm;

use DB;
use App\Models\Customer;
use App\Traits\UuidManager;
use App\Models\CustomerPhoneNumber;
use App\Repository\Crm\Contracts\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{

    /**
     * getCustomerList
     *
     * @param  mixed $keyword

     * @return void
     */
    public function getCustomerList($keyword)
    {
        try {
            $customers = Customer::select('id', 'uuid', 'first_name', 'last_name', 'email', 'phone_numbers')
                    ->when($keyword, function ($query, $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('first_name', 'like', "%$search%")
                                ->orWhere('last_name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhereJsonContains('phone_numbers', ["$search"] );
                        });
                    });
            return $customers->paginate(10);
        } catch (\Throwable $th) {           
            DB::rollback();
            \Log::error($th);
            abort(500, 'Something went wrong please contact administrator');
        }
    }

       
    /**
     * getCustomerById
     *
     * @param  mixed $customerId
     * @return void
     */
    public function getCustomerById($customerId)
    {
        try {
            return  Customer::select('id', 'uuid', 'first_name', 'last_name', 'email')
                        ->where('id', $customerId)->first();
        } catch (\Throwable $th) {
            DB::rollback();
            \Log::error($th);
            abort(500, 'Something went wrong please try agin later');
        }
    }

        
    /**
     * createCustomer
     *
     * @param  mixed $payload
     * @return void
     */
    public function createCustomer($payload)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::create($payload);
            DB::commit();

            return $customer;
        } catch (\Throwable $th) {
            DB::rollback();
            \Log::error($th);
            abort(500, 'Could not create customer please try again later');
        }
    }

      
    /**
     * updateCustomer
     *
     * @param  mixed $payload
     * @param  mixed $contactNumbers
     * @param  mixed $customerId
     * @return void
     */
    public function updateCustomer($payload,$customerId)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::where('id', $customerId)->first();
            if ($customer) {
                $customer->update($payload);
            }

            DB::commit();
            return $customer;
        } catch (\Throwable $th) {
            DB::rollback();
            \Log::error($th);
            abort(500, 'Could not update customer please try again later');
        }
    }

    
    /**
     * deleteCustomer
     *
     * @param  mixed $customerId
     * @return void
     */
    public function deleteCustomer($customerId)
    {
        try {
            $customer = Customer::where('id', $customerId)
                                ->delete();
            return true;
        } catch (\Throwable $th) {          
            \Log::error($th);
            abort(500, 'Could not delete customer please try again later');
        }
    }

    
    /**
     * Customer Phone Numbers Process
     *
     * @param  mixed $numbers
     * @return void
     */
    private function customerPhoneNumbersProcess($numbers)
    {
        foreach ($numbers as $value) {
            $contactNumber = new CustomerPhoneNumber();
            $contactNumber->phone_number = $value;
            $contactNumber->uuid = UuidManager::generateUuid();
            $contactNumbers[] = $contactNumber;
        }

        return $contactNumbers;
    }

    
    /**
     * createOrUpdateCustomer
     *
     * @param  mixed $payload
     * @return void
     */
    public function createOrUpdateCustomer($payload)
    {
        DB::beginTransaction();

        try {           
            Customer::upsert($payload,'email');           
            DB::commit();
          
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            \Log::error($th);
            abort(500, 'Could not insert / update customer please try again later');
        }
    }
}
