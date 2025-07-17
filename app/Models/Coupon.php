<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'discount', 'type', 'deadline_at', 'active'];

    protected $casts = [
        'deadline_at' => 'datetime',
    ];
}
