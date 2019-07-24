<?php


namespace Highscore\Browser\Macros;

use Facebook\WebDriver\Exception\TimeOutException;
use Laravel\Dusk\Browser;

/**
 * @inheritDoc Laravel\Dusk\Browser
 * @package Highscore\Browser\Macros
 */
class WaitFontLoad
{
    public function __invoke(Browser $browser, $timeoutInSeconds = 30)
    {
        $start = time();

        while ($browser->javascript('return document.fonts.status') === 'loading') {
            usleep(100000);

            if (time() - $start > $timeoutInSeconds) {
                throw new TimeOutException("Font load timeout");
            }
        }
    }

}
