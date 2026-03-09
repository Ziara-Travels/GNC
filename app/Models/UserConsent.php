<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserConsent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'consent_type',
        'consent_given',
        'consent_text',
        'ip_address',
        'user_agent',
        'version',
        'consented_at',
        'withdrawn_at'
    ];

    protected $casts = [
        'consent_given' => 'boolean',
        'consented_at' => 'datetime',
        'withdrawn_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return $this->consent_given && !$this->withdrawn_at;
    }

    public static function getConsentTypes()
    {
        return [
            'essential' => 'Essential Cookies',
            'functional' => 'Functional Cookies',
            'analytics' => 'Analytics Cookies',
            'marketing' => 'Marketing Communications',
            'data_processing' => 'Data Processing',
            'third_party' => 'Third Party Sharing'
        ];
    }
}