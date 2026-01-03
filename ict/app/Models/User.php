<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * 'role' is a string column: 'admin_ict', 'admin_hr', 'staff'
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // simple string role column
        'nama',
        'jawatan',
        'bahagian',
        'profile_pic',
    ];

    /**
     * Hidden attributes for arrays/serialization.
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Return stored role string (or null).
     *
     * @return string|null
     */
    public function roleName(): ?string
{
    return $this->role;
}

public function isAdminIct(): bool
{
    return $this->roleName() === 'admin_ict';
}

public function isAdminHr(): bool
{
    return $this->roleName() === 'admin_hr';
}

public function isStaff(): bool
{
    return $this->roleName() === 'staff';
}

    /**
     * Generic role checker.
     *
     * @param  string|array  $role
     * @return bool
     */
    public function hasRole(string|array $role): bool
    {
        $current = $this->roleName();

        if (is_array($role)) {
            return in_array($current, $role, true);
        }

        return $current === $role;
    }
}
