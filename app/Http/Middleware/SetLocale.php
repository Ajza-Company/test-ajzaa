<?php

namespace App\Http\Middleware;

use App\Models\Locale;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve default locale from cache or configuration
        $defaultLocale = Cache::rememberForever('default_locale', function () {
            return config('app.locale') ?? Locale::where('is_default', true)->value('locale');
        });

        app()->setLocale($defaultLocale);

        // Retrieve supported locales from cache
        $supportedLocales = Cache::rememberForever('supported_locales', function () {
            return Locale::pluck('locale')->toArray();
        });

        // Get the locale from the request header
        $locale = $request->header('X-LOCALE');

        // Set the application locale if it's supported
        if ($locale && in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
            if (auth('api')->check()) {
                auth('api')->user()->update(['preferred_language' => $locale]);
            }
        }

        return $next($request);
    }
}
