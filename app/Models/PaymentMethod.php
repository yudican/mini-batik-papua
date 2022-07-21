<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'payment_method';
    //public $incrementing = false;

    protected $fillable = ['nama_bank', 'logo_bank', 'nomor_rekening_bank', 'nama_rekening_bank'];

    protected $dates = [];
}
