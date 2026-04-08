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
            
            // Kode resi unik
            $table->string('order_id')->unique();
            
            // Relasi dan penanganan Online vs Offline
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('customer_name_offline')->nullable();
            
            // Relasi services
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('cascade');
            
            $table->enum('order_type', ['online_pickup', 'online_delivery', 'offline_walkin'])->default('offline_walkin');
            
            // Pembayaran Midtrans & Amount
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('midtrans_snap_token')->nullable();
            $table->string('midtrans_id')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'expire', 'cancel'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'transfer'])->default('cash');
            
            // Status & Kurir
            $table->enum('service_status', ['menunggu', 'proses_cuci', 'selesai_cuci', 'diantar', 'selesai_total'])->default('menunggu');
            $table->foreignId('kurir_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('karyawan_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Estimasi dan jarak
            $table->decimal('distance', 8, 2)->nullable();
            $table->integer('eta_minutes')->nullable();
            
            // Info alamat
            $table->string('pickup_address')->nullable();
            $table->string('delivery_address')->nullable();

            // Kuantitas & waktu
            $table->integer('quantity')->default(1);
            $table->date('transaction_date')->default(DB::raw('CURRENT_DATE'));
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