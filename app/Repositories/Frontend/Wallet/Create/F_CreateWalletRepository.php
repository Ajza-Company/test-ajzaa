<?php

namespace App\Repositories\Frontend\Wallet\Create;

use App\Models\Wallet;
use App\Repositories\Frontend\F_FirstOrCreateRepository;

class F_CreateWalletRepository extends F_FirstOrCreateRepository implements F_CreateWalletInterface
{
    /**
     * Create a new instance.
     *
     * @param Wallet $model
     */
    public function __construct(Wallet $model)
    {
        parent::__construct($model);
    }
}
