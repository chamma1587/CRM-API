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
     * @return void
     */
    public function createCustomer($payload);
    
    /**
     * Update Customer
     *
     * @param  mixed $payload
     * @param  mixed $customerId
     * @return void
     */
    public function updateCustomer($payload, $customerId);
    
    /**
     * Delete Customer
     *
     * @param  mixed $customerId
     * @return void
     */
    public function deleteCustomer($customerId);

    
    /**
     * createOrUpdateCustomer
     *
     * @param  mixed $payload
     * @return void
     */
    public function createOrUpdateCustomer($payload);

}
