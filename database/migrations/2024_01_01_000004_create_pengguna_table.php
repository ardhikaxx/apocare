<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->foreignId('role_id')->constrained('peran');
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto', 255)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('login_terakhir')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna');
            $table->foreignId('diubah_oleh')->nullable()->constrained('pengguna');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
