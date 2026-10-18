<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Photo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wedding_id',
        'album_id',
        'drive_file_id',
        'nama_file',
        'mime_type',
        'file_size',
        'thumbnail_url',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int) ($this->file_size ?? 0);
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = min(4, floor(log($bytes, 1024)));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }

    /**
     * Memastikan kolom deleted_at otomatis tersedia di database jika belum termigrasi.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('ensure_soft_deletes_column', function () {
            static $checked = false;
            if (!$checked) {
                $checked = true;
                try {
                    if (Schema::hasTable('photos') && !Schema::hasColumn('photos', 'deleted_at')) {
                        Schema::table('photos', function ($table) {
                            $table->softDeletes()->after('thumbnail_url');
                        });
                    }
                } catch (\Throwable $e) {
                    // Abaikan jika sudah ada atau race condition
                }
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}