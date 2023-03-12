<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class ,'order_id' , 'id');
    }
    public function captain()
    {
        return $this->belongsTo(Captain::class ,'captain_id' , 'id');
    }
}
