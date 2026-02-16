<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'event',
        'auditable_type',
        'auditable_id',
        'actor_id',
        'before_data',
        'after_data',
        'changed_fields',
        'route_name',
        'method',
        'url',
        'ip_address',
        'user_agent',
        'source',
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data' => 'array',
        'changed_fields' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'actor_id');
    }

    public function getModelNameAttribute(): string
    {
        return class_basename($this->auditable_type);
    }
}