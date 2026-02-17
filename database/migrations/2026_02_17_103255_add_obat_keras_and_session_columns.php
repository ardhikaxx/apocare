<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->string('last_login_at')->nullable()->after('login_terakhir');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->boolean('is_online')->default(false)->after('last_login_ip');
        });
    }

    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'last_login_ip', 'is_online']);
        });
    }
};
