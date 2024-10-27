<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'image',
        'video',
        'price',
        'release_date',
        'discount',
        'stock_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
