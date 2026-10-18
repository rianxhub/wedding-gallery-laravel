<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'meta',
        'is_read',
    ];

    protected $casts = [
        'meta'    => 'array',
        'is_read' => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopeUploads($q)  { return $q->where('action', 'upload'); }
    public function scopeEdits($q)    { return $q->where('action', 'edit');   }
    public function scopeDeletes($q)  { return $q->where('action', 'delete'); }
    public function scopeUnread($q)   { return $q->where('is_read', false);   }

    // ─── Helpers ─────────────────────────────────────────────────

    /**
     * Log an activity quickly.
     */
    public static function log(
        string $action,
        string $description,
        string $subjectType = null,
        int    $subjectId   = null,
        array  $meta        = []
    ): self {
        return self::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'description'  => $description,
            'meta'         => $meta ?: null,
        ]);
    }

    /**
     * Icon for each action type.
     */
    public function actionIcon(): string
    {
        return match($this->action) {
            'upload' => '⬆️',
            'edit'   => '✏️',
            'delete' => '🗑️',
            default  => '🔔',
        };
    }

    /**
     * Color class for each action type.
     */
    public function actionColor(): string
    {
        return match($this->action) {
            'upload' => 'text-emerald-700 bg-emerald-50',
            'edit'   => 'text-blue-700 bg-blue-50',
            'delete' => 'text-rose-700 bg-rose-50',
            default  => 'text-slate-700 bg-slate-100',
        };
    }

    /**
     * Human time diff.
     */
    public function timeAgo(): string
    {
        return $this->created_at->diffForHumans();
    }
}
