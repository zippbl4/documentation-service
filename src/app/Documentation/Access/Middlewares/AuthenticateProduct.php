<?php

namespace App\Documentation\Access\Middlewares;

use App\Documentation\Access\Contracts\ContentAccessManagerInterface;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as Auth;

final class AuthenticateProduct extends Middleware
{
    public function __construct(
        Auth                                           $auth,
        private readonly ContentAccessManagerInterface $authManager,
    ) {
        parent::__construct($auth);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards): mixed
    {
        if ($this->authManager->can($request)) {
            return $next($request);
        }

        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            return route('auth.loginForm');
        }

        return null;
    }
}
