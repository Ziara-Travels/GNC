<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Booking;
use App\Models\Permission;
use App\Models\Role;

use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'is_deleted',
        'created_by',
        'phone',
        'country',
        'date_of_birth',
        'profile_picture',
        'updated_by',
        'two_factor_secret',
        'two_factor_enabled',
        'two_factor_backup_codes',
        'last_login_at',
        'email_verified_at',
        'loyalty_points',
        'last_login_at',
        'reengagement_sent',
        'referral_code',
        'referred_by',
        'referral_count',
        'referral_earnings',
        'referral_active',
        'social_id',
        'social_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
         'is_active' => 'boolean',
          'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
        'is_deleted' => 'boolean',
        'reengagement_sent' => 'boolean',
        
    ];

    /**
     * Check if user has any of the given roles
     *
     * @param array|string $roles
     * @return bool
     */
    public function hasAnyRole($roles): bool
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }

        return in_array($this->role, $roles, true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get all bookings for the user
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all notifications for the user
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    /**
     * Get the user's avatar URL
     */
    public function getAvatarUrl()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        
        return null;
    }

    /**
     * Get the user's initials for avatar fallback
     */
    public function getInitials()
    {
        $names = explode(' ', trim($this->name));
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }
        
        return substr($initials, 0, 2); // Return max 2 initials
    }

     /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

     public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function hasPermission($permission)
    {
        // Check direct user permissions
        if ($this->permissions()->where('name', $permission)->exists()) {
            return true;
        }

        // Check role-based permissions
        $rolePermissions = \DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role', $this->role)
            ->where('permissions.name', $permission)
            ->exists();

        return $rolePermissions;
    }

    public function hasAnyPermission($permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function getRolePermissions()
    {
        return \DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role', $this->role)
            ->select('permissions.*')
            ->get();
    }

    public function canDeleteUser($targetUser)
    {
        // Super admins can delete anyone except their creator
        if ($this->role === 'superadmin') {
            return $targetUser->created_by !== $this->id && $targetUser->id !== $this->created_by;
        }

        // Admins can delete only users they created
        if ($this->role === 'admin') {
            return $targetUser->created_by === $this->id && $targetUser->role === 'user';
        }

        return false;
    }

    // Relationships
    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'user_permissions');
    // }

    // public function bookings()
    // {
    //     return $this->hasMany(Booking::class);
    // }

    // public function createdUsers()
    // {
    //     return $this->hasMany(User::class, 'created_by');
    // }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Permission methods
    // public function hasPermission($permissionName)
    // {
    //     return $this->permissions()->where('name', $permissionName)->exists();
    // }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function isActive()
    {
        return $this->is_active;
    }

    // Scope methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Default permissions for new users
    public function assignDefaultPermissions()
    {
        $defaultPermissions = [
            'dashboard.view',
            'flights.search',
            'flights.view',
            'flights.book',
            'bookings.view_own',
            'bookings.update_own',
            'profile.view',
            'profile.update'
        ];

        $permissions = Permission::whereIn('name', $defaultPermissions)->get();
        $this->permissions()->sync($permissions);
    }

     public function twoFactorAuth()
    {
        return $this->hasOne(TwoFactorAuthentication::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    public function impersonationLogs()
    {
        return $this->hasMany(ImpersonationLog::class);
    }

    /**
     * Enable 2FA for user
     */
    public function enableTwoFactor()
    {
        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();
        $backupCodes = $this->generateBackupCodes();

        $this->twoFactorAuth()->updateOrCreate(
            ['user_id' => $this->id],
            [
                'secret_key' => Crypt::encrypt($secretKey),
                'backup_codes' => $backupCodes,
                'enabled' => true,
            ]
        );

        return $secretKey;
    }

    /**
     * Disable 2FA for user
     */
    public function disableTwoFactor()
    {
        $this->twoFactorAuth()->delete();
    }

    /**
     * Check if user has 2FA enabled
     */
    public function hasTwoFactorEnabled()
    {
        return $this->twoFactorAuth && $this->twoFactorAuth->enabled;
    }

    /**
     * Get decrypted secret key
     */
    public function getTwoFactorSecret()
    {
        if ($this->twoFactorAuth) {
            return Crypt::decrypt($this->twoFactorAuth->secret_key);
        }
        return null;
    }

    /**
     * Generate backup codes
     */
    private function generateBackupCodes()
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = strtoupper(str_random(8));
        }
        return $codes;
    }

    /**
     * Use backup code
     */
    public function useBackupCode($code)
    {
        if ($this->twoFactorAuth) {
            $codes = $this->twoFactorAuth->backup_codes;
            if (in_array($code, $codes)) {
                $codes = array_filter($codes, function($c) use ($code) {
                    return $c !== $code;
                });
                $this->twoFactorAuth->update(['backup_codes' => array_values($codes)]);
                return true;
            }
        }
        return false;
    }

    // Add loyalty points relationship or methods if needed


public function getTotalBookingsAttribute()
{
    return $this->bookings()->count();
}

public function getLoyaltyTierAttribute()
{
    if ($this->loyalty_points >= 10000) {
        return 'Platinum';
    } elseif ($this->loyalty_points >= 5000) {
        return 'Gold';
    } elseif ($this->loyalty_points >= 1000) {
        return 'Silver';
    }
    
    return 'Bronze';
}
public function referralCodes()
{
    return $this->hasMany(ReferralCode::class);
}

public function referrals()
{
    return $this->hasMany(UserReferral::class, 'referrer_id');
}

public function referredBy()
{
    return $this->belongsTo(User::class, 'referred_by');
}

public function referredUsers()
{
    return $this->hasMany(User::class, 'referred_by');
}

  // Add relationship for created roles (optional)
    public function createdRoles()
    {
        return $this->hasMany(Role::class, 'created_by');
    }
}
