# Laravel dusk browser macros

This packages adds several methods to the laravel dusk browser. 
Also makes easy to instance a new browser with a builder pattern.

## Instalation

```
composer require highscore/browser
```

## Usage
```php
<?php
use Laravel\Dusk\Browser;

$browser = Browser::configure()
    ->headless()
    ->singleProcess()
    ->host('http://127.0.0.1')
    ->port(9515)
    ->enableRequestLog()
    ->storeScreenshotsAt(storage_path('app/'))
    ->size(1280, 720)
    ->agent('MyCustomAgent 1.0')
    ->proxy('http://proxyurl:9510')
    ->allowDownloadsTo('/tmp')
    ->grantAllPermisions('http://www.google.com')
    ->lang('es')
    ->make();

dump($browser->visit('http://www.google.com')
    ->javascript('return document.title'));


$browser
    ->waitDocumentLoad()
    ->waitFontLoad()
    ->waitForVisible('//img')
    ->waitForClickable('button')
    ->transparentScreenshot('transparent-screenshot');

dump($browser->getNetworkEvents());
dump($browser->html());

```


## Running tests
Start a chromedriver on 127.0.0.1:9515 and run phpunit
