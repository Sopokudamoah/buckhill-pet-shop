<?php

namespace App\Models;

use App\Guards\JwtAuthGuard;
use App\Helpers\AccessToken;
use App\Models\Traits\HasUuid;
use App\Notifications\SendPasswordResetToken;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'address',
        'phone_number',
        'is_marketing',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function jwtTokens()
    {
        return $this->hasMany(JwtToken::class, 'user_id');
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => "{$attributes['first_name']} {$attributes['last_name']}",
        );
    }

    public function createToken(string $name = null, array $abilities = ['*'], DateTimeInterface $expiresAt = null)
    {
        $unique_id = hash('sha256', Str::random(40));

        $expiresAt = Carbon::parse($expiresAt ?? now()->addSeconds(config('jwt.expiration')));

        $token = $this->jwtTokens()->create([
            'token_title' => $name ?? $this->fullName,
            'unique_id' => $unique_id,
            'permissions' => $abilities,
            'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
        ]);

        $payload = [
            'unique_id' => $unique_id,
            JwtAuthGuard::$user_key => $this->uuid,
            JwtAuthGuard::$token_key => $token->id
        ];

        $jwt = jwt_encode($payload, $expiresAt);

        return new AccessToken($jwt);
    }

    public function currentAccessToken()
    {
        $payload = jwt_decode(request()->bearerToken());
        return $this->jwtTokens()->find($payload['token_id']);
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SendPasswordResetToken($token));
    }


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }


    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Order::class, 'user_id', 'id', null, 'payment_id')->select(
            ['payments.*']
        );
    }
}
