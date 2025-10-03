<?php

namespace App\AsyncTask\Services;

use App\AsyncTask\Channels\ChannelContract;

abstract class BaseService
{
    private ChannelContract $channel;

    public function setChannel($channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    public function getChannel(): ChannelContract
    {
        return $this->channel;
    }
}
