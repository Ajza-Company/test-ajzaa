<?php

namespace App\Models;

use App\Filters\General\SupportChatsFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SupportChat extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'subject', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportChatMessage::class, 'support_chat_id');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(SupportChatMessage::class, 'support_chat_id')->where('message_type', '!=', 'ended')->latest();
    }

    /**
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request): Builder
    {
        return (new SupportChatsFilter($request))->filter($builder);
    }
}