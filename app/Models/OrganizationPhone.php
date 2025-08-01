<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationPhone extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationPhoneFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'phone',
    ];

    public function organization() : belongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
