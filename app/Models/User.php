<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'google_id', 'is_guest_account', 'guest_lease_token', 'guest_lease_expires_at', 'last_activity_at'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const MAX_GUEST_ACCOUNTS = 50;

    protected function casts(): array
    {
        return [
            'is_guest_account' => 'boolean',
            'guest_lease_expires_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }

    public function isGuest(): bool
    {
        return $this->is_guest_account;
    }

    public function scopeGuestAccount(Builder $query): Builder
    {
        return $query->where('is_guest_account', true);
    }
}
