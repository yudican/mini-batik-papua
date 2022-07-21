<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk')->nullable();
            $table->string('foto_produk')->nullable();
            $table->string('harga_produk')->nullable();
            $table->text('deskripsi_produk')->nullable();
            $table->foreignId('jenis_produk_id');
            $table->foreignId('produk_katalog_id');
            $table->timestamps();

            $table->foreign('jenis_produk_id')->references('id')->on('jenis_produk')->onDelete('cascade');
            $table->foreign('produk_katalog_id')->references('id')->on('produk_katalog')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
}
