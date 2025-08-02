<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\SwaggerDocs\Api\Auth\TokenControllerDoc\GetTokenDoc;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Auth',
    description: 'Получение токена для API'
)]
class TokenController extends Controller
{
    #[GetTokenDoc]
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
