<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** 
     * Run the migrations. 
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('pickup_address');
            $table->string('delivery_address');
            $table->date('transaction_date');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'cancelled']);
            $table->enum('service_status', ['waiting', 'process', 'done']);
            $table->time('pickup_time')->nullable();
            $table->time('delivery_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
        });
    }

    /** 
     * Reverse the migrations. 
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        

        Schema::dropIfExists('transactions');
        
    }
};