<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivitySearchRequest;
use App\Http\Requests\Api\GeoRadiusSearchRequest;
use App\Http\Requests\Api\GeoRectangleSearchRequest;
use App\Http\Requests\Api\OrganizationSearchRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Activity;
use App\Models\Organization;
use App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc\GetByActivityDoc;
use App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc\GetByBuildingDoc;
use App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc\GetByGeoRadiusDoc;
use App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc\GetByGeoRectangleDoc;
use App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc\SearchByActivityTreeDoc;
use App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc\SearchByNameDoc;
use App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc\ShowDoc;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Organization',
    title: 'Organization',
    required: ['id', 'name'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Организация "Пример"'),
        new OA\Property(property: 'building', ref: '#/components/schemas/Building'),
        new OA\Property(
            property: 'phones',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'phone', type: 'string', example: '+7 (999) 123-45-67'),
                ],
                type: 'object'
            )
        ),
        new OA\Property(
            property: 'activities',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'parent_id', type: 'integer', example: null, nullable: true),
                    new OA\Property(property: 'name', type: 'string', example: 'Медицинские услуги'),
                ],
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class OrganizationController extends Controller
{
    #[ShowDoc]
    public function show(int $id): OrganizationResource
    {
        $organization = Organization::query()
            ->with(['building', 'phones', 'activities'])
            ->findOrFail($id);

        return new OrganizationResource($organization);
    }

    #[GetByBuildingDoc]
    public function getByBuilding(int $buildingId): AnonymousResourceCollection
    {
        $organizations = Organization::query()
            ->where(['building_id' => $buildingId])
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    #[GetByActivityDoc]
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

    #[SearchByActivityTreeDoc]
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

    #[SearchByNameDoc]
    public function searchByName(OrganizationSearchRequest $request): AnonymousResourceCollection
    {
        $name = $request->validated()['name'];

        $organizations = Organization::query()
            ->where('name', 'LIKE', "%{$name}%")
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    #[GetByGeoRadiusDoc]
    public function getByGeoRadius(GeoRadiusSearchRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $lat = $validated['latitude'];
        $lng = $validated['longitude'];
        $radius = $validated['radius'];

        $organizations = Organization::query()
            ->whereHas('building', function ($query) use ($lat, $lng, $radius) {
                $query->whereRaw("
                (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) <= ?
            ", [$lat, $lng, $lat, $radius]);
            })
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    #[GetByGeoRectangleDoc]
    public function getByGeoRectangle(GeoRectangleSearchRequest $request): AnonymousResourceCollection
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
