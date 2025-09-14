<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_required' => 'boolean',
        ];
    }

    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_secret) && ! is_null($this->two_factor_confirmed_at);
    }

    public function isTwoFactorRequired(): bool
    {
        return $this->two_factor_required || $this->hasTwoFactorEnabled();
    }

    public function generateTwoFactorRecoveryCodes(): void
    {
        $codes = collect(range(1, 8))->map(fn () => Str::random(10).'-'.Str::random(10))->toArray();
        $this->two_factor_recovery_codes = encrypt(json_encode($codes));
        $this->save();
    }

    public function getTwoFactorRecoveryCodes(): array
    {
        return $this->two_factor_recovery_codes
            ? json_decode(decrypt($this->two_factor_recovery_codes), true)
            : [];
    }

    public function useTwoFactorRecoveryCode(string $code): bool
    {
        $codes = $this->getTwoFactorRecoveryCodes();

        if (! in_array($code, $codes)) {
            return false;
        }

        $codes = array_values(array_diff($codes, [$code]));
        $this->two_factor_recovery_codes = encrypt(json_encode($codes));
        $this->save();

        return true;
    }

    protected function twoFactorSecret(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? decrypt($value) : null,
            set: fn ($value) => $value ? encrypt($value) : null,
        );
    }
}
