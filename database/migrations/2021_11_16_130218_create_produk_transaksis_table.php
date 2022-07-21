<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('jumlah')->nullable();
            $table->string('jenis_transaksi')->nullable();
            $table->dateTime('tanggal_transaksi')->nullable();
            $table->enum('status_transaksi', ['pending', 'berhasil', 'diproses'])->nullable();
            $table->foreignId('produk_id');
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_transaksi');
    }
}
