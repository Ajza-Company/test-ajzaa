<?php

namespace App\Repositories\Admin\State\Fetch;

interface A_FetchStateInterface
{
    /**
     * Create new resource
     *
     * @return mixed
     */
    public function fetch(): mixed;
}
