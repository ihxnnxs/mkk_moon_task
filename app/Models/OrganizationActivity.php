<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationActivity extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationActivityFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'activity_id',
    ];

    protected $table = 'organization_activities';

    public function organization() : belongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function activity() : belongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
