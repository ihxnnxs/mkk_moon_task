<?php

namespace App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class GetByActivityDoc extends \OpenApi\Annotations\Get
{
    public function __construct()
    {
        parent::__construct([
            'path' => '/api/organizations/activity/{activity_id}',
            'operationId' => 'getOrganizationsByActivity',
            'description' => 'Получить организации по ID деятельности',
            'summary' => 'Получить организации по деятельности',
            'security' => bearerSecurityDoc(),
            'tags' => ['Organizations'],
            'parameters' => [
                new OA\Parameter(
                    name: 'activity_id',
                    description: 'ID деятельности',
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
