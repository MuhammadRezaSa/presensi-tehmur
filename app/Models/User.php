<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function cabang()
    {
        return $this->hasOne(Cabang::class, 'user_id');
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isCabang(): bool
    {
        return $this->role === 'cabang';
    }
}
