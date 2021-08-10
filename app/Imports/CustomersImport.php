<?php

namespace App\Imports;

use App\Traits\UuidManager;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Repository\Crm\CustomerRepository;

class CustomersImport implements OnEachRow, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
        $row   = $row->toArray();       
        
        $customerRepository = new CustomerRepository();

        $customer = $customerRepository->getCustomerByEmail($row['email']);
        
        if (!$customer) { 
            //create new customer     
            $payload['uuid'] = UuidManager::generateUuid();    
            $payload['first_name'] = $row['first_name'];    
            $payload['last_name'] = $row['last_name'];   
            $payload['email'] = $row['email'];   
            $contactNumbers = explode(",",$row['phone_numbers']);    

            $customerRepository->createCustomer($payload, $contactNumbers);
           
        }else{
            //update customer details
            $payload['first_name'] = $row['first_name'];    
            $payload['last_name'] = $row['last_name'];   
            $payload['email'] = $row['email'];   
            $contactNumbers =  explode(",",$row['phone_numbers']); 

            $customerRepository->updateCustomer($payload, $contactNumbers, $customer->id);
        }
    }


    public function rules(): array
    {
        return [           
            'first_name' => 'required',            
            'last_name' => 'required',
            'email' => 'required',
            'phone_numbers' => 'required',           
        ];
    }
    
}
