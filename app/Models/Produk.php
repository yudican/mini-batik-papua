<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'produk';
    //public $incrementing = false;

    protected $fillable = ['nama_produk', 'foto_produk', 'harga_produk', 'deskripsi_produk', 'jenis_produk_id', 'produk_katalog_id'];

    protected $dates = [];
    protected $appends = ['stok'];

    /**
     * Get the jenisProduk that owns the Produk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenisProduk()
    {
        return $this->belongsTo(JenisProduk::class);
    }

    /**
     * Get the produkKatalog that owns the Produk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produkKatalog()
    {
        return $this->belongsTo(ProdukKatalog::class);
    }

    /**
     * Get all of the produkTransaksi for the Produk
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produkTransaksi()
    {
        return $this->hasMany(ProdukTransaksi::class);
    }

    public function getStokAttribute()
    {
        $masuk = $this->produkTransaksi()->whereJenisTransaksi('transaksi-masuk')->sum('jumlah');
        $keluar = $this->produkTransaksi()->whereJenisTransaksi('transaksi-keluar')->sum('jumlah');

        return $masuk - $keluar;
    }
}
