<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'user'],
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->default(2);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });

        User::create([
            'name' => 'Nori',
            'email' => 'admin@nori.my',
            'password' => bcrypt('@Nedoeci202'),
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Ridwan',
            'email' => 'pratamaridwan111@gmail.com',
            'password' => bcrypt('@Nedoeci202'),
            'role_id' => 2,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
        });
    }
};
