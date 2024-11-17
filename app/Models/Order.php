<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
    ];

    /**
     * Relación con productos (muchos a muchos) a través de la tabla intermedia.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    /**
     * Relación inversa con el usuario (uno a muchos).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

