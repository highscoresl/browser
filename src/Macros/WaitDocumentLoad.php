<?php


namespace Highscore\Browser\Macros;

use Facebook\WebDriver\Exception\TimeOutException;
use Laravel\Dusk\Browser;

/**
 * @inheritDoc Laravel\Dusk\Browser
 * @package Highscore\Browser\Macros
 */
class WaitDocumentLoad
{
    public function __invoke(Browser $browser, $timeoutInSeconds = 30)
    {
        $start = time();

        while ($browser->driver->executeScript('return document.readyState') !== 'complete') {
            usleep(100000);

            if (time() - $start > $timeoutInSeconds) {
                throw new TimeOutException("Document load timeout");
            }
        }
    }

}
