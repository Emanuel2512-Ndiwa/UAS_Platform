<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // 

return new class extends Migration {
    public function up() {
        DB::statement("ALTER TABLE transactions MODIFY service_status ENUM('waiting', 'process', 'done', 'delivered') DEFAULT 'waiting'");
    }

    public function down() {
        DB::statement("ALTER TABLE transactions MODIFY service_status ENUM('waiting', 'process', 'done') DEFAULT 'waiting'");
    }
};