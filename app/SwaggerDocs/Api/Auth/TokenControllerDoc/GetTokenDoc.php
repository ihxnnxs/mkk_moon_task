<?php

namespace App\SwaggerDocs\Api\Auth\TokenControllerDoc;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class GetTokenDoc extends \OpenApi\Annotations\Get
{
    public function __construct()
    {
        parent::__construct([
            'path' => '/api/token',
            'operationId' => 'getToken',
            'description' => 'Получить токен для доступа к API',
            'summary' => 'Получить токен',
            'tags' => ['Auth'],
            'responses' => [
                new OA\Response(
                    response: Response::HTTP_OK,
                    description: 'Токен успешно создан',
                    content: new OA\JsonContent(properties: tokenResponseDoc())
                )
            ]
        ]);
    }
}
