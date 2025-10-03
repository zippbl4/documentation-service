<?php

namespace App\Documentation\Aspect\Listeners;

use App\Config\DTO\ConfigDTO;
use App\Documentation\Aspect\Events\PathSaved;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

readonly class OnPathSavedCreateNginxAspect
{
    public function __construct(
        private Filesystem $filesystem,
        private ConfigDTO  $config,
    ) {
    }

    public function handle(PathSaved $event): void
    {
        $file = sprintf(
            '%s%s.location.template',
            $this->config->aspectsUnsafeFolder,
            Str::slug(
                $event->path->name,
                dictionary: [
                    '*' => 'all',
                ],
            ),
        );

        // TODO disabled
//        $this->filesystem->put(
//            $file,
//            $event->path->nginx_conf_template
//        );
    }
}
