<?php


namespace Highscore\Browser\Macros;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Laravel\Dusk\Browser;

/**
 * @inheritDoc Laravel\Dusk\Browser
 * @package Highscore\Browser\Macros
 */
class WaitForVisible
{
    public function __invoke(Browser $browser, $xpath)
    {
        $browser->driver->wait()->until(
            WebDriverExpectedCondition::elementToBeClickable(
                WebDriverBy::xPath($xpath)
            )
        );
    }

}
