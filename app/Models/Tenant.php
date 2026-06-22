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
    ];

    protected $casts = [
        'asaas_api_key' => 'encrypted',
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
}
