<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['total'];

    /**
     * Get all of the produk for the ProdukKatalog
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Get the order that owns the OrderDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the total price of the order detail
     *
     * @return int
     */
    public function getTotalAttribute()
    {
        return $this->produk->harga_produk * $this->attributes['qty'];
    }
}
