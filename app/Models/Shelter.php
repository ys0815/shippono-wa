<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prefecture_id',
        'website_url',
        'area',
        'kind',
    ];

    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
}
