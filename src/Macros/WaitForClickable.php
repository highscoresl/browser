<?php


namespace Highscore\Browser\Macros;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Laravel\Dusk\Browser;

/**
 * @inheritDoc Laravel\Dusk\Browser
 * @package Highscore\Browser\Macros
 */
class WaitForClickable
{
    public function __invoke(Browser $browser, $xpath)
    {
        $browser->driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::xPath($xpath)
            )
        );
    }

}
