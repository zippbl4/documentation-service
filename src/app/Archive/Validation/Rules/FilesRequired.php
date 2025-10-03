<?php

namespace App\Archive\Validation\Rules;

use App\Archive\Validation\Contracts\RuleInterface;
use App\Archive\Validation\DTO\ContextDTO;

class FilesRequired implements RuleInterface
{
    /**
     * @param array $files ["index.html", ... ]
     */
    public function __construct(private array $files)
    {
    }

    public function validateWithContext(ContextDTO $context, \Closure $fail): void
    {
        $archiveFiles = $context->value('archiveFiles');
        $pathRegexPattern = $context->value('pathPattern');

        $requiredFiles = $this->validate($archiveFiles, $pathRegexPattern);

        if (count($requiredFiles) > 0) {
            $fail(sprintf(
                '%s: %s',
                'Не найдены обязательные файлы в архиве',
                implode(', ', $requiredFiles)
            ));
        }
    }

    private function validate(array $archiveFiles, string $pathRegexPattern): array
    {
        $productFiles = [];
        foreach ($archiveFiles as $archiveFile => $unused) {
            preg_match($pathRegexPattern, $archiveFile, $matches);

            if (! empty($matches['page'])) {
                $productFiles[$matches['page']] = $matches;
            }
        }

        $requiredFiles = [];
        foreach ($this->files as $file) {
            if (! isset($productFiles[$file])) {
                $requiredFiles[] = $file;
            }
        }

        return $requiredFiles;
    }
}
