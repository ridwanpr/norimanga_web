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
        Schema::table('manga', function (Blueprint $table) {
            $table->index('slug');
        });

        Schema::table('manga_detail', function (Blueprint $table) {
            $table->index('manga_id');
            $table->index('type');
            $table->index('cover');
        });

        Schema::table('manga_chapters', function (Blueprint $table) {
            $table->index('chapter_number');
            $table->index('slug');
        });

        Schema::table('genres', function (Blueprint $table) {
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manga', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        Schema::table('manga_detail', function (Blueprint $table) {
            $table->dropIndex(['manga_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['cover']);
        });

        Schema::table('manga_chapters', function (Blueprint $table) {
            $table->dropIndex(['chapter_number']);
            $table->dropIndex(['slug']);
        });

        Schema::table('genres', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });
    }
};