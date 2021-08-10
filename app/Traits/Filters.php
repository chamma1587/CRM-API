<?php

namespace App\Traits;

trait Filters
{    
    /**
     * getFilters
     *
     * @param  mixed $filters
     * @return void
     */
    public static function getFilters($filters)
    {
        $result = [
            'search' => null,         
            ] ;

        $filters = explode('&', $filters);

        foreach ($filters as $filterElement) {
            $filter = explode('=', $filterElement);

            if ($filter[0] == 'se') {
                $result['search']  = $filter[1];
            }           
        }

        return $result;
    }
}
