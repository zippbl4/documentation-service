<?php

namespace Unit\Menu;

use Tests\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $filter = [
            'lang' => 'rus',
            'product' => 'matlab',
        ];

        $keys = array_map(function ($key, $val) {
            return [
                "filter->$key" => $val,
            ];
        }, array_keys($filter), $filter);

        $keys = array_merge(...$keys);

        dd($keys);
    }
}
