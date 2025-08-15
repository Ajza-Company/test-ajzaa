<?php

namespace App\Models;

use App\Filters\General\RepChatsFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RepChat extends Model
{
    use HasFactory;
    protected $fillable = ['rep_order_id', 'user1_id', 'user2_id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(RepOrder::class, 'rep_order_id');
    }

    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(RepChatMessage::class, 'rep_chat_id');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(RepChatMessage::class, 'rep_chat_id')->where('message_type', '!=', 'ended')->latest();
    }

    /**
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request): Builder
    {
        return (new RepChatsFilter($request))->filter($builder);
    }
}
