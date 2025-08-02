<?php

namespace App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ShowDoc extends \OpenApi\Annotations\Get
{
    public function __construct()
    {
        parent::__construct([
            'path' => '/api/organizations/{id}',
            'operationId' => 'getOrganizationById',
            'description' => 'Получить организацию по ID',
            'summary' => 'Показать организацию',
            'security' => bearerSecurityDoc(),
            'tags' => ['Organizations'],
            'parameters' => [
                new OA\Parameter(
                    name: 'id',
                    description: 'ID организации',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer', example: 1)
                )
            ],
            'responses' => [
                new OA\Response(
                    response: Response::HTTP_OK,
                    description: 'Успешный ответ',
                    content: new OA\JsonContent(properties: organizationPropertiesDoc())
                ),
                notFoundResponseDoc(),
                unauthorizedResponseDoc()
            ]
        ]);
    }
}
