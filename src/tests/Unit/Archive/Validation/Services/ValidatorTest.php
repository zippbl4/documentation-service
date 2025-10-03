<?php

namespace Tests\Unit\Archive\Validation\Services;

use App\Archive\Validation\DTO\ContextDTO;
use App\Archive\Validation\Rules\FilesRequired;
use App\Archive\Validation\Rules\RootFolderExists;
use App\Archive\Validation\Services\ArchiveValidator;
use App\Documentation\Aspect\DTO\AspectPathDTO;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ValidatorTest extends TestCase
{
    #[DataProvider(methodName: 'rules')]
    public function testValidate(string $archive, array $rules, array $messages, bool $fails): void
    {
        $archive = $this->app->storagePath($archive);
        $path = new AspectPathDTO(
            driver: 'local',
            root: $this->app->storagePath('/tests/release/'),
            pattern: '/{version}/{version}_{lang}/{product}/{page}',
        );

        $ctx = (new ContextDTO())
            ->withValue('unpackStrategy', 'zip_manual')
            ->withValue('archivePath', $archive)
            ->withValue('pathPattern', $path->getBuilder()->buildRegex())
        ;
        $validator = $this
            ->app
            ->make(ArchiveValidator::class)
            ->make($ctx, $rules)
        ;

        $this->assertEquals(
            $fails,
            $validator->fails(),
        );

        $this->assertEquals(
            $messages,
            $validator->getMessages(),
        );
    }

    public static function rules(): iterable
    {
        yield [
            'archive' => '/tests/zip/R2018b.zip',
            'rules' => [],
            'messages' => [],
            'fails' => false,
        ];

        yield [
            'archive' => '/tests/zip/R2018b.zip',
            'rules' => [new RootFolderExists()],
            'messages' => [],
            'fails' => false,
        ];

        yield [
            'archive' => '/tests/zip/without_root.zip',
            'rules' => [new RootFolderExists()],
            'messages' => ['Отсутствует корневая папка в архиве.'],
            'fails' => true,
        ];

        yield [
            'archive' => '/tests/zip/R2018b.zip',
            'rules' => [new FilesRequired(['index.html'])],
            'messages' => [],
            'fails' => false,
        ];

        yield [
            'archive' => '/tests/zip/R2018b.zip',
            'rules' => [new FilesRequired(['index.html', 'index1.html', 'index2.html'])],
            'messages' => ['Не найдены обязательные файлы в архиве: index1.html, index2.html'],
            'fails' => true,
        ];

        yield [
            'archive' => '/tests/zip/without_root.zip',
            'rules' => [
                new RootFolderExists(),
                new FilesRequired(['index.html', 'index1.html']),
            ],
            'messages' => [
                'Отсутствует корневая папка в архиве.',
                'Не найдены обязательные файлы в архиве: index.html, index1.html'
            ],
            'fails' => true,
        ];
    }
}
