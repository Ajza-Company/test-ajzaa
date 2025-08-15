<?php

namespace App\Repositories\Frontend;

class F_FetchRepository
{
    /**
     * Create a new instance.
     *
     * @param $model
     */
    public function __construct(protected $model)
    {

    }

    /**
     *
     * @param array|null $data
     * @param bool $paginate
     * @param array|null $with
     * @param array|null $withCount
     * @param bool $isLocalized
     * @param bool $latest
     * @param string|null $role
     * @return mixed
     */
    public function fetch(array $data = null, bool $paginate = true, array $with = null, array $withCount = null, bool $isLocalized = true ,bool $latest=true,string $role = null): mixed
    {
        $query = $this->model->query();

        if ($isLocalized) {
            $query->whereHas('localized')->with('localized');
        }

        if ($data) {
            $query->where($data);
        }

        if (\request()->query() && method_exists($this->model, 'scopeFilter')) {
            $query->filter(\request());
        }

        if ($with) {
            $query->with($with);
        }

        if ($latest) {
            $query = $query->latest();
        }

        if ($withCount) {
            $query->withCount($withCount);
        }

        if ($role) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        if ($paginate) {
            return $query->adaptivePaginate();
        }

        return $query->get();
    }
}
