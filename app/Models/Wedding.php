<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wedding extends Model
{
    protected $fillable = [
        'user_id',
        'couple_name',
        'slug',
        'wedding_date',
        'drive_folder_id',
        'cover_drive_file_id',
        'quote',
        'theme',
        'is_published',
        'pin',
        'pin_enabled',
        'guest_download',
        'cover_image',
        'photo_sort_mode',
    ];

    protected function casts(): array
    {
        return [
            'wedding_date'   => 'date',
            'is_published'   => 'boolean',
            'pin_enabled'    => 'boolean',
            'guest_download' => 'boolean',
        ];
    }

    /**
     * Konfigurasi tema dinamis untuk Tailwind CSS
     */
    public function getThemeConfigAttribute(): array
    {
        $rawTheme = trim((string) ($this->theme ?? 'Elegance Emerald'));

        $key = match ($rawTheme) {
            'Warm Minimalist' => 'warm_minimalist',
            'Modern Classic', 'Classic Luxury' => 'modern_classic',
            'Sunset Gold', 'Royal Elegant' => 'sunset_gold',
            default => 'elegance_emerald',
        };

        return match ($key) {
            'warm_minimalist' => [
                'key'            => 'warm_minimalist',
                'name'           => 'Warm Minimalist',
                'desc'           => 'Earth, travertine stone',
                // Warna dasar
                'body_bg'        => 'bg-[#F7F5F0]',
                'body_text'      => 'text-[#3E2F28]',
                'heading_text'   => 'text-[#2C1F18]',
                'subtext'        => 'text-[#7A6A60]',
                'accent_text'    => 'text-[#8C6D58]',
                // Kartu & Modal
                'card_bg'        => 'bg-white/90',
                'card_border'    => 'border-[#E6E0D5]',
                'card_shadow'    => 'shadow-sm shadow-stone-300/40',
                // Tombol
                'primary_btn'    => 'bg-[#8C6D58] hover:bg-[#725441] text-white shadow-[#8C6D58]/20',
                'secondary_btn'  => 'bg-[#ECE7DF] hover:bg-[#DFD8CB] text-[#3E2F28] border border-[#DDD6C8]',
                'accent_btn'     => 'bg-[#8C6D58] hover:bg-[#725441] text-white',
                // Input
                'input_bg'       => 'bg-[#F2ECE3] border-[#DDD5C9] text-[#3E2F28] focus:ring-[#8C6D58] focus:border-[#8C6D58]',
                'pin_box'        => 'bg-white border-[#DDD5C9] text-[#3E2F28] focus:ring-[#8C6D58]',
                // Badges & Navigation
                'badge'          => 'bg-[#ECE7DF] text-[#5A4032] border border-[#DDD6C8]',
                'navbar_bg'      => 'bg-[#F7F5F0]/90 border-b border-[#E6E0D5]',
                'hero_overlay'   => 'bg-gradient-to-t from-[#F7F5F0] via-[#F7F5F0]/75 to-transparent',
                'photo_card_bg'  => 'bg-white border-[#E6E0D5]',
                'ring_color'     => 'focus:ring-[#8C6D58]',
                'is_dark'        => false,
            ],

            'modern_classic' => [
                'key'            => 'modern_classic',
                'name'           => 'Modern Classic',
                'desc'           => 'Editorial monochrome',
                // Warna dasar
                'body_bg'        => 'bg-[#0E0F12]',
                'body_text'      => 'text-[#F3F4F6]',
                'heading_text'   => 'text-white',
                'subtext'        => 'text-[#9CA3AF]',
                'accent_text'    => 'text-slate-200',
                // Kartu & Modal
                'card_bg'        => 'bg-[#18191E]/95',
                'card_border'    => 'border-zinc-800',
                'card_shadow'    => 'shadow-xl shadow-black/60',
                // Tombol
                'primary_btn'    => 'bg-white hover:bg-zinc-200 text-black shadow-lg shadow-white/10 font-bold',
                'secondary_btn'  => 'bg-zinc-800 hover:bg-zinc-700 text-zinc-200 border border-zinc-700',
                'accent_btn'     => 'bg-white hover:bg-zinc-200 text-black font-semibold',
                // Input
                'input_bg'       => 'bg-zinc-900/90 border-zinc-700 text-white focus:ring-white focus:border-white',
                'pin_box'        => 'bg-zinc-900 border-zinc-700 text-white focus:ring-white',
                // Badges & Navigation
                'badge'          => 'bg-zinc-800/90 text-zinc-300 border border-zinc-700',
                'navbar_bg'      => 'bg-[#0E0F12]/90 border-b border-zinc-800',
                'hero_overlay'   => 'bg-gradient-to-t from-[#0E0F12] via-[#0E0F12]/80 to-transparent',
                'photo_card_bg'  => 'bg-[#18191E] border-zinc-800',
                'ring_color'     => 'focus:ring-white',
                'is_dark'        => true,
            ],

            'sunset_gold' => [
                'key'            => 'sunset_gold',
                'name'           => 'Sunset Gold',
                'desc'           => 'Warm amber hour',
                // Warna dasar
                'body_bg'        => 'bg-[#FFF9F2]',
                'body_text'      => 'text-[#2D2114]',
                'heading_text'   => 'text-[#23170B]',
                'subtext'        => 'text-[#826950]',
                'accent_text'    => 'text-[#D97706]',
                // Kartu & Modal
                'card_bg'        => 'bg-white/95',
                'card_border'    => 'border-amber-200/80',
                'card_shadow'    => 'shadow-lg shadow-amber-500/10',
                // Tombol
                'primary_btn'    => 'bg-[#D97706] hover:bg-[#B45309] text-white shadow-lg shadow-amber-500/25 font-bold',
                'secondary_btn'  => 'bg-amber-100 hover:bg-amber-200 text-[#78350F] border border-amber-300/50',
                'accent_btn'     => 'bg-[#D97706] hover:bg-[#B45309] text-white',
                // Input
                'input_bg'       => 'bg-[#FFF4E5] border-amber-200 text-[#2D2114] focus:ring-amber-500 focus:border-amber-500',
                'pin_box'        => 'bg-white border-amber-300 text-[#2D2114] focus:ring-amber-500',
                // Badges & Navigation
                'badge'          => 'bg-amber-100 text-[#92400E] border border-amber-300/60',
                'navbar_bg'      => 'bg-[#FFF9F2]/90 border-b border-amber-200/70',
                'hero_overlay'   => 'bg-gradient-to-t from-[#FFF9F2] via-[#FFF9F2]/75 to-transparent',
                'photo_card_bg'  => 'bg-white border-amber-200/70',
                'ring_color'     => 'focus:ring-amber-500',
                'is_dark'        => false,
            ],

            default => [ // elegance_emerald
                'key'            => 'elegance_emerald',
                'name'           => 'Elegance Emerald',
                'desc'           => 'Deep botanical slate',
                // Warna dasar
                'body_bg'        => 'bg-[#09151A]',
                'body_text'      => 'text-slate-100',
                'heading_text'   => 'text-white',
                'subtext'        => 'text-slate-300',
                'accent_text'    => 'text-emerald-400',
                // Kartu & Modal
                'card_bg'        => 'bg-[#0E2028]/90',
                'card_border'    => 'border-emerald-900/50',
                'card_shadow'    => 'shadow-2xl shadow-black/60',
                // Tombol
                'primary_btn'    => 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-950/60 font-bold',
                'secondary_btn'  => 'bg-emerald-950/80 hover:bg-emerald-900 text-emerald-200 border border-emerald-800/50',
                'accent_btn'     => 'bg-emerald-600 hover:bg-emerald-500 text-white',
                // Input
                'input_bg'       => 'bg-[#060E12] border-emerald-900/60 text-white focus:ring-emerald-500 focus:border-emerald-500',
                'pin_box'        => 'bg-[#0A1820] border-emerald-800 text-white focus:ring-emerald-500',
                // Badges & Navigation
                'badge'          => 'bg-emerald-950/90 text-emerald-300 border border-emerald-800/60',
                'navbar_bg'      => 'bg-[#09151A]/90 border-b border-emerald-950',
                'hero_overlay'   => 'bg-gradient-to-t from-[#09151A] via-[#09151A]/80 to-transparent',
                'photo_card_bg'  => 'bg-[#0D1D24] border-emerald-900/40',
                'ring_color'     => 'focus:ring-emerald-500',
                'is_dark'        => true,
            ],
        };
    }

    /**
     * URL Foto Hero Cover (dari cover_drive_file_id atau fallback)
     */
    public function getCoverPhotoUrlAttribute(): string
    {
        if (!empty($this->cover_drive_file_id)) {
            $id = trim($this->cover_drive_file_id);
            if (str_starts_with($id, 'http://') || str_starts_with($id, 'https://')) {
                return $id;
            }
            // Link langsung pratinjau Google Drive resolusi tinggi
            return "https://lh3.googleusercontent.com/d/{$id}=w1920";
        }

        if (!empty($this->cover_image)) {
            $cov = trim($this->cover_image);
            if (str_starts_with($cov, 'http://') || str_starts_with($cov, 'https://')) {
                return $cov;
            }
            return "https://lh3.googleusercontent.com/d/{$cov}=w1920";
        }

        if ($this->relationLoaded('photos') && $this->photos->count() > 0) {
            $first = $this->photos->first();
            if (!empty($first->thumbnail_url)) {
                return $first->thumbnail_url;
            }
        }

        return 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1920&q=80';
    }

    /**
     * Relasi ke User pengelola (ERD: USERS mengelola WEDDINGS)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function getPhotosAttribute($value)
    {
        if ($this->relationLoaded('photos')) {
            return $this->getRelation('photos');
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('photos')) {
            return $this->getRelationValue('photos');
        }

        return collect();
    }

    public function trashedPhotos(): HasMany
    {
        return $this->hasMany(Photo::class)->onlyTrashed();
    }

    public function getTrashedPhotosAttribute($value)
    {
        if ($this->relationLoaded('trashedPhotos')) {
            return $this->getRelation('trashedPhotos');
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('photos')) {
            return $this->getRelationValue('trashedPhotos');
        }

        return collect();
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class);
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }

    public function clientVisitors(): HasMany
    {
        return $this->hasMany(ClientVisitor::class);
    }

    /**
     * Generate the public client gallery URL for this wedding
     */
    public function getClientUrlAttribute(): string
    {
        return route('client.pin', $this->slug);
    }

    public function getTotalPhotosSizeAttribute(): int
    {
        return (int) $this->photos()->whereNull('deleted_at')->sum('file_size');
    }

    public function getFormattedTotalSizeAttribute(): string
    {
        $bytes = $this->total_photos_size;
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = min(4, floor(log($bytes, 1024)));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }
}