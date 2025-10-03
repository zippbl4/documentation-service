### Module: Page.Driver

Структура (`tree ./src/app/Page/Driver`):

```text
./src/app/Page/Driver
├── Contracts
│   ├── Driver.php
│   ├── DriverFactoryInterface.php
│   └── SupportedDriversInterface.php
├── DTO
│   ├── DriverRequestDTO.php
│   └── DriverResponseDTO.php
├── DriverServiceProvider.php
├── Drivers
│   ├── LocalDriver.php
│   └── RemoteDriver.php
├── Exceptions
│   └── DriverException.php
└── Factories
    └── DriverFactory.php
```

Каждый драйвер реализует интерфейс:

```php
interface Driver
{
    /**
     * @param DriverRequestDTO $request
     * @return DriverResponseDTO
     * @throws DriverException
     */
    public function showPage(DriverRequestDTO $request): DriverResponseDTO;
}
```

В текущем варианте драйвера могут работать только на чтение.

С помощью фабрики:

```php
interface DriverFactoryInterface
{
    public function getDriver(string $name): Driver;
}
```

Регистрируется в контейнере:

```php
public function registerFactory(): void
{
    $this->app->alias(DriverFactoryInterface::class, AliasDictionary::DRIVERS);
    $this->app->singleton(DriverFactoryInterface::class, DriverFactory::class);
}

public function registerDrivers(): void
{
    $this->app->extend(AliasDictionary::DRIVERS, function (DriverFactory $manager): DriverFactory {
        $manager->addDriver($this->app->make(LocalDriver::class));
        $manager->addDriver($this->app->make(RemoteDriver::class));
        return $manager;
    });
}
```

И далее используется:

```php
public function __construct(
    private DriverFactoryInterface $driverFactory,
) {
}

public function foo(): void
{
    $driverResponse = $this
        ->driverFactory
        ->getDriver('local')
        ->showPage(DriverRequestDTO)
    ;
}
```

#### DTO

Если с `DriverResponseDTO` вроде как все понятно:

```php
final readonly class DriverResponseDTO
{
    public function __construct(
        private string $title,
        private string $content,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
```
то с `DriverRequestDTO` наверно нет.


`DriverRequestDTO` - запрос к `Driver`. Сейчас получается неоднозначная херня.
Каждому драйверу нужно однозначные данные для поиска контента. Сейчас `$filters` для `EloquentDriver`,
`$rootWithPath` для `LocalDriver`. И выходит что разные драйвера используют разные данные для поиска, но заполнять надо все!
Шляпа. 
Я пока не решил как тут лучше сделать.

Изначально он был 

```php
final readonly class DriverRequestDTO
{
    public function __construct(
        private string $path,
    ) {
    }

    /**
     * @example /var/www/storage/app/release//R2018b/R2018b_rus/matlab/index.html
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->path;
    }
}
```

Вон путь передали и достаточно. А там дальше драйвер уже разбирал как с этой строкой работать.
Был вариант сделать `private mixed $uniquenessCondition` - и опять там каждый драйвер будет с этим работать как хочет.

Но!

Оказывается в том единственном месте, где драйвер вызывается, 
стоит билдер которому похоже тоже нужно сказать какой драйвер будет использоваться,
что бы тот генерировал правильные данные для билдера. Хз.

...

В `RemoteDriver` наверно вообще нужны будут креды для авторизации. Их тоже куда-то нужно сунуть.

```php
final readonly class DriverRequestDTO
{
    public function __construct(
        private array $filters,
        private string $rootWithPath,
    ) {
    }

    /**
     * @example /var/www/storage/app/release//R2018b/R2018b_rus/matlab/index.html
     * @return string
     */
    public function getRootWithPath(): string
    {
        return $this->rootWithPath;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}
```

