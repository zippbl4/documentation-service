event(new ProductUploaded(2, 'cli', '/var/www/storage/app/release/R2018b'))

app(EloquentSaverReceiverHandler::class, ['aspectId' => 2])->receive('{"lang":"eng","product":"matlab","version":"R2018b","page":"hello/index.html","content":"test"}');


{"lang":"eng","product":"matlab","version":"R2018b","page":"ref\/matlab.mock.testcase.verifyset.html","content":"test"}
