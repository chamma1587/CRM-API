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
        $customers = Customer::select('id', 'uuid', 'first_name', 'last_name', 'email')
                    ->when($keyword, function ($query, $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('first_name', 'like', "%$search%")
                                ->orWhere('last_name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhereHas('phoneNumbers', function ($withQuery) use ($search) {
                                    $withQuery->where('phone_number', (int)$search);
                                });
                        });
                    });
     
        return $customers->paginate(10);
    }

       
    /**
     * getCustomerById
     *
     * @param  mixed $customerId
     * @return void
     */
    public function getCustomerById($customerId)
    {
        return  Customer::where('id', $customerId)->first();
    }

        
    /**
     * createCustomer
     *
     * @param  mixed $payload
     * @return void
     */
    public function createCustomer($payload, $phoneNumbers)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::create($payload);

            if ($customer) {
                if (count($phoneNumbers) > 0) {
                    $contactNumbers = $this->customerPhoneNumbersProcess($phoneNumbers);
                    $customer->phoneNumbers()->saveMany($contactNumbers);
                }
            }

            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e);
            return false;
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
    public function updateCustomer($payload, $contactNumbers, $customerId)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::where('id', $customerId)->first();

            $customer->first_name = $payload['first_name'];
            $customer->last_name  = $payload['last_name'];
            $customer->email      = $payload['email'];
            $customer->save();

            $customer->phoneNumbers()->delete();

            if (count($contactNumbers) > 0) {
                $contactNumbers = $this->customerPhoneNumbersProcess($contactNumbers);
                $customer->phoneNumbers()->saveMany($contactNumbers);
            }

            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e);
            return false;
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
        DB::beginTransaction();

        try {
            $customer = Customer::where('id', $customerId)->first();
            if ($customer) {
                $customer->phoneNumbers()->delete();
                $customer->delete();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e);
            return false;
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
     * getCustomerByEmail
     *
     * @param  mixed $email
     * @return void
     */
    public function getCustomerByEmail($email)
    {
        return  Customer::select('id')
                        ->where('email', $email)
                        ->first();
    }
}
