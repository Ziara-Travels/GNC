<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoFactorAuthentication extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'secret_key',
        'backup_codes',
        'enabled',
    ];

    protected $casts = [
        'backup_codes' => 'array',
        'enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
