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

    public function building() : belongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function phones() : hasMany
    {
        return $this->hasMany(OrganizationPhone::class);
    }

    public function activities() : belongsToMany
    {
        return $this->belongsToMany(Activity::class, 'organization_activities');
    }

}
