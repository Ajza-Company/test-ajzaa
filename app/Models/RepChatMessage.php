<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepChatMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'rep_chat_id',
        'rep_offer_id',
        'message',
        'message_type',
        'attachment'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(RepChat::class, 'rep_chat_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(RepOffer::class, 'rep_offer_id');
    }
}
