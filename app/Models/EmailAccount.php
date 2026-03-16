<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAccount extends Model
{
    protected $fillable = [
        'user_id', 'team_id', 'email_address', 'provider',
        'imap_host', 'imap_port', 'imap_encryption',
        'smtp_host', 'smtp_port', 'smtp_encryption',
        'username', 'password', 'is_active'
    ];

    protected $casts = [
        'password' => 'encrypted',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
