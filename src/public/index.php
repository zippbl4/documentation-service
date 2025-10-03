<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));
define('XHPROF_DEBUG_EXTENSION_EXISTS', extension_loaded('xhprof'));
define('XHPROF_DEBUG_START', isset($_GET['XHPROF_DEBUG_START']));

if (XHPROF_DEBUG_EXTENSION_EXISTS && XHPROF_DEBUG_START) {
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    register_shutdown_function(static function () {
        if (! is_dir('/var/log/php/xhprof/')) {
            mkdir('/var/log/php/xhprof/');
        }

        file_put_contents(
            sprintf(
                '/var/log/php/xhprof/%s.%s.xhprof',
                time(),
                'type'
            ),
            serialize(xhprof_disable())
        );
    });
}

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);

$request = Request::capture();

$response = $kernel->handle($request)->send();

$kernel->terminate($request, $response);
