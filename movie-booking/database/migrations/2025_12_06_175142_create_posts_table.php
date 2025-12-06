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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Tiêu đề bài viết
            $table->string('title');

            // Nội dung bài viết
            $table->longText('content');

            // Tóm tắt ngắn
            $table->text('excerpt')->nullable();

            // Ảnh đại diện bài viết
            $table->string('thumbnail')->nullable();

            // Người viết (admin)
            $table->unsignedBigInteger('author_id')->nullable();

            // Loại bài viết
            $table->enum('category', ['review', 'news', 'article'])
                  ->default('review');

            // Lượt xem
            $table->integer('views')->default(0);

            // Trạng thái: Nháp / Đã xuất bản
            $table->enum('status', ['draft', 'published'])
                  ->default('draft');

            // Thời gian xuất bản
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            // Khóa ngoại
            $table->foreign('author_id')
                  ->references('id')->on('users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
