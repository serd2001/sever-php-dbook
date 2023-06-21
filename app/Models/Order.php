<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function orderDetails()
    {
        return $this->hasMany(orderDetail::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
    public function import()
    {
        return $this->belongsTo(import::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
