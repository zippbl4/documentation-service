<?php

namespace App\Documentation\Access\Rules;

use App\Documentation\Access\Contracts\BaseRule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final readonly class CanSeeProduct implements BaseRule
{
    public function __construct(
        private ?string $products = null
    ) {
    }

    public function handle(Request $request, \Closure $next): mixed
    {
        if (empty($this->products)) {
            return $next($request);
        }

        $products = array_keys(array_filter(json_decode($this->products, true)));
        $page = $request->path();

        foreach ($products as $product) {
            if (Str::contains($page, $product)) {
                return false;
            }
        }

        return $next($request);
    }
}
