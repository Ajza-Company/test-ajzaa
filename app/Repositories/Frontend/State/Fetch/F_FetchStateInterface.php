<?php

namespace App\Repositories\Frontend\State\Fetch;

interface F_FetchStateInterface
{
    /**
     * Create new resource
     *
     * @return mixed
     */
    public function fetch(): mixed;
}
