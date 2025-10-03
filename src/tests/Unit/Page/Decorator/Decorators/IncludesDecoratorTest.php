<?php

namespace Tests\Unit\Page\Decorator\Decorators;

use App\Documentation\Decorator\IncludesDecorator;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @covers IncludesDecorator
 */
class IncludesDecoratorTest extends TestCase
{
    public function testHandle()
    {
        $decorator = new IncludesDecorator(
            new Request(),
            new DummyRequestPageDTOBuilder()
        );

        $decorator->handle(
            file_get_contents(__DIR__ . '/index.html'),
            fn (string $content) => $this->assertEquals(
                file_get_contents(__DIR__ . '/indexIncludesDecorator.html'), $content
            )
        );
    }
}
