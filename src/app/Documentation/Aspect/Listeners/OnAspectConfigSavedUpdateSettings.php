<?php

namespace App\Documentation\Aspect\Listeners;

use App\Config\DTO\ConfigDTO;
use App\Config\Enums\ModificationRow;
use App\Config\Repositories\SettingsInterface;
use App\Documentation\Aspect\Contracts\AspectConverterInterface;
use App\Documentation\Aspect\DTO\AspectConfigDTO;
use App\Documentation\Aspect\Events\AspectConfigSaved;

readonly class OnAspectConfigSavedUpdateSettings
{
    public function __construct(
        private ConfigDTO $config,
        private SettingsInterface $settings,
        private AspectConverterInterface $converter,
    ) {
    }

    /**
     * @TODO удаление
     *
     * @param AspectConfigSaved $event
     * @return void
     */
    public function handle(AspectConfigSaved $event): void
    {
        $config = $event->config;

        if ($config->name === AspectConfigDTO::IS_WIKI_ASPECT) {
            $this->settings->set(
                ModificationRow::LIST_OF_WIKI_ASPECTS,
                $this->append(
                    $this->config->listOfWikiAspects,
                    (string) $this->converter->convertToAspectId($event->config->aspect)
                ),
            );
        }

        if ($config->name === AspectConfigDTO::IS_WORK_DIRECTLY) {
            $this->settings->set(
                ModificationRow::LIST_OF_ASPECTS_WORK_DIRECTLY,
                $this->append(
                    $this->config->listOfAspectsWorkDirectly,
                    (string) $this->converter->convertToAspectId($event->config->aspect)
                ),
            );
        }
    }

    private function append(array $aspects, string $aspect): string
    {
        $aspects[] = $aspect;
        $aspects = array_unique($aspects);
        return json_encode($aspects);
    }
}
