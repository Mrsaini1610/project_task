<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    public function role()
    {
        return $this->belongsTo(RoleBase::class, 'role_ID', 'id');    }
    protected $fillable = [
        'name',
        'email',
        'phone',
        'description',
        'role_ID',
        'profile_image'

    ];
}
