<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wish extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'nama_tamu',
        'ucapan',
        'kehadiran',
    ];

    /**
     * Relasi ke model Wedding
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }
}
