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
        Schema::table('users', function (Blueprint $table) {
            // Thuộc tính mới bạn yêu cầu thêm
            $table->tinyInteger('role')->default(1)->after('password'); 
            $table->tinyInteger('status')->default(1)->after('role');

            $table->string('avatar')->nullable()->after('status');
            $table->string('phone')->nullable()->after('avatar');
            $table->string('address')->nullable()->after('phone');
            $table->date('birthday')->nullable()->after('address');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birthday');
            $table->timestamp('last_login_at')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xoá các cột nếu rollback
            $table->dropColumn([
                'role',
                'status',
                'avatar',
                'phone',
                'address',
                'birthday',
                'gender',
                'last_login_at',
            ]);
        });
    }
};