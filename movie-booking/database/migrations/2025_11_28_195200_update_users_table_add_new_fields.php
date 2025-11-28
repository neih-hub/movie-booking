<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('role')->default(1)->change();
        });
    }

    public function down(): void
    {
        // rollback không cần thiết, nhưng có thể thêm nếu bạn muốn
    }
};