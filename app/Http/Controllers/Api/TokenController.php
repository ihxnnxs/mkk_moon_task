<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Auth',
    description: 'Получение токена для API'
)]
class TokenController extends Controller
{
    #[OA\Get(
        path: '/api/token',
        operationId: 'getToken',
        description: 'Получить токен для доступа к API',
        summary: 'Получить токен',
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Токен успешно создан',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: '1|abcdef123456789'),
                        new OA\Property(property: 'type', type: 'string', example: 'Bearer')
                    ]
                )
            )
        ]
    )]
    public function getToken(): JsonResponse
    {
        $apiUser = User::firstOrCreate([
            'email' => 'api@app.local'
        ], [
            'name' => 'API User',
            'password' => bcrypt('secret')
        ]);

        $token = $apiUser->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'type' => 'Bearer'
        ]);
    }
}
