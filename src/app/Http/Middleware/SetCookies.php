<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

final class SetCookies
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @TODO
     */
    public function handle($request, \Closure $next): mixed
    {
        /** @var CookieJar $cookie */
        $cookie = app("cookie");

        $uuid = $request->cookie('userGuestUuid');
        if ($uuid === null) {
            $uuid = Uuid::uuid4()->toString();
            $cookie->queue($cookie->make('userGuestUuid', $uuid,60*24*30));
            $request->cookies->add(['userGuestUuid' => $uuid]);
        }

        return $next($request);
    }
}
