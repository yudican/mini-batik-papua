<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confirm_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id');
            $table->foreignId('order_id');
            $table->string('nama_bank')->nullable();
            $table->string('nomor_rekening_bank')->nullable();
            $table->string('nama_rekening_bank')->nullable();
            $table->string('jumlah_transfer')->nullable();
            $table->date('tanggal_transfer')->nullable();
            $table->enum('status', ['Diproses', 'Diterima', 'Ditolak'])->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('confirm_payments');
    }
}
