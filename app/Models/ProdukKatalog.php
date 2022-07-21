<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKatalog extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'produk_katalog';
    //public $incrementing = false;

    protected $fillable = ['nama_katalog'];

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
