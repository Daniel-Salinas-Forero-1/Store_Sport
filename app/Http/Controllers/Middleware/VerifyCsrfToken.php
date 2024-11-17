<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * Las rutas que deberían estar exentas de la verificación CSRF.
     *
     * @var array
     */
    protected $except = [
        'api/*', // Excluir todas las rutas de la API de la verificación CSRF
        // También puedes añadir rutas específicas si lo necesitas
        // 'api/orders/*',
    ];
}
