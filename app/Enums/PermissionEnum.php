<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 */
final class PermissionEnum extends Enum
{
    // Supplier Permissions
    const SHOW_ALL_PERMISSIONS = 'show-all-permissions';
    const VIEW_ORDERS = 'view-orders';
    const ACCEPT_ORDERS = 'accept-orders';
    const VIEW_OFFERS = 'view-offers';
    const CONTROL_OFFER = 'control-offer';
    const VIEW_USERS = 'view-users';
    const EDIT_USERS = 'edit-users';
    const VIEW_STORES = 'view-stores';
    const EDIT_STORES = 'edit-stores';
    const VIEW_CATEGORIES = 'view-categories';
    const EDIT_CATEGORIES = 'edit-categories';
    const VIEW_PRODUCTS = 'view-products';
    const EDIT_PRODUCTS = 'edit-products';
    const VIEW_ORDERS_STATISTICS = 'view-orders-statistics';

    // Admin Permissions
    const SHOW_ALL_USERS = 'a.show-all-users';
    const CONTROL_USER = 'a.control-user';
    const SHOW_ALL_STORES = 'a.show-all-stores';
    const CONTROL_STORE = 'a.control-store';
    const SHOW_ALL_REPSALES = 'a.show-all-repSales';
    const CONTROL_REPSALES = 'a.control-repSales';
    const SHOW_ALL_PROMOS = 'a.show-all-promos';
    const CONTROL_PROMO = 'a.control-promo';
    const SHOW_ALL_PRODUCTS = 'a.show-all-products';
    const CONTROL_PRODUCT = 'a.control-product';
    const SHOW_ALL_STATES = 'a.show-all-states';
    const CONTROL_STATE = 'a.control-state';
    const SHOW_ALL_OFFERS = 'a.show-all-offers';
    const CONTROL_OFFERS = 'a.control-offers';
    const SHOW_ALL_CHAT = 'a.show-all-chat';
    const CONTROL_CHAT = 'a.control-chat';

}
