<?php

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('buildingPropertiesDoc')) {
    function buildingPropertiesDoc(): array
    {
        return [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'address', type: 'string', example: '123 Main St'),
            new OA\Property(property: 'latitude', type: 'number', format: 'float', example: 40.7128),
            new OA\Property(property: 'longitude', type: 'number', format: 'float', example: -74.0060),
        ];
    }
}

if (!function_exists('organizationPropertiesDoc')) {
    function organizationPropertiesDoc(): array
    {
        return [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'name', type: 'string', example: 'Организация "Пример"'),
            new OA\Property(property: 'building', properties: buildingPropertiesDoc(), type: 'object'),
            new OA\Property(
                property: 'phones',
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'phone', type: 'string', example: '+7 (999) 123-45-67'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Property(
                property: 'activities',
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'parent_id', type: 'integer', example: null, nullable: true),
                        new OA\Property(property: 'name', type: 'string', example: 'Медицинские услуги'),
                    ],
                    type: 'object'
                )
            ),
        ];
    }
}

if (!function_exists('tokenResponseDoc')) {
    function tokenResponseDoc(): array
    {
        return [
            new OA\Property(property: 'token', type: 'string', example: '1|abcdef123456789'),
            new OA\Property(property: 'type', type: 'string', example: 'Bearer')
        ];
    }
}

if (!function_exists('unauthorizedResponseDoc')) {
    function unauthorizedResponseDoc(): OA\Response
    {
        return new OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'Unauthorized',
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
            ])
        );
    }
}

if (!function_exists('notFoundResponseDoc')) {
    function notFoundResponseDoc(): OA\Response
    {
        return new OA\Response(
            response: Response::HTTP_NOT_FOUND,
            description: 'Not Found',
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'message', type: 'string', example: 'No query results for model'),
            ])
        );
    }
}

if (!function_exists('validationErrorResponseDoc')) {
    function validationErrorResponseDoc(): OA\Response
    {
        return new OA\Response(
            response: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: 'Validation Error',
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                new OA\Property(
                    property: 'errors',
                    type: 'object',
                    example: [
                        'field_name' => ['Field is required.']
                    ]
                )
            ])
        );
    }
}

if (!function_exists('successResponseDoc')) {
    function successResponseDoc(): OA\Response
    {
        return new OA\Response(
            response: Response::HTTP_OK,
            description: 'Success'
        );
    }
}

if (!function_exists('bearerSecurityDoc')) {
    function bearerSecurityDoc(): array
    {
        return [['bearerAuth' => []]];
    }
}
