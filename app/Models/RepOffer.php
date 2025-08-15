<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RepOffer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['rep_order_id', 'price', 'status','rep_user_id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(RepOrder::class, 'rep_order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rep_user_id');
    }


    public function message(): HasOne
    {
        return $this->hasOne(RepChatMessage::class);
    }
}
