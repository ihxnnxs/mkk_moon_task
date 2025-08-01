<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
    ];

    /**
     * @return BelongsTo<Activity, $this>
     */
    public function parent() : BelongsTo
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    /**
     * @return HasMany<Activity, $this>
     */
    public function children() : HasMany
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }
}
