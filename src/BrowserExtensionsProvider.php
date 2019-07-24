<?php

namespace Highscore\Browser;

use Highscore\Browser\Macros\ExecuteCommand;
use Highscore\Browser\Macros\GetNetworkEvents;
use Highscore\Browser\Macros\Html;
use Highscore\Browser\Macros\Javascript;
use Highscore\Browser\Macros\TransparentScreenshot;
use Highscore\Browser\Macros\WaitDocumentLoad;
use Highscore\Browser\Macros\WaitForClickable;
use Highscore\Browser\Macros\WaitFontLoad;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class BrowserExtensionsProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Browser::macro('executeCommand', function($command, $data) {
            (new ExecuteCommand)($this, $command, $data);

            return $this;
        });

        Browser::macro('configure', function() {
            return new BrowserBuilder;
        });

        Browser::macro('transparentScreenshot', function($name) {
            (new TransparentScreenshot)($this, $name);

            return $this;
        });

        Browser::macro('waitForClickable', function($xpath) {
            (new WaitForClickable)($this, $xpath);

            return $this;
        });

        Browser::macro('waitForVisible', function($xpath) {
            (new WaitFontLoad)($this, $xpath);

            return $this;
        });

        Browser::macro('html', function() {
            return (new Html)($this);
        });

        Browser::macro('javascript', function($script) {
            return (new Javascript)($this, $script);
        });

        Browser::macro('waitFontLoad', function($timeoutInSeconds = 30) {
            (new WaitFontLoad)($this, $timeoutInSeconds);
            return $this;
        });

        Browser::macro('waitDocumentLoad', function($timeoutInSeconds = 30) {
            (new WaitDocumentLoad)($this, $timeoutInSeconds);
            return $this;
        });

        Browser::macro('getNetworkEvents', function() {
            return (new GetNetworkEvents)($this);
        });
    }
}
