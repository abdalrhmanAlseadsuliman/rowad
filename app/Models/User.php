<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role' => Role::class,
        'subscription_end_date' => 'date',
        'registration_date' => 'date',
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
        ];
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function plan()
    {
        return $this->belongsTo(StudyPlan::class, 'plan_id');
    }


    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function recentlyReadBooks()
    {
        return $this->hasMany(RecentlyReadBook::class);
    }


    /**
     * تحقق من دور المستخدم
     */
    public function hasRole(string|Role $role): bool
    {
        if (is_string($role)) {
            $role = Role::from($role);
        }

        return $this->role === $role;
    }

    /**
     * تحقق من كون المستخدم admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(Role::Admin);
    }

    /**
     * تحقق من كون المستخدم student
     */
    public function isStudent(): bool
    {
        return $this->hasRole(Role::Student);
    }



    public function isSubscriptionActive(): bool
    {
        return $this->subscription_end_date && $this->subscription_end_date->isFuture();
    }

    public function isSubscriptionExpiringSoon(int $days = 30): bool
    {
        if (!$this->subscription_end_date) return false;

        return $this->subscription_end_date->isFuture() &&
            $this->subscription_end_date->diffInDays(now()) <= $days;
    }

    public function getSubscriptionStatusAttribute(): string
    {
        if (!$this->subscription_end_date) {
            return 'no_subscription';
        }

        if ($this->subscription_end_date->isPast()) {
            return 'expired';
        }

        if ($this->isSubscriptionExpiringSoon()) {
            return 'expiring_soon';
        }

        return 'active';
    }
}
