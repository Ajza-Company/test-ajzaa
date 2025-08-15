<?php

namespace App\Repositories\Frontend\Store\Fetch;

interface F_FetchStoreInterface
{
    /**
     * Create new resource
     *
     * @return mixed
     */
    public function fetch(): mixed;
}
