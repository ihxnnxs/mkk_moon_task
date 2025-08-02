<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Building',
    title: 'Building',
    required: ['id', 'address', 'latitude', 'longitude'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'address', type: 'string', example: '123 Main St'),
        new OA\Property(property: 'latitude', type: 'number', format: 'float', example: 40.7128),
        new OA\Property(property: 'longitude', type: 'number', format: 'float', example: -74.0060),
    ],
    type: 'object'
)]
#[OA\Get(
    path: '/api/buildings',
    operationId: 'getBuildingsList',
    description: 'Возвращает список всех зданий',
    summary: 'Получить список зданий',
    security: [['bearerAuth' => []]],
    tags: ['Buildings'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Успешный ответ',
            content: new OA\JsonContent(
                type: 'array',
                items: new OA\Items(ref: '#/components/schemas/Building')
            )
        )
    ]
)]
class BuildingController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $buildings = Building::query()
            ->select(['id', 'address', 'latitude', 'longitude'])
            ->get();

        return BuildingResource::collection($buildings);
    }
}
