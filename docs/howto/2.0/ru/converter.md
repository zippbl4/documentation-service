### Module: Converter

Изначально блы взял подход из Java Spring:

Интерфейс реализации конвертера:

Java:

```java
package org.springframework.core.convert.converter;

import org.springframework.lang.Nullable;

@FunctionalInterface
public interface Converter<S, T> {
    @Nullable
    T convert(S var1);
}
```

Php:

```php
namespace App\Converter\Contracts;

use App\Converter\Attributes\ExpectedType;

interface ConverterContract
{
    public function convert(#[ExpectedType()] object $from): object;
}
```

где, `#[ExpectedType()]` - это аналог `S var1`, тк в php мы не можем уточнить входящий тип

Сервис вызова конвертера:

```java
package org.springframework.core.convert;

import org.springframework.lang.Nullable;

public interface ConversionService {
    boolean canConvert(@Nullable Class<?> var1, Class<?> var2);

    boolean canConvert(@Nullable TypeDescriptor var1, TypeDescriptor var2);

    @Nullable
    <T> T convert(@Nullable Object var1, Class<T> var2);

    @Nullable
    Object convert(@Nullable Object var1, @Nullable TypeDescriptor var2, TypeDescriptor var3);
}
```

Слегка упрощенный вариант:

```php
namespace App\Converter\Contracts;

interface ConverterServiceContract
{
    /**
     * @template V
     * @param object $from
     * @param class-string<V> $to
     * @return V
     */
    public function convert(object $from, string $to): object;
}
```

Непосредственно конвертер:

```java
public class Foo2Bar implements Converter<Foo, Bar> {
    @Override
    public Bar convert(Foo source) {
        Bar dto = new Bar();
        //...
        return dto;
    }

}
```

Php:

```php
namespace App\Converter\Examples;

use App\Converter\Attributes\ExpectedType;
use App\Converter\Contracts\ConverterContract;

class Foo2Bar implements ConverterContract
{
    public function convert(#[ExpectedType(class: Foo::class)] object $from): Bar
    {
        $dto = new Bar();
        //...
        return $dto;
    }
}
```

Здесь видно, что тип входящего параметра уточняется через атрибут.
А тип возвращаемого значения уточняется на основе полиморфизма.

Вызов конвертера:

```java
public class Service {
    // ...
    private final ConversionService conversionService;
    // ...
    
    public void handle() {
        // ..
        Foo foo = new Foo();
        // ..
        Bar bar = conversionService.convert(foo, Bar.class);
        // ..
    }
```

Php:

```php
public function __construct(
    private ConverterServiceContract $converter,
) {
}

public function handle(): void
{
    // ...
    $foo = new Foo();
    // ...
    $bar = $this->converter->convert($foo, Bar::class);
}
```

Для добавления конвертера нужно реализовать `ConverterContract` и контейнер сам подтянет конвертер.

Но нужно сделать `composer du`, тк классы подтягиваются из `vendor/composer/autoload_classmap.php`
