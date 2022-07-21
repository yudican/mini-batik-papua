<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisProduk extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'jenis_produk';
    //public $incrementing = false;

    protected $fillable = ['nama_jenis'];

    protected $dates = [];

    /**
     * Get all of the produk for the ProdukKatalog
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}
