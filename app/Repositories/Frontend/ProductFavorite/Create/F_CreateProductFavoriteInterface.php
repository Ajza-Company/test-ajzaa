<?php

namespace App\Repositories\Frontend\ProductFavorite\Create;

interface F_CreateProductFavoriteInterface
{
    /**
     * Create new resource
     *
     * @param array $search
     * @param array $data
     * @return mixed
     */
    public function create(array $search, array $data): mixed;
}
