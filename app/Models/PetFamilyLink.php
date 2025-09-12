<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetFamilyLink extends Model
{
    protected $fillable = [
        'user_id',
        'pet1_id',
        'pet2_id',
    ];

    /**
     * ユーザーとのリレーション
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ペット1とのリレーション
     */
    public function pet1(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'pet1_id');
    }

    /**
     * ペット2とのリレーション
     */
    public function pet2(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'pet2_id');
    }
}
