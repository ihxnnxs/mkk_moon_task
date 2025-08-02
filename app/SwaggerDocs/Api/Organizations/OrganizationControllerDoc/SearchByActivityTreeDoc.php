<?php

namespace App\SwaggerDocs\Api\Organizations\OrganizationControllerDoc;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class SearchByActivityTreeDoc extends \OpenApi\Annotations\Post
{
    public function __construct()
    {
        parent::__construct([
            'path' => '/api/organizations/search/activity/tree',
            'operationId' => 'searchOrganizationsByActivityTree',
            'description' => 'Поиск организаций по дереву деятельности',
            'summary' => 'Поиск организаций по дереву деятельности',
            'security' => bearerSecurityDoc(),
            'tags' => ['Organizations'],
            'requestBody' => new OA\RequestBody(
                request: 'searchByActivityTree',
                required: true,
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['activity_id'],
                        properties: [
                            new OA\Property(
                                property: 'activity_id',
                                description: 'ID родительского вида деятельности',
                                type: 'integer',
                                example: 1
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
