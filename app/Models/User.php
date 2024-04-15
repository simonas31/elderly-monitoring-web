<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'security_type',
        'parent_user_id',
        'role_id',
        'phone_number',
        'date_of_birth',
        'fall_notifications',
        'profile_picture',
        'email_verified_at',
        'two_factor_code',
        'two_factor_expires_at',
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
        'password' => 'hashed',
    ];

    public function role(): HasOne
    {
        return $this->hasOne(Role::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRole()
    {
        return Role::find($this->role_id);
    }

    public function generateTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }

    public function resetTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function checkTwoFactorCode($code): string
    {
        if (isset($this->two_factor_expires_at) && $this->two_factor_expires_at >= now()->addMinutes(15)) {
            return "Expired";
        }

        if (!isset($code)) {
            return "Code is empty";
        }

        if ($this->two_factor_code == $code) {
            return "OK";
        } else {
            return "Invalid code";
        }
    }

    public function routeNotificationForVonage($notification)
    {
        return $this->phone_number;
    }
}
