<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use App\Traits\UuidManager;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Repository\Crm\CustomerRepository;

class CustomersImport implements ToCollection, WithHeadingRow, WithValidation
{  

    public function collection(Collection $rows)
    {
        $customers = [];
        $customerRepository = new CustomerRepository();

        foreach ($rows as $row) 
        {
          $customers[] = [
            'uuid' => UuidManager::generateUuid(),
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'phone_numbers' => json_encode(explode(",", $row['phone_numbers']))
          ];
        }
       
        $customerRepository->createOrUpdateCustomer($customers);

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
