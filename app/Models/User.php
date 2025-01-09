<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeSearchByName($query, $name)
    {
        if ($name) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
        return $query;
    }

    public function scopeSearchByEmail($query, $email)
    {
        if ($email) {
            return $query->where('email', 'like', '%' . $email . '%');
        }
        return $query;
    }

    public function scopeSearchByRole($query, $role)
    {
        if ($role) {
            return $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', 'like', '%' . $role . '%');
            });
        }
        return $query;
    }
}
