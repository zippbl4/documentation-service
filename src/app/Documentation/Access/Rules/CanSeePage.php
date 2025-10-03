<?php

namespace App\Documentation\Access\Rules;

use App\Documentation\Access\Contracts\BaseRule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final readonly class CanSeePage implements BaseRule
{
    public function __construct(
        private ?string $pages = null
    ) {
    }

    public function handle(Request $request, \Closure $next): mixed
    {
        if (empty($this->pages)) {
            return $next($request);
        }

        $pages = explode('/n', $this->pages);
        $page = $request->path();

        foreach ($pages as $p) {
            if (Str::contains($page, $p)) {
                return true;
            }
        }

        return $next($request);
    }
}
