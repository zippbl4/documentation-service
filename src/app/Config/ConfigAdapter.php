<?php

namespace App\Config;

use App\Config\DTO\ConfigDTO;
use App\Config\Entities\Settings;
use App\Config\Repositories\SettingsInterface;
use App\ObjectMapper\Contracts\ArrayDeserializerInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Schema;

final readonly class ConfigAdapter
{
    public function __construct(
        private Config                     $config,
        private SettingsInterface          $settings,
        private ArrayDeserializerInterface $deserializer,
    ) {
    }

    public function fillConfig(): Config
    {
        if (Schema::hasTable((new Settings())->getTable())) {
            foreach ($this->settings->all() as $name => $value) {
                if (empty($value)) {
                    continue;
                }
                $this->config->set("app-config.$name", $value);
            }
        }

        return $this->config;
    }

    public function makeConfigDTO(): ConfigDTO
    {
        return $this
            ->deserializer
            ->deserialize(
                $this->config->get('app-config'),
                ConfigDTO::class
            );
    }
}
