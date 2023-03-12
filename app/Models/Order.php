<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function notifications()
    {
        return $this->hasMany(Notification::class );
    }
    public function scopeTotal()
    {
        return $this->paginate(10);
    }

    public function scopeActive()
    {
        return $this->where('status' , 0);
    }
}
