<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    /** @use HasFactory<\Database\Factories\BuildingFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];


    /**
     * @return HasMany<Organization, $this>
     */
    public function organizations() : HasMany
    {
        return $this->hasMany(Organization::class);
    }
}
