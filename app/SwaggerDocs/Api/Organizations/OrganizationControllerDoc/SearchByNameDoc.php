<?php

namespace App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class SearchByNameDoc extends \OpenApi\Annotations\Get
{
    public function __construct()
    {
        parent::__construct([
            'path' => '/api/organizations/search/name',
            'operationId' => 'searchOrganizationsByName',
            'description' => 'Поиск организаций по названию',
            'summary' => 'Поиск организаций по названию',
            'security' => bearerSecurityDoc(),
            'tags' => ['Organizations'],
            'parameters' => [
                new OA\Parameter(
                    name: 'name',
                    description: 'Название организации для поиска',
                    in: 'query',
                    required: true,
                    schema: new OA\Schema(type: 'string', example: 'Медицинский')
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
                validationErrorResponseDoc(),
                unauthorizedResponseDoc()
            ]
        ]);
    }
}
