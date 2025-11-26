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
        Schema::create('posts_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('posts_id');
            $table->foreign('posts_id', 'fk_posts_tags_posts')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade');
            $table->index('posts_id', 'ix_posts_tags_posts_id');
            $table->unsignedBigInteger('tags_id');
            $table->foreign('tags_id', 'fk_posts_tags_tags')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');
            $table->index('tags_id', 'ix_posts_tags_tags_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_tags');
    }
};
