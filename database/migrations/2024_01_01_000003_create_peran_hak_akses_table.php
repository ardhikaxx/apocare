<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peran_hak_akses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('peran')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('hak_akses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peran_hak_akses');
    }
};
