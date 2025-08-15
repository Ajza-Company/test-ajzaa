<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Lang;

/**
 */
final class SuccessMessagesEnum extends Enum
{
    const CREATED = 'success.created';
    const CANCELLED = 'success.cancelled';
    const UPDATED = 'success.updated';
    const DELETED = 'success.deleted';
    const IMPORTED = 'success.imported';
    const REQUESTED = 'success.requested';
    const VERIFIED = 'success.verified';
    const LOGGEDOUT = 'success.logged_out';
    const LOGGEDIN = 'success.logged_in';
    const REPORTED = 'success.reported';
    const CONNECTED = 'success.connected';
    const FOUND = 'success.found';
    const INITIATED = 'success.initiated';
    const SENT = 'success.sent';
}
