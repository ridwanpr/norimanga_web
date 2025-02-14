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
        Schema::create('manga_views', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unsignedBigInteger('manga_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manga_views');
    }
};
