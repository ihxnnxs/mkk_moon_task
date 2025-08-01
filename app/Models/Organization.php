<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'building_id',
        'name',
    ];

    /**
     * @return BelongsTo<Building, $this>
     */
    public function building() : BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * @return HasMany<OrganizationPhone, $this>
     */
    public function phones() : HasMany
    {
        return $this->hasMany(OrganizationPhone::class);
    }

    /**
     * @return BelongsToMany<Activity, $this>
     */
    public function activities() : BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'organization_activities');
    }

}
