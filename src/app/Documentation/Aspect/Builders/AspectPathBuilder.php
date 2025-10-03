<?php

namespace App\Documentation\Aspect\Builders;

use App\Documentation\Aspect\DTO\AspectMapperDTO;
use App\Documentation\Aspect\DTO\AspectMapperItemDTO;
use App\Documentation\Aspect\DTO\AspectPathDTO;
use App\Documentation\Aspect\DTO\BuiltPathDTO;
use Illuminate\Support\Str;

/**
 * Example:
 * <pre>
 * $builder = new AspectPathBuilder(AspectPathDTO, AspectMapperDTO);
 *
 * Получить полный путь к файлу:
 *
 * $path = $builder
 *  ->useFullPath()
 *  ->setLang($request->lang)
 *  ->setProduct($request->product)
 *  ->setVersion($request->version)
 *  ->setPage($request->page)
 *  ->setExtension($request->extension)
 *  ->buildString();
 *
 * Получить регулярное выражение на основе AspectPathDTO::pattern:
 *
 * $path = $builder
 *  ->buildRegex();
 *
 * Получить заполненное регулярное выражение на основе AspectPathDTO::pattern и входящих данных:
 *
 * $path = $builder
 *  ->fillRegex()
 *  ->setLang(AspectId->lang)
 *  ->setProduct(AspectId->product)
 *  ->setVersion(AspectId->version)
 *  ->buildRegex();
 * </pre>
 *
 * @see AspectPathBuilderTest
 */
final class AspectPathBuilder implements AspectPathBuilderInterface
{
    /**
     * @var array
     */
    private array $raw = [];
    /**
     * @var array<string, array<AspectMapperItemDTO>>
     */
    private array $mappers = [];
    private array $mappedValues = [];
    private bool $useRootWithPatter = false;
    private bool $fillRegex = false;

    public function __construct(
        private readonly AspectPathDTO $aspectPath,
        private readonly ?AspectMapperDTO $aspectMapper = null,
    ) {
        if ($aspectMapper !== null) {
            $this->mappers = $aspectMapper
                ->getItems()
                ->groupBy(function (AspectMapperItemDTO $item) {
                    return $item->getPattern();
                })
                ->toArray()
            ;
        }
    }

    public function useFullPath(): AspectPathBuilderInterface
    {
        $this->useRootWithPatter = true;
        return $this;
    }

    public function fillRegex(): AspectPathBuilderInterface
    {
        $this->fillRegex = true;
        return $this;
    }

    public function setLang(string $val): self
    {
        $this->setRaw(AspectPathDTO::LANG, $val);
        $this->setByMapper(AspectPathDTO::LANG, $val);

        return $this;
    }

    public function setProduct(string $val): self
    {
        $this->setRaw(AspectPathDTO::PRODUCT, $val);
        $this->setByMapper(AspectPathDTO::PRODUCT, $val);

        return $this;
    }

    public function setVersion(string $val): self
    {
        $this->setRaw(AspectPathDTO::VERSION, $val);
        $this->setByMapper(AspectPathDTO::VERSION, $val);

        return $this;
    }

    public function setPage(string $val): self
    {
        $this->setRaw(AspectPathDTO::PAGE, $val);
        $this->setByMapper(AspectPathDTO::PAGE, $val);

        return $this;
    }

    public function setExtension(string $val): self
    {
        $this->setRaw(AspectPathDTO::EXTENSION, $val);

        // Если существует {extension} паттерн в пути просто изменяем его
        if ($this->aspectPath->existsExtensionPattern()) {
            $this->setByMapper(AspectPathDTO::EXTENSION, $val);

            return $this;
        }

        // Если страница не пустая
        if ($this->mappedValues[AspectPathDTO::PAGE] === '' && $val === '') {
            return $this;
        }

        if ($this->mappedValues[AspectPathDTO::PAGE] !== '' && $val !== '') {
            $pageWithExtension = sprintf(
                '%s.%s',
                $this->mappedValues[AspectPathDTO::PAGE],
                $val,
            );
            $this->setByMapper(AspectPathDTO::PAGE, $pageWithExtension);
        }

        return $this;
    }

    public function buildString(): string
    {
        $path = preg_replace_callback('~\{(\w+)\}~', function ($matches): string {
            $name = $matches[0];
            return $this->mappedValues[$name] ?? $name;
        }, $this->aspectPath->getPattern());

        if ($this->useRootWithPatter) {
            return $this->aspectPath->getRoot() . $path;
        }

        return $path;
    }

    public function buildObject(): BuiltPathDTO
    {
        $builtPath = new BuiltPathDTO();

        $path = preg_replace_callback('~\{(\w+)\}~', function ($matches) use ($builtPath): string {
            $pattern = $matches[0];
            $name = $matches[1];

            $value = $this->mappedValues[$pattern] ?? $pattern;

            $builtPath->addPattern($pattern, $value);
            $builtPath->addFilter($name, $value);

            return $value;
        }, $this->aspectPath->getPattern());

        $builtPath
            ->setRootWithPath($this->aspectPath->getRoot() . $path)
            ->setRoot($this->aspectPath->getRoot())
            ->setPath($path)
        ;

        return $builtPath;
    }

    public function buildRegex(): string
    {
        return sprintf(
            '~%s$~',
            preg_replace_callback('~\{(\w+)\}~', function ($matches): string {
                $pattern = $matches[0];
                switch ($pattern) {
                    case AspectPathDTO::VERSION:
                        static $versionPatternCount = 0;

                        $versionPattern = "?(?P<version>{$this->getPatternStatement($pattern)})";
                        if ($versionPatternCount > 0) $versionPattern = '(?P=version)';

                        $versionPatternCount++;
                        return $versionPattern;
                    case AspectPathDTO::LANG:
                        return "(?P<lang>{$this->getPatternStatement($pattern)})";
                    case AspectPathDTO::PRODUCT:
                        return "(?P<product>{$this->getPatternStatement($pattern)})";
                    case AspectPathDTO::PAGE:
                        return '?(?P<page>.*\.\w+)?';
                    default:
                        return '';
                }
            }, $this->aspectPath->getPattern()),
        );
    }

    private function setRaw(string $pattern, string $val): AspectPathBuilderInterface
    {
        if (!in_array($pattern, AspectPathDTO::getSupportedPatterns())) {
            throw new \Exception('No pattern: ' . $pattern);
        }

        $this->raw[$pattern] = $val;

        return $this;
    }

    private function setByMapper(string $pattern, mixed $val): self
    {
        if (!in_array($pattern, AspectPathDTO::getSupportedPatterns())) {
            throw new \Exception('No pattern: ' . $pattern);
        }

        $this->mappedValues[$pattern] = $val;

        if ($this->aspectMapper === null) {
            return $this;
        }

        if (! isset($this->mappers[$pattern])) {
            return $this;
        }

        $modifiedVal = $val;
        $mappers = $this->mappers[$pattern];
        foreach ($mappers as $mapper) {
            if (empty($modifiedVal)) {
                $modifiedVal = $mapper->getTo();
            } else {
                $modifiedVal = Str::replace($mapper->getFrom(), $mapper->getTo(), $modifiedVal);
            }
        }

        $this->mappedValues[$pattern] = $modifiedVal;
        return $this;
    }

    private function getPatternStatement(string $pattern, string $default = '\w+'): string
    {
        $patternStatement = $default;

        if ($this->fillRegex && isset($this->mappedValues[$pattern])) {
            $patternStatement = $this->mappedValues[$pattern];
        }

        return $patternStatement;
    }
}
