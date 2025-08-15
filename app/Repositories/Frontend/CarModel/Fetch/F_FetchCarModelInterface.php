<?php

namespace App\Repositories\Frontend\CarModel\Fetch;

interface F_FetchCarModelInterface
{
    /**
     * Create new resource
     *
     * @param array $data
     * @return mixed
     */
    public function fetch(array $data): mixed;
}
