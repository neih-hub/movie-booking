<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('foods', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('price')->default(0);
        $table->integer('total')->default(0); // Số lượng còn lại
        $table->timestamps();
    });
}

    public function down()
    {
    Schema::dropIfExists('foods');
    }

};