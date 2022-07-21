<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukTransaksi extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'produk_transaksi';
    //public $incrementing = false;

    protected $fillable = ['jumlah', 'jenis_transaksi', 'tanggal_transaksi', 'status_transaksi', 'produk_id'];

    protected $dates = ['tanggal_transaksi'];

    /**
     * Get the produk that owns the ProdukTransaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
