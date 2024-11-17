<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra cualquier servicio de la aplicación.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Arranca cualquier servicio de la aplicación.
     *
     * @return void
     */
    public function boot(): void
    {
        // Cargar rutas de la web
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // Cargar rutas de la API
        Route::middleware('api')
            ->prefix('api')  // Opcional: Asegúrate de que las rutas de la API tengan el prefijo 'api'
            ->group(base_path('routes/api.php'));
    }
}
