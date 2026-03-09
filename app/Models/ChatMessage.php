<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'role',
        'message',
        'timestamp',
        'metadata'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'metadata' => 'array'
    ];

    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'session_id');
    }
}
