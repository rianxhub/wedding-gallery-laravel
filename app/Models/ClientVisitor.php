<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientVisitor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'wedding_id',
        'name',
        'email',
        'ip_address',
        'session_token',
        'visited_at',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at'        => 'datetime',
            'last_activity_at'  => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ClientVisitor $v) {
            if (empty($v->visited_at)) {
                $v->visited_at = now();
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * Format visited_at untuk tampilan admin
     */
    public function getVisitedAtFormattedAttribute(): string
    {
        if (!$this->visited_at) return '-';
        return $this->visited_at
            ->timezone(config('app.timezone', 'Asia/Jakarta'))
            ->translatedFormat('d M Y, H:i') . ' WIB';
    }
}
