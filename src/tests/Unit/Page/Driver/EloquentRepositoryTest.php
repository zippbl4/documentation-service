<?php

namespace Tests\Unit\Page\Driver;

use App\Documentation\AspectPlugin\PageDriver\Repositories\MysqlPageRepository;
use Tests\TestCase;

class EloquentRepositoryTest extends TestCase
{
    public function testUpdate()
    {
        $values = [
            'href' => 'test',
            'title' => 'test',
            'content' => 'test',
            'payload' => json_encode(array_diff_key(
                [
                    'lang' => '1',
                    'product' => '1',
                    'version' => '1',
                    'page' => '1',
                    'content' => '1',
                    'default_content_hash' => '1',
                ],
                ['content' => null],
            )),
        ];

        $repository = $this->app->make(MysqlPageRepository::class);
        $repository->updateOrCreateMany($values);
    }
}
