<?php

namespace App\Documentation\Uploader\Http\Rules;

use App\Documentation\Aspect\Contracts\AspectServiceContract;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * @deprecated {@see \App\Documentation\Validation\Rules\FilesRequired}
 */
final readonly class CheckRequiredArchiveFiles implements ValidationRule
{
    public function __construct(
        private AspectServiceContract $aspectService,
        private int                   $aspectId,
    ) {
        //
    }

    /**
     * @param string $attribute
     * @param UploadedFile $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        //TODO
        return;

        $aspect = $this->aspectService->getAspect($this->aspectId);

        // TODO в репозиторий
        $pages = $aspect->getValidations();
        if ($pages->isEmpty()) {
            return;
        }

        // TODO...
        $pages = $pages
            ->map(fn ($item) => $item->page)
            ->map(function ($page) use ($aspect) {
                $path = $aspect
                    ->getPathBuilder()
                    ->removeFirstSlash()
                    ->setLang("({$aspect->id->lang})")
                    ->setProduct($aspect->id->product)
                    ->setVersion($aspect->id->version)
                    ->setPage($page)
                    ->removeDoubleSlashes()
                    ->buildString();

                return \Str::replace('/', '\/', $path);
            })
            ->toArray();

        $notExistedFiles = $this
            ->checkerManager
            ->get($value->getClientOriginalExtension())
            ->check(
                $value->getPathname(),
                $pages,
            );

        if (count($notExistedFiles) > 0) {
            $fail(sprintf(
                '%s: %s',
                'Не найдены обязательные файлы в архиве',
                implode(',', $notExistedFiles)
            ));
        }
    }
}
