<?php

namespace App\Repository\Crm\Contracts;

interface CustomerRepositoryInterface
{
    /**
     * get Customer List
     *
     * @param  mixed $keyword
     * @return void
     */
    public function getCustomerList($keyword);
    
    /**
     * get Customer By Id
     *
     * @param  mixed $customerId
     * @return void
     */
    public function getCustomerById($customerId);
    
    /**
     * Create Customer
     *
     * @param  mixed $payload
     * @param  array $contactNumbers
     * @return void
     */
    public function createCustomer($payload, $contactNumbers);
    
    /**
     * Update Customer
     *
     * @param  mixed $payload
     * @param  array $contactNumbers
     * @param  mixed $customerId
     * @return void
     */
    public function updateCustomer($payload, $contactNumbers, $customerId);
    
    /**
     * Delete Customer
     *
     * @param  mixed $customerId
     * @return void
     */
    public function deleteCustomer($customerId);

    
    /**
     * getCustomerByEmail
     *
     * @param  mixed $email
     * @return void
     */
    public function getCustomerByEmail($email);
}
