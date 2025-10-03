<?php

namespace App\Documentation\Aspect\Listeners;

use App\Documentation\Aspect\DTO\AspectConfigDTO;
use App\Documentation\Aspect\Entities\AspectConfig;
use App\Documentation\Aspect\Events\AspectCreated;

readonly class OnAspectCreatedCreateDefaultSettings
{
    public function handle(AspectCreated $event): void
    {
        $settings = AspectConfigDTO::getSupportedSettings();

        foreach ($settings as $name => $description) {
            AspectConfig::create([
                'aspect_id' => $event->aspect->id,
                'description' => $description,
                'name' => $name,
                'value' => null,
            ]);
        }
    }
}
