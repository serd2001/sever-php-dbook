<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = ['name', 'surname', 'dob', 'phone', 'password'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function getAuthIdentifierName()
    {
        return 'id';
    }
}
