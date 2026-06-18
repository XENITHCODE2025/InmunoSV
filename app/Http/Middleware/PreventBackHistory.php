<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * PreventBackHistory
 * ──────────────────────────────────────────────────────────────────────────────
 * Evita que el navegador muestre páginas autenticadas desde su caché
 * al presionar "atrás" después de cerrar sesión.
 *
 * Sin este middleware, Laravel invalida la sesión correctamente en el servidor,
 * pero el navegador puede seguir mostrando el HTML que ya había descargado
 * (sobre todo en Chrome/Edge con back-forward cache).
 *
 * Aplica los headers no-cache a TODAS las respuestas de rutas protegidas
 * por 'auth', así nunca se sirve una vista sensible desde el caché local.
 */
class PreventBackHistory
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        return $response;
    }
}