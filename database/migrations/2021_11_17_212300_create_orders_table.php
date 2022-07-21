<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable();
            $table->integer('total_order')->nullable();
            $table->string('kode_order')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->integer('kode_unik')->nullable();
            $table->date('tanggal_order')->nullable();
            $table->foreignId('payment_method_id')->nullable();
            $table->enum('status', ['Belum Bayar', 'Diproses', 'Siap Diambil', 'Selesai', 'Ditolak', 'Dibatalkan'])->default('Belum Bayar');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
