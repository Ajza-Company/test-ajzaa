<?php

namespace App\Repositories\Frontend;

class F_FirstOrCreateRepository
{
    protected $model;

    /**
     * Create a new instance.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * create new resource
     *
     * @param array $search
     * @param array $data
     * @return mixed
     */
    public function create(array $search, array $data): mixed
    {
        return $this->model->firstOrCreate($search, $data);
    }
}
