<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('order_id')->unique(); // untuk kode unik transaksi
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke users
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // relasi ke services

            $table->string('pickup_address');
            $table->string('delivery_address')->nullable(); // bisa null jika belum diantar
            $table->date('transaction_date')->default(DB::raw('CURRENT_DATE')); // default hari ini

            $table->integer('quantity')->default(1); // jumlah layanan
            $table->decimal('total_amount', 10, 2)->default(0); // total harga

            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'transfer'])->default('cash');
            $table->enum('service_status', ['waiting', 'process', 'done', 'delivered'])->default('waiting');

            $table->time('pickup_time')->nullable();
            $table->time('delivery_time')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};