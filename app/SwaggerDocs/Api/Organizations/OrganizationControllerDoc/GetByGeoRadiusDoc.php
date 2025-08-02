<?php

namespace App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class GetByGeoRadiusDoc extends \OpenApi\Annotations\Post
{
    public function __construct()
    {
        parent::__construct([
            'path' => '/api/organizations/search/geo/radius',
            'operationId' => 'getOrganizationsByGeoRadius',
            'description' => 'Поиск организаций в радиусе от точки',
            'summary' => 'Поиск организаций по геолокации (радиус)',
            'security' => bearerSecurityDoc(),
            'tags' => ['Organizations'],
            'requestBody' => new OA\RequestBody(
                request: 'getByGeoRadius',
                required: true,
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['latitude', 'longitude', 'radius'],
                        properties: [
                            new OA\Property(
                                property: 'latitude',
                                type: 'number',
                                format: 'float',
                                example: 55.7558
                            ),
                            new OA\Property(
                                property: 'longitude',
                                type: 'number',
                                format: 'float',
                                example: 37.6176
                            ),
                            new OA\Property(
                                property: 'radius',
                                description: 'Радиус поиска в метрах',
                                type: 'number',
                                format: 'float',
                                example: 1000
                            )
                        ]
                    )
                )
            ),
            'responses' => [
                new OA\Response(
                    response: Response::HTTP_OK,
                    description: 'Успешный ответ',
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(properties: organizationPropertiesDoc())
                    )
                ),
                validationErrorResponseDoc(),
                unauthorizedResponseDoc()
            ]
        ]);
    }
}
