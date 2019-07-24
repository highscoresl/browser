<?php


namespace Highscore\Browser\Macros;

use Laravel\Dusk\Browser;

/**
 * @inheritDoc Laravel\Dusk\Browser
 * @package Highscore\Browser\Macros
 */
class Javascript
{
    public function __invoke(Browser $browser, $script)
    {
        return $browser->driver->executeScript($script);
    }

}
