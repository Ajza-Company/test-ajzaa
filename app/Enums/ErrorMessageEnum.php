<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 */
final class ErrorMessageEnum extends Enum
{
    const FETCH = 'error.fetch';
    const DELETE = 'error.delete';
    const UPDATE = 'error.update';
    const CREATE = 'error.create';
    const CANCEL = 'error.cancel';
    const IMPORT = 'error.import';
    const REQUEST = 'error.request';
    const VERIFY = 'error.verify';
    const LOGOUT = 'error.logout';
    const LOGIN = 'error.login';
    const REPORT = 'error.report';
    const CONNECT = 'error.connect';
    const FOUND = 'error.found';
    const INITIATE = 'error.initiate';
    const SEND = 'error.send';
}
