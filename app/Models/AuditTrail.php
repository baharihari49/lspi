<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditTrail extends Model
{
    protected $fillable = [
        'event',
        'action',
        'module',
        'severity',
        'auditable_type',
        'auditable_id',
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'old_values',
        'new_values',
        'changes',
        'metadata',
        'ip_address',
        'user_agent',
        'request_method',
        'request_url',
        'request_data',
        'session_id',
        'response_code',
        'response_status',
        'error_message',
        'tags',
        'description',
        'performed_at',
        'batch_id',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changes' => 'array',
        'metadata' => 'array',
        'request_data' => 'array',
        'tags' => 'array',
        'performed_at' => 'datetime',
        'response_code' => 'integer',
    ];

    /**
     * Get the auditable entity
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter by event type
     */
    public function scopeByEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope: Filter by module
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope: Filter by severity
     */
    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by batch
     */
    public function scopeByBatch($query, string $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    /**
     * Scope: Only created events
     */
    public function scopeCreated($query)
    {
        return $query->where('event', 'created');
    }

    /**
     * Scope: Only updated events
     */
    public function scopeUpdated($query)
    {
        return $query->where('event', 'updated');
    }

    /**
     * Scope: Only deleted events
     */
    public function scopeDeleted($query)
    {
        return $query->where('event', 'deleted');
    }

    /**
     * Scope: Only viewed events
     */
    public function scopeViewed($query)
    {
        return $query->where('event', 'viewed');
    }

    /**
     * Scope: Only login events
     */
    public function scopeLogin($query)
    {
        return $query->where('event', 'login');
    }

    /**
     * Scope: Only logout events
     */
    public function scopeLogout($query)
    {
        return $query->where('event', 'logout');
    }

    /**
     * Scope: Critical severity
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    /**
     * Scope: Error severity
     */
    public function scopeError($query)
    {
        return $query->where('severity', 'error');
    }

    /**
     * Scope: Warning severity
     */
    public function scopeWarning($query)
    {
        return $query->where('severity', 'warning');
    }

    /**
     * Scope: Info severity
     */
    public function scopeInfo($query)
    {
        return $query->where('severity', 'info');
    }

    /**
     * Scope: Recent audits (last 24 hours)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * Scope: Date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Check if action was successful
     */
    public function isSuccessful(): bool
    {
        return $this->response_status === 'success';
    }

    /**
     * Check if action failed
     */
    public function isFailed(): bool
    {
        return $this->response_status === 'failed' || $this->response_status === 'error';
    }

    /**
     * Check if severity is critical or error
     */
    public function isImportant(): bool
    {
        return in_array($this->severity, ['critical', 'error']);
    }

    /**
     * Get formatted changes for display
     */
    public function getFormattedChanges(): array
    {
        if (!$this->changes) {
            return [];
        }

        $formatted = [];
        foreach ($this->changes as $field => $values) {
            $formatted[] = [
                'field' => $field,
                'old' => $values['old'] ?? null,
                'new' => $values['new'] ?? null,
            ];
        }

        return $formatted;
    }

    /**
     * Get human-readable event name
     */
    public function getEventNameAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->event));
    }

    /**
     * Get severity color for UI
     */
    public function getSeverityColorAttribute(): string
    {
        return match ($this->severity) {
            'critical' => 'red',
            'error' => 'orange',
            'warning' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Create audit trail for model creation
     */
    public static function logCreated(Model $model, ?User $user = null, array $metadata = []): self
    {
        return self::create([
            'event' => 'created',
            'action' => class_basename($model) . ' created',
            'module' => self::getModuleFromModel($model),
            'severity' => 'info',
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'user_id' => $user?->id ?? auth()->id(),
            'user_name' => $user?->name ?? auth()->user()?->name,
            'user_email' => $user?->email ?? auth()->user()?->email,
            'new_values' => $model->toArray(),
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_method' => request()->method(),
            'request_url' => request()->fullUrl(),
            'performed_at' => now(),
        ]);
    }

    /**
     * Create audit trail for model update
     */
    public static function logUpdated(Model $model, array $oldValues, ?User $user = null, array $metadata = []): self
    {
        $changes = [];
        foreach ($model->getChanges() as $key => $newValue) {
            if (isset($oldValues[$key]) && $oldValues[$key] !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValues[$key],
                    'new' => $newValue,
                ];
            }
        }

        return self::create([
            'event' => 'updated',
            'action' => class_basename($model) . ' updated',
            'module' => self::getModuleFromModel($model),
            'severity' => 'info',
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'user_id' => $user?->id ?? auth()->id(),
            'user_name' => $user?->name ?? auth()->user()?->name,
            'user_email' => $user?->email ?? auth()->user()?->email,
            'old_values' => $oldValues,
            'new_values' => $model->toArray(),
            'changes' => $changes,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_method' => request()->method(),
            'request_url' => request()->fullUrl(),
            'performed_at' => now(),
        ]);
    }

    /**
     * Create audit trail for model deletion
     */
    public static function logDeleted(Model $model, ?User $user = null, array $metadata = []): self
    {
        return self::create([
            'event' => 'deleted',
            'action' => class_basename($model) . ' deleted',
            'module' => self::getModuleFromModel($model),
            'severity' => 'warning',
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'user_id' => $user?->id ?? auth()->id(),
            'user_name' => $user?->name ?? auth()->user()?->name,
            'user_email' => $user?->email ?? auth()->user()?->email,
            'old_values' => $model->toArray(),
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_method' => request()->method(),
            'request_url' => request()->fullUrl(),
            'performed_at' => now(),
        ]);
    }

    /**
     * Get module name from model
     */
    private static function getModuleFromModel(Model $model): string
    {
        $className = class_basename($model);

        // Map model names to module names
        $moduleMap = [
            'Assessment' => 'assessments',
            'Payment' => 'payments',
            'Certificate' => 'certificates',
            'User' => 'users',
            'Assessor' => 'assessors',
            'Tuk' => 'tuk',
            'LspProfile' => 'lsp-profiles',
        ];

        return $moduleMap[$className] ?? strtolower($className);
    }
}
