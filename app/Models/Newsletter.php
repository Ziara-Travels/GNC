<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'weekly_emails',
        'monthly_emails',
        'promotional_emails',
        'is_subscribed',
        'source',
        'hubspot_contact_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'weekly_emails' => 'boolean',
        'monthly_emails' => 'boolean',
        'promotional_emails' => 'boolean',
        'is_subscribed' => 'boolean',
    ];
}
