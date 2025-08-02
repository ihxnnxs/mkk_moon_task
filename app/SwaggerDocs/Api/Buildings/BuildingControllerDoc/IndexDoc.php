<?php

namespace App\SwaggerDocs\Api\Buildings\BuildingControllerDoc;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class IndexDoc extends \OpenApi\Annotations\Get
{
    public function __construct()
    {
        parent::__construct([
            'path' => '/api/buildings',
            'operationId' => 'getBuildingsList',
            'description' => 'Возвращает список всех зданий',
            'summary' => 'Получить список зданий',
            'security' => bearerSecurityDoc(),
            'tags' => ['Buildings'],
            'responses' => [
                new OA\Response(
                    response: Response::HTTP_OK,
                    description: 'Успешный ответ',
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(properties: buildingPropertiesDoc())
                    )
                ),
                unauthorizedResponseDoc()
            ]
        ]);
    }
}
