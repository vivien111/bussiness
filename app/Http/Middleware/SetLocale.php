<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->route('locale') ?? Auth::user()?->locale ?? config('app.locale');
        App::setLocale($locale);

        return $next($request);
    }
}

