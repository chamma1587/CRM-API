<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repository\Common\CommonRepository;
use App\Repository\Crm\CustomerRepository;
use App\Repository\Common\Contracts\CommonRepositoryInterface;
use App\Repository\Crm\Contracts\CustomerRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CommonRepositoryInterface::class, CommonRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }
}


?>