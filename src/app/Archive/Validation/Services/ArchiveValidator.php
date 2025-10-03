<?php

namespace App\Archive\Validation\Services;

use App\Archive\Researcher\Contracts\ArchiveResearcherFactoryInterface;
use App\Archive\Validation\Contracts\RuleInterface;
use App\Archive\Validation\Contracts\ArchiveValidatorInterface;
use App\Archive\Validation\DTO\ContextDTO;
use App\Archive\Validation\Exceptions\ValidatorException;

class ArchiveValidator implements ArchiveValidatorInterface
{
    private ContextDTO $context;

    /**
     * @var RuleInterface[]
     */
    private array $rules = [];

    private array $messages = [];

    public function __construct(private ArchiveResearcherFactoryInterface $researcherFactory)
    {
    }

    /**
     * @param ContextDTO $context
     * @param RuleInterface[] $rules
     * @return $this
     */
    public function make(ContextDTO $context, array $rules = []): self
    {
        $this->context = $context;
        $this->rules = $rules;

        return $this;
    }

    public function passes(): bool
    {
        $this->context->withValue(
            'archiveFiles',
            array_flip($this
                ->researcherFactory
                ->get($this->context->value('unpackStrategy'))
                ->getRawFiles($this->context->value('archivePath'))
            ),
        );

        try {
            foreach ($this->rules as $rule) {
                $rule->validateWithContext($this->context, function (string $message): void {
                    $this->messages[] = $message;
                });
            }
        } catch (ValidatorException $exception) {
            $this->messages[] = $exception->getMessage();
        }

        return empty($this->messages);
    }

    public function fails(): bool
    {
        return ! $this->passes();
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
