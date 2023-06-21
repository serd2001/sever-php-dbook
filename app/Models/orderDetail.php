<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderDetail extends Model
{
    protected $table = 'order_details';
    use HasFactory;
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
