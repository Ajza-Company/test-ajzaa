<?php

namespace App\Providers;

use App\Repositories\Frontend\Address\Create\F_CreateAddressInterface;
use App\Repositories\Frontend\Address\Create\F_CreateAddressRepository;
use App\Repositories\Frontend\AjzaOffer\Fetch\F_FetchAjzaOfferInterface;
use App\Repositories\Frontend\AjzaOffer\Fetch\F_FetchAjzaOfferRepository;
use App\Repositories\Frontend\Area\Fetch\F_FetchAreaInterface;
use App\Repositories\Frontend\Area\Fetch\F_FetchAreaRepository;
use App\Repositories\Frontend\CarBrand\Fetch\F_FetchCarBrandInterface;
use App\Repositories\Frontend\CarBrand\Fetch\F_FetchCarBrandRepository;
use App\Repositories\Frontend\CarModel\Fetch\F_FetchCarModelInterface;
use App\Repositories\Frontend\CarModel\Fetch\F_FetchCarModelRepository;
use App\Repositories\Frontend\CarType\Fetch\F_FetchCarTypeInterface;
use App\Repositories\Frontend\CarType\Fetch\F_FetchCarTypeRepository;
use App\Repositories\Frontend\Category\Fetch\F_FetchCategoryInterface;
use App\Repositories\Frontend\Category\Fetch\F_FetchCategoryRepository;
use App\Repositories\Frontend\Order\Create\F_CreateOrderInterface;
use App\Repositories\Frontend\Order\Create\F_CreateOrderRepository;
use App\Repositories\Frontend\Order\Find\F_FindOrderInterface;
use App\Repositories\Frontend\Order\Find\F_FindOrderRepository;
use App\Repositories\Frontend\OrderProduct\Insert\F_InsertOrderProductInterface;
use App\Repositories\Frontend\OrderProduct\Insert\F_InsertOrderProductRepository;
use App\Repositories\Frontend\OtpCode\Create\F_CreateOtpCodeInterface;
use App\Repositories\Frontend\OtpCode\Create\F_CreateOtpCodeRepository;
use App\Repositories\Frontend\Product\Find\F_FindProductInterface;
use App\Repositories\Frontend\Product\Find\F_FindProductRepository;
use App\Repositories\Frontend\Product\Fetch\F_FetchProductRepository;
use App\Repositories\Frontend\Product\Fetch\F_FetchProductInterface;
use App\Repositories\Frontend\ProductFavorite\Create\F_CreateProductFavoriteInterface;
use App\Repositories\Frontend\ProductFavorite\Create\F_CreateProductFavoriteRepository;
use App\Repositories\Frontend\RepOrder\Create\F_CreateRepOrderInterface;
use App\Repositories\Frontend\RepOrder\Create\F_CreateRepOrderRepository;
use App\Repositories\Frontend\SliderImage\Fetch\F_FetchSliderImageInterface;
use App\Repositories\Frontend\SliderImage\Fetch\F_FetchSliderImageRepository;
use App\Repositories\Frontend\State\Fetch\F_FetchStateInterface;
use App\Repositories\Frontend\State\Fetch\F_FetchStateRepository;
use App\Repositories\Frontend\Store\Fetch\F_FetchStoreInterface;
use App\Repositories\Frontend\Store\Fetch\F_FetchStoreRepository;
use App\Repositories\Frontend\Store\Find\F_FindStoreInterface;
use App\Repositories\Frontend\Store\Find\F_FindStoreRepository;
use App\Repositories\Frontend\StoreReview\Create\F_CreateStoreReviewInterface;
use App\Repositories\Frontend\StoreReview\Create\F_CreateStoreReviewRepository;
use App\Repositories\Frontend\User\Create\F_CreateUserInterface;
use App\Repositories\Frontend\User\Create\F_CreateUserRepository;
use App\Repositories\Frontend\Wallet\Create\F_CreateWalletInterface;
use App\Repositories\Frontend\Wallet\Create\F_CreateWalletRepository;
use App\Repositories\Frontend\Category\Find\F_FindCategoryInterface;
use App\Repositories\Frontend\Category\Find\F_FindCategoryRepository;
use Illuminate\Support\ServiceProvider;

class F_RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            F_FindCategoryInterface::class,
            F_FindCategoryRepository::class);

        $this->app->bind(
            F_CreateOtpCodeInterface::class,
            F_CreateOtpCodeRepository::class);

        $this->app->bind(
            F_CreateUserInterface::class,
            F_CreateUserRepository::class);

        $this->app->bind(
            F_FetchStoreInterface::class,
            F_FetchStoreRepository::class);

        $this->app->bind(
            F_FetchCarBrandInterface::class,
            F_FetchCarBrandRepository::class);

        $this->app->bind(
            F_FetchCarModelInterface::class,
            F_FetchCarModelRepository::class);

        $this->app->bind(
            F_FetchCarTypeInterface::class,
            F_FetchCarTypeRepository::class);

        $this->app->bind(
            F_FetchStateInterface::class,
            F_FetchStateRepository::class);

        $this->app->bind(
            F_FetchAreaInterface::class,
            F_FetchAreaRepository::class);

        $this->app->bind(
            F_FindStoreInterface::class,
            F_FindStoreRepository::class);

        $this->app->bind(
            F_FindProductInterface::class,
            F_FindProductRepository::class);
 
        $this->app->bind(
            F_FetchProductInterface::class,
            F_FetchProductRepository::class);
    
        $this->app->bind(
            F_FetchCategoryInterface::class,
            F_FetchCategoryRepository::class);

        $this->app->bind(
            F_CreateProductFavoriteInterface::class,
            F_CreateProductFavoriteRepository::class);

        $this->app->bind(
            F_CreateAddressInterface::class,
            F_CreateAddressRepository::class);

        $this->app->bind(
            F_CreateRepOrderInterface::class,
            F_CreateRepOrderRepository::class);

        $this->app->bind(
            F_CreateOrderInterface::class,
            F_CreateOrderRepository::class);

        $this->app->bind(
            F_FindOrderInterface::class,
            F_FindOrderRepository::class);

        $this->app->bind(
            F_InsertOrderProductInterface::class,
            F_InsertOrderProductRepository::class);

        $this->app->bind(
            F_CreateStoreReviewInterface::class,
            F_CreateStoreReviewRepository::class);

        $this->app->bind(
            F_CreateWalletInterface::class,
            F_CreateWalletRepository::class);

        $this->app->bind(
            F_FetchSliderImageInterface::class,
            F_FetchSliderImageRepository::class);

        $this->app->bind(
            F_FetchAjzaOfferInterface::class,
            F_FetchAjzaOfferRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
