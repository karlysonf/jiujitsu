<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class Plan extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
