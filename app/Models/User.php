<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Filters\Admin\GetUserFilter;
use App\Filters\Admin\UserFilter;
use DateTimeInterface;
use App\Models\RepChat;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, SoftDeletes;

    protected string $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'full_mobile',
        'password',
        'avatar',
        'is_active',
        'is_registered',
        'gender',
        'state_id',
        'deletion_status',
        'deletion_requested_at',
        'deletion_reason'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deletion_requested_at' => 'datetime'
        ];
    }

    /**
     *
     * @return NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'], DateTimeInterface $expiresAt = null)
    {
        $plainTextToken = $this->generateTokenString();

        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
        ]);

        //This is the line that we change from the original function
        //We basically removed the `id|` prefix from the token
        return new NewAccessToken($token, $plainTextToken);
    }

    /**
     *
     * @return HasMany
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(ProductFavorite::class, 'user_id');
    }

    /**
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     *
     * @return HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    /**
     *
     * @return HasMany
     */
    public function storeUsers(): HasMany
    {
        return $this->hasMany(StoreUser::class, 'user_id');
    }

    /**
     *
     * @return HasMany
     */
    public function repOrders(): HasMany
    {
        return $this->hasMany(RepOrder::class, 'user_id');
    }

    /**
     *
     * @return HasMany
     */
    public function repVendorOrders(): HasMany
    {
        return $this->hasMany(RepOrder::class, 'rep_id');
    }
    /**
     *
     * @return HasMany
     */
    public function userFcmTokens(): HasMany
    {
        return $this->hasMany(UserFcmToken::class, 'user_id');
    }

    /**
     *
     * @return HasManyThrough
     */
    public function favoriteProducts(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, ProductFavorite::class, 'user_id', 'id', 'id', 'product_id');
    }

    /**
     *
     * @return HasOne
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'user_id');
    }

    /**
     *
     * @return HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }

    /**
     *
     * @return HasManyThrough
     */
    public function stores(): HasManyThrough
    {
        return $this->hasManyThrough(Store::class, StoreUser::class, 'user_id', 'id', 'id', 'store_id');
    }

    /**
     *
     * @return HasOneThrough
     */
    public function store(): HasOneThrough
    {
        return $this->hasOneThrough(Store::class, StoreUser::class, 'user_id', 'id', 'id', 'store_id');
    }

    /**
     *
     * @return HasMany
     */
    public function offers(): HasMany
    {
        return $this->hasMany(RepOffer::class, 'rep_user_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * Filter Scope
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request): Builder
    {
        return (new UserFilter($request))->filter($builder);
    }

    public function scopeGetUserFilter (Builder $builder, $request): Builder
    {
        return (new GetUserFilter($request))->filter($builder);
    }

    /**
     * Check if user account is pending deletion
     */
    public function isPendingDeletion(): bool
    {
        return $this->deletion_status === 'pending_deletion';
    }

    /**
     * Check if user account is deleted
     */
    public function isDeleted(): bool
    {
        return $this->deletion_status === 'deleted';
    }

    /**
     * Check if user can perform actions
     */
    public function canPerformActions(): bool
    {
        return $this->deletion_status === 'active' && $this->is_active;
    }

    /**
     * Check if user can make orders
     */
    public function canMakeOrders(): bool
    {
        return $this->canPerformActions();
    }

    /**
     * Check if user can access dashboard
     */
    public function canAccessDashboard(): bool
    {
        return $this->canPerformActions();
    }

    /**
     * Check if user is completely blocked
     */
    public function isBlocked(): bool
    {
        return $this->deletion_status === 'deleted' || !$this->is_active;
    }

    /**
     * Get deletion date (15 days from request)
     */
    public function getDeletionDateAttribute(): ?string
    {
        if ($this->deletion_requested_at) {
            return $this->deletion_requested_at->addDays(15)->format('Y-m-d H:i:s');
        }
        return null;
    }

    /**
     * Get remaining days until deletion
     */
    public function getRemainingDaysAttribute(): ?int
    {
        if ($this->deletion_requested_at) {
            $deletionDate = $this->deletion_requested_at->addDays(15);
            $now = now();
            
            if ($deletionDate->isPast()) {
                return 0;
            }
            
            return $now->diffInDays($deletionDate, false);
        }
        return null;
    }
}
