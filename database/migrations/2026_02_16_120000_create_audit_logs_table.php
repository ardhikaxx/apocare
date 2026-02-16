<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event', 20);
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->foreignId('actor_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->json('before_data')->nullable();
            $table->json('after_data')->nullable();
            $table->json('changed_fields')->nullable();
            $table->string('route_name')->nullable();
            $table->string('method', 10)->nullable();
            $table->text('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('source', 20)->default('web');
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['event']);
            $table->index(['actor_id']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};