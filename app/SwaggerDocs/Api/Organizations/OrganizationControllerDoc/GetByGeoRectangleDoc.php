<?php

namespace App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class GetByGeoRectangleDoc extends \OpenApi\Annotations\Post
{
    public function __construct()
    {
        parent::__construct([
            'path' => '/api/organizations/search/geo/rectangle',
            'operationId' => 'getOrganizationsByGeoRectangle',
            'description' => 'Поиск организаций в прямоугольной области',
            'summary' => 'Поиск организаций по геолокации (прямоугольник)',
            'security' => bearerSecurityDoc(),
            'tags' => ['Organizations'],
            'requestBody' => new OA\RequestBody(
                request: 'getByGeoRectangle',
                required: true,
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['min_lat', 'max_lat', 'min_lng', 'max_lng'],
                        properties: [
                            new OA\Property(
                                property: 'min_lat',
                                description: 'Минимальная широта',
                                type: 'number',
                                format: 'float',
                                example: 55.6558
                            ),
                            new OA\Property(
                                property: 'max_lat',
                                description: 'Максимальная широта',
                                type: 'number',
                                format: 'float',
                                example: 55.8558
                            ),
                            new OA\Property(
                                property: 'min_lng',
                                description: 'Минимальная долгота',
                                type: 'number',
                                format: 'float',
                                example: 37.5176
                            ),
                            new OA\Property(
                                property: 'max_lng',
                                description: 'Максимальная долгота',
                                type: 'number',
                                format: 'float',
                                example: 37.7176
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
