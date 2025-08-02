<?php

namespace App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class GetByBuildingDoc extends \OpenApi\Annotations\Get
{
    public function __construct()
    {
        parent::__construct([
            'path' => '/api/organizations/building/{building_id}',
            'operationId' => 'getOrganizationsByBuilding',
            'description' => 'Получить организации по ID здания',
            'summary' => 'Получить организации по зданию',
            'security' => bearerSecurityDoc(),
            'tags' => ['Organizations'],
            'parameters' => [
                new OA\Parameter(
                    name: 'building_id',
                    description: 'ID здания',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer', example: 1)
                )
            ],
            'responses' => [
                new OA\Response(
                    response: Response::HTTP_OK,
                    description: 'Успешный ответ',
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(properties: organizationPropertiesDoc())
                    )
                ),
                unauthorizedResponseDoc()
            ]
        ]);
    }
}
