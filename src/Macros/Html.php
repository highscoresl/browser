<?php


namespace Highscore\Browser\Macros;

use Laravel\Dusk\Browser;

/**
 * @inheritDoc Laravel\Dusk\Browser
 * @package Highscore\Browser\Macros
 */
class Html
{
    public function __invoke(Browser $browser)
    {
        return $browser->driver->getPageSource();
    }

}
