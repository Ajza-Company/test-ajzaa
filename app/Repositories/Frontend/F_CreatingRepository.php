<?php

namespace App\Repositories\Frontend;

class F_CreatingRepository
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
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }
}
