<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'birth_date',
        'gender',
        'profile_image_url',
        'header_image_url',
        'shelter_id',
        'rescue_date',
        'breed',
        'estimated_age',
        'age_years',
        'age_months',
        'profile_description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function shareLinks(): HasMany
    {
        return $this->hasMany(PetShareLink::class);
    }

    /**
     * このペットがペット1として参加している家族リンク
     */
    public function familyLinksAsPet1(): HasMany
    {
        return $this->hasMany(PetFamilyLink::class, 'pet1_id');
    }

    /**
     * このペットがペット2として参加している家族リンク
     */
    public function familyLinksAsPet2(): HasMany
    {
        return $this->hasMany(PetFamilyLink::class, 'pet2_id');
    }

    /**
     * このペットの家族リンク（両方向）
     */
    public function familyLinks()
    {
        return $this->familyLinksAsPet1->merge($this->familyLinksAsPet2);
    }
}
