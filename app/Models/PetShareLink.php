<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PetShareLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'share_token',
        'title',
        'description',
        'is_active',
        'expires_at',
        'view_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::random(32);
        } while (static::where('share_token', $token)->exists());

        return $token;
    }

    public function getShareUrl(): string
    {
        return route('pets.share', $this->share_token);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
