<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subdomain',
        'domain',
        'logo',
        'primary_color',
        'secondary_color',
        'asaas_api_key',
        'asaas_environment',
        'status',
        'plan_tier',
        'max_users',
        'expires_at',
    ];

    protected $casts = [
        'asaas_api_key' => 'encrypted',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the active tenant bound to the application container.
     */
    public static function current(): ?self
    {
        if (app()->bound('currentTenant')) {
            return app('currentTenant');
        }

        return null;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * Get the count of active students, teachers, and instructors.
     */
    public function getActiveUsersCount(): int
    {
        return $this->users()
            ->withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->where('status', 'active')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['aluno', 'professor', 'instrutor']);
            })
            ->count();
    }

    /**
     * Check if the tenant has reached the active users limit.
     */
    public function hasReachedUserLimit(): bool
    {
        if (is_null($this->max_users)) {
            return false;
        }

        return $this->getActiveUsersCount() >= $this->max_users;
    }
}
