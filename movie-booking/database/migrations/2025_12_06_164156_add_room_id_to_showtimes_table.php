<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('showtimes', function (Blueprint $table) {
            if (!Schema::hasColumn('showtimes', 'room_id')) {
                $table->unsignedBigInteger('room_id')->after('movie_id');

                $table->foreign('room_id')
                    ->references('id')->on('rooms')
                    ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('showtimes', function (Blueprint $table) {
            if (Schema::hasColumn('showtimes', 'room_id')) {
                $table->dropForeign(['room_id']);
                $table->dropColumn('room_id');
            }
        });
    }
};
