<?php

namespace App\Repositories\Frontend\Country\Fetch;

interface F_FetchCountryInterface
{
    /**
     * Fetch countries
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetch();
}