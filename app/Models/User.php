<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'address',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'cashier_id');
    }

    // Helper methods untuk check role
    public function isDirektur()
    {
        return $this->role->name === 'direktur';
    }

    public function isManajerUnit()
    {
        return $this->role->name === 'manajer_unit';
    }

    public function isKasir()
    {
        return $this->role->name === 'kasir';
    }

    public function isLogistik()
    {
        return $this->role->name === 'logistik';
    }

    public function isAdminTI()
    {
        return $this->role->name === 'admin_ti';
    }
}