<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'openai_api_key',
        'groq_api_keys',
    ];

    protected $casts = [
        'groq_api_keys' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
