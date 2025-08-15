<?php

namespace App\Repositories\Frontend\Company\Find;

use App\Models\Company;

class F_FindCompanyRepository implements F_FindCompanyInterface
{
    /**
     * Create a new instance.
     *
     * @param Company $model
     */
    public function __construct(private Company $model)
    {
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): mixed
    {
        return $this->model->with(['user','stores' => function ($q) {
            $q->whereHas('localized');
        }])->findOrFail($id);
    }
}
