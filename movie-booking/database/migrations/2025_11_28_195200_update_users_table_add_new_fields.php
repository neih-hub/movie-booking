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
            // Các trường mới muốn thêm vào bảng users
            $table->tinyInteger('role')->default(1)->after('password');       // 0 = admin, 1 = user
            $table->tinyInteger('status')->default(1)->after('role');         // 1 = active, 0 = banned

            $table->string('avatar')->nullable()->after('status');            // ảnh đại diện
            $table->string('phone')->nullable()->after('avatar');             // số điện thoại
            $table->string('address')->nullable()->after('phone');            // địa chỉ
            $table->date('birthday')->nullable()->after('address');           // ngày sinh
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birthday');
            $table->timestamp('last_login_at')->nullable()->after('gender');  // lần đăng nhập cuối
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa các cột khi rollback
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