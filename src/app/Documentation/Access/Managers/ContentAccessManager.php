<?php

namespace App\Documentation\Access\Managers;

use App\Documentation\Access\Contracts\BaseRule;
use App\Documentation\Access\Contracts\ContentAccessManagerInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ContentAccessManager implements ContentAccessManagerInterface
{
    private array $pipes = [];

    public function __construct(private readonly Application $app)
    {
    }

    public function add(BaseRule $pipe): void
    {
        $this->pipes[] = $pipe;
    }

    public function can(Request $request): bool
    {
         return (bool) (new Pipeline($this->app))
             ->send($request)
             ->through($this->pipes)
             ->thenReturn();
    }
}
