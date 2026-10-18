<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DownloadLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'wedding_id',
        'drive_file_id',
        'file_name',
        'ip_address',
        'downloaded_at',
    ];

    protected function casts(): array
    {
        return [
            'downloaded_at' => 'datetime',
        ];
    }

    /**
     * Otomatis mengisi downloaded_at dengan waktu realtime (Asia/Jakarta) saat dicatat.
     */
    protected static function booted(): void
    {
        static::creating(function (DownloadLog $log) {
            if (empty($log->downloaded_at)) {
                $log->downloaded_at = now();
            }
        });
    }

    /**
     * Format tanggal dan jam lengkap dengan WIB.
     */
    public function getDownloadedAtFormattedAttribute(): string
    {
        if (!$this->downloaded_at) {
            return '-';
        }

        return $this->downloaded_at->timezone(config('app.timezone', 'Asia/Jakarta'))
            ->translatedFormat('d M Y, H:i:s') . ' WIB';
    }

    /**
     * Format waktu relatif (misal: "Baru saja", "5 menit yang lalu").
     */
    public function getDownloadedAtHumanAttribute(): string
    {
        if (!$this->downloaded_at) {
            return '-';
        }

        return $this->downloaded_at->timezone(config('app.timezone', 'Asia/Jakarta'))
            ->diffForHumans();
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }
}
