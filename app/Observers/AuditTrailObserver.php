<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditTrailObserver
{
    private array $ignoredKeys = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    private array $sensitiveKeys = [
        'password',
        'remember_token',
    ];

    public function created(Model $model): void
    {
        $this->storeAudit('created', $model, null, $this->sanitizeAttributes($model->getAttributes()), array_keys($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $changes = $model->getChanges();
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $original = [];
        foreach (array_keys($changes) as $field) {
            $original[$field] = $model->getOriginal($field);
        }

        $this->storeAudit(
            'updated',
            $model,
            $this->sanitizeAttributes($original),
            $this->sanitizeAttributes($changes),
            array_keys($changes)
        );
    }

    public function deleted(Model $model): void
    {
        $before = $this->sanitizeAttributes($model->getOriginal());
        $this->storeAudit('deleted', $model, $before, null, array_keys($before));
    }

    public function restored(Model $model): void
    {
        $after = $this->sanitizeAttributes($model->getAttributes());
        $this->storeAudit('restored', $model, null, $after, array_keys($after));
    }

    private function storeAudit(string $event, Model $model, ?array $before, ?array $after, array $changedFields): void
    {
        if ($model instanceof AuditLog) {
            return;
        }

        if (app()->runningInConsole()) {
            return;
        }

        $request = request();

        AuditLog::query()->create([
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'actor_id' => Auth::id(),
            'before_data' => $before,
            'after_data' => $after,
            'changed_fields' => array_values(array_filter($changedFields, fn ($key) => ! in_array($key, $this->ignoredKeys, true))),
            'route_name' => $request?->route()?->getName(),
            'method' => $request?->method(),
            'url' => $request?->fullUrl(),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'source' => 'web',
        ]);
    }

    private function sanitizeAttributes(array $attributes): array
    {
        $sanitized = [];

        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->ignoredKeys, true)) {
                continue;
            }

            if (in_array($key, $this->sensitiveKeys, true)) {
                $sanitized[$key] = '[HIDDEN]';
                continue;
            }

            if (is_array($value) || is_object($value)) {
                $sanitized[$key] = json_decode(json_encode($value), true);
                continue;
            }

            $sanitized[$key] = $value;
        }

        return $sanitized;
    }
}