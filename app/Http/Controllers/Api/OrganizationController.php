<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ActivitySearchRequest;
use App\Http\Requests\Api\GeoSearchRequest;
use App\Http\Requests\Api\OrganizationSearchRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrganizationController
{
    public function show(int $id): OrganizationResource
    {
        $organization = Organization::query()
            ->with(['building', 'phones', 'activities'])
            ->findOrFail($id);

        return new OrganizationResource($organization);
    }

    public function getByBuilding(int $buildingId): AnonymousResourceCollection
    {
        $organizations = Organization::query()
            ->where(['building_id' => $buildingId])
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    public function getByActivity(int $activityId): AnonymousResourceCollection
    {
        $organizations = Organization::query()
            ->whereHas('activities', function ($query) use ($activityId) {
                $query->where(['activity_id' => $activityId]);
            })
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    public function searchByActivityTree(ActivitySearchRequest $request): AnonymousResourceCollection
    {
        $activityId = $request->validated()['activity_id'];

        $childrenIds = Activity::query()
            ->where('parent_id', $activityId)
            ->orWhere('parent_id', function ($query) use ($activityId) {
                $query->select('id')
                    ->from('activities')
                    ->where('parent_id', $activityId);
            })
            ->pluck('id')
            ->push($activityId)
            ->toArray();

        $organizations = Organization::query()
            ->whereHas('activities', function ($query) use ($childrenIds) {
                $query->whereIn('activity_id', $childrenIds);
            })->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    public function searchByName(OrganizationSearchRequest $request): AnonymousResourceCollection
    {
        $name = $request->validated()['name'];

        $organizations = Organization::query()
            ->where('name', 'LIKE', "%{$name}%")
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    public function getByGeoRadius(GeoSearchRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $lat = $validated['latitude'];
        $lng = $validated['longitude'];
        $radius = $validated['radius'];

        $organizations = Organization::query()
            ->whereHas('building', function ($query) use ($lat, $lng, $radius) {
                $query->selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
                    ->havingRaw('distance <= ?', [$radius]);
            })
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    public function getByGeoRectangle(GeoSearchRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        $organizations = Organization::query()
            ->whereHas('building', function ($query) use ($validated) {
                $query->whereBetween('latitude', [$validated['min_lat'], $validated['max_lat']])
                    ->whereBetween('longitude', [$validated['min_lng'], $validated['max_lng']]);
            })
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }
}
