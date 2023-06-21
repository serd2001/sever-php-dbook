<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(orderDetail::class);
    }
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
