<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Be Wasy API',
    description: 'Dokumentasi API untuk aplikasi Be Wasy.'
)]
#[OA\Server(
    url: '/api',
    description: 'API server'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum token'
)]
final class ApiInfo
{
}
