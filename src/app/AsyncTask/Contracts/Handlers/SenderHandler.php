<?php

namespace App\AsyncTask\Contracts\Handlers;

interface SenderHandler
{

    /**
     * @return iterable<string>|null
     */
    public function data(): ?iterable;
}
