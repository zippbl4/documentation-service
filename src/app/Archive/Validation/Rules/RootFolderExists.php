<?php

namespace App\Archive\Validation\Rules;

use App\Archive\Validation\Contracts\RuleInterface;
use App\Archive\Validation\DTO\ContextDTO;

class RootFolderExists implements RuleInterface
{
    public function validateWithContext(ContextDTO $context, \Closure $fail): void
    {
        $archiveFiles = $context->value('archiveFiles');

        if (! $this->validate($archiveFiles)) {
            $fail('Отсутствует корневая папка в архиве.');
        }
    }

    protected function validate(array $archiveFiles): bool
    {
        $rootFolders = [];
        foreach ($archiveFiles as $path => $unused) {
            $parts = explode('/', $path);
            if (! empty($parts[0])) {
                $rootFolders[] = $parts[0];
            }
        }

        $rootFolders = array_unique($rootFolders);
        $rootFolderExists = count($rootFolders) === 1;

        return $rootFolderExists;
    }
}
