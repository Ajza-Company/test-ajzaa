<?php

namespace App\Repositories\Frontend\Wallet\Create;

interface F_CreateWalletInterface
{
    /**
     *
     * @param array $search
     * @param array $data
     * @return mixed
     */
    public function create(array $search, array $data): mixed;
}
