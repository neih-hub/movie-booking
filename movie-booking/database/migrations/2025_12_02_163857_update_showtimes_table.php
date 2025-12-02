<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('showtimes', function (Blueprint $table) {

        // Xóa cột end_time nếu tồn tại
        if (Schema::hasColumn('showtimes', 'end_time')) {
            $table->dropColumn('end_time');
        }

        // Thêm ngày chiếu
        if (!Schema::hasColumn('showtimes', 'date_start')) {
            $table->date('date_start')->after('room_id');
        }

        // start_time trở thành thời gian chiếu (time)
        // Nếu đang là datetime → bạn phải tự sửa trực tiếp hoặc làm migration đổi kiểu:
        $table->time('start_time')->change();

        // Thêm giá vé
        if (!Schema::hasColumn('showtimes', 'price')) {
            $table->integer('price')->default(0)->after('start_time');
        }
    });
}

public function down()
{
    Schema::table('showtimes', function (Blueprint $table) {
        // Khôi phục end_time
        $table->dateTime('end_time')->nullable();

        // Xóa date_start
        $table->dropColumn('date_start');

        // start_time trở về datetime
        $table->dateTime('start_time')->change();

        // Xóa price
        $table->dropColumn('price');
    });
}

};