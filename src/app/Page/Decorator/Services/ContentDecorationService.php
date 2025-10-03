<?php

namespace App\Page\Decorator\Services;

use App\Context\Context;
use App\Page\Decorator\Contracts\ContentDecorationInterface;
use App\Page\Decorator\DTO\InboundDecoratorDTO;
use App\Page\Decorator\Factories\DecoratorFactoryInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class ContentDecorationService implements ContentDecorationInterface
{
    public function __construct(
        private DecoratorFactoryInterface $decoratorFactory,
        private Container $app,
    ) {
    }

    public function decorate(string $content, array $decorators, Context $context): string
    {
        return (new Pipeline($this->app))
            ->send($content)
            ->through($this->getPipes($decorators, $context))
            ->then(fn(string $content): string => $content);
    }

    /**
     * @param list<InboundDecoratorDTO> $decorators
     * @param Context $context
     * @return array
     */
    private function getPipes(array $decorators, Context $context): array
    {
        $pipe = [];

        foreach ($decorators as $decorator) {
            $pipe[] = $this
                ->decoratorFactory
                ->getDecorator($decorator->name)
                ->setContext($context)
                ->setUserCustomTemplate($decorator->userCustomTemplate)
            ;
        }

        return $pipe;
    }
}
