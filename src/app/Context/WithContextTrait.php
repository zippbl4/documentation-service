<?php

namespace App\Context;

trait WithContextTrait
{
    private Context $context;

    public function withContext(Context $context): self
    {
        $this->context = $context;
        return $this;
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}
