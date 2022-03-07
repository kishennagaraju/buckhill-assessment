<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{
    use HasFactory;

    protected $table = "password_resets";

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $fillable = [
        'email',
        'token',
    ];

    protected $hidden = [
        'id'
    ];

    public function forgotPassword($email)
    {
        return $this->newQuery()->create([
            'email' => $email
        ]);
    }
}
