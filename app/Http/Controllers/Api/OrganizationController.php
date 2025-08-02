<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivitySearchRequest;
use App\Http\Requests\Api\GeoRadiusSearchRequest;
use App\Http\Requests\Api\GeoRectangleSearchRequest;
use App\Http\Requests\Api\GeoSearchRequest;
use App\Http\Requests\Api\OrganizationSearchRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Organization',
    title: 'Organization',
    required: ['id', 'name'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Организация "Пример"'),
        new OA\Property(property: 'building_id', type: 'integer', example: 1),
        new OA\Property(property: 'description', type: 'string', example: 'Описание организации'),
        new OA\Property(property: 'building', ref: '#/components/schemas/Building'),
        new OA\Property(
            property: 'phones',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'number', type: 'string', example: '+7 (999) 123-45-67'),
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
    #[OA\Get(
        path: '/api/organizations/{id}',
        operationId: 'getOrganizationById',
        description: 'Получить организацию по ID',
        summary: 'Показать организацию',
        security: [['bearerAuth' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID организации',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(ref: '#/components/schemas/Organization')
            ),
            new OA\Response(
                response: 404,
                description: 'Организация не найдена',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Organization] 1')
                    ]
                )
            )
        ]
    )]
    public function show(int $id): OrganizationResource
    {
        $organization = Organization::query()
            ->with(['building', 'phones', 'activities'])
            ->findOrFail($id);

        return new OrganizationResource($organization);
    }

    #[OA\Get(
        path: '/api/organizations/building/{buildingId}',
        operationId: 'getOrganizationsByBuilding',
        description: 'Получить организации в здании',
        summary: 'Организации по зданию',
        security: [['bearerAuth' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\Parameter(
                name: 'buildingId',
                description: 'ID здания',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Organization')
                )
            )
        ]
    )]
    public function getByBuilding(int $buildingId): AnonymousResourceCollection
    {
        $organizations = Organization::query()
            ->where(['building_id' => $buildingId])
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    #[OA\Get(
        path: '/api/organizations/activity/{activityId}',
        operationId: 'getOrganizationsByActivity',
        description: 'Получить организации по виду деятельности',
        summary: 'Организации по деятельности',
        security: [['bearerAuth' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\Parameter(
                name: 'activityId',
                description: 'ID вида деятельности',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Organization')
                )
            )
        ]
    )]
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

    #[OA\Post(
        path: '/api/organizations/search/activity/tree',
        operationId: 'searchOrganizationsByActivityTree',
        description: 'Поиск организаций по дереву видов деятельности (включая дочерние)',
        summary: 'Поиск по дереву деятельности',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['activity_id'],
                properties: [
                    new OA\Property(property: 'activity_id', description: 'ID родительского вида деятельности', type: 'integer', example: 1)
                ]
            )
        ),
        tags: ['Organizations'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Organization')
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(
                            property: 'errors',
                            properties: [
                                new OA\Property(property: 'activity_id', type: 'array', items: new OA\Items(type: 'string'))
                            ],
                            type: 'object'
                        )
                    ]
                )
            )
        ]
    )]
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

    #[OA\Get(
        path: '/api/organizations/search/name',
        operationId: 'searchOrganizationsByName',
        description: 'Поиск организаций по названию',
        summary: 'Поиск по названию',
        security: [['bearerAuth' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\Parameter(
                name: 'name',
                description: 'Часть названия организации',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'медицина')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Organization')
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(
                            property: 'errors',
                            properties: [
                                new OA\Property(property: 'name', type: 'array', items: new OA\Items(type: 'string'))
                            ],
                            type: 'object'
                        )
                    ]
                )
            )
        ]
    )]
    public function searchByName(OrganizationSearchRequest $request): AnonymousResourceCollection
    {
        $name = $request->validated()['name'];

        $organizations = Organization::query()
            ->where('name', 'LIKE', "%{$name}%")
            ->with(['building', 'phones', 'activities'])
            ->get();

        return OrganizationResource::collection($organizations);
    }

    #[OA\Post(
        path: '/api/organizations/search/geo/radius',
        operationId: 'getOrganizationsByGeoRadius',
        description: 'Поиск организаций в радиусе от точки',
        summary: 'Поиск по радиусу',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['latitude', 'longitude', 'radius'],
                properties: [
                    new OA\Property(property: 'latitude', description: 'Широта центра поиска', type: 'number', format: 'float', example: 55.7558),
                    new OA\Property(property: 'longitude', description: 'Долгота центра поиска', type: 'number', format: 'float', example: 37.6176),
                    new OA\Property(property: 'radius', description: 'Радиус поиска в километрах', type: 'number', format: 'float', example: 5.0)
                ]
            )
        ),
        tags: ['Organizations'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Organization')
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(
                            property: 'errors',
                            properties: [
                                new OA\Property(property: 'latitude', type: 'array', items: new OA\Items(type: 'string')),
                                new OA\Property(property: 'longitude', type: 'array', items: new OA\Items(type: 'string')),
                                new OA\Property(property: 'radius', type: 'array', items: new OA\Items(type: 'string'))
                            ],
                            type: 'object'
                        )
                    ]
                )
            )
        ]
    )]
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

    #[OA\Post(
        path: '/api/organizations/search/geo/rectangle',
        operationId: 'getOrganizationsByGeoRectangle',
        description: 'Поиск организаций в прямоугольной области',
        summary: 'Поиск по прямоугольнику',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['min_lat', 'max_lat', 'min_lng', 'max_lng'],
                properties: [
                    new OA\Property(property: 'min_lat', description: 'Минимальная широта', type: 'number', format: 'float', example: 55.5),
                    new OA\Property(property: 'max_lat', description: 'Максимальная широта', type: 'number', format: 'float', example: 56.0),
                    new OA\Property(property: 'min_lng', description: 'Минимальная долгота', type: 'number', format: 'float', example: 37.0),
                    new OA\Property(property: 'max_lng', description: 'Максимальная долгота', type: 'number', format: 'float', example: 38.0)
                ]
            )
        ),
        tags: ['Organizations'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Organization')
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(
                            property: 'errors',
                            properties: [
                                new OA\Property(property: 'min_lat', type: 'array', items: new OA\Items(type: 'string')),
                                new OA\Property(property: 'max_lat', type: 'array', items: new OA\Items(type: 'string')),
                                new OA\Property(property: 'min_lng', type: 'array', items: new OA\Items(type: 'string')),
                                new OA\Property(property: 'max_lng', type: 'array', items: new OA\Items(type: 'string'))
                            ],
                            type: 'object'
                        )
                    ]
                )
            )
        ]
    )]
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
