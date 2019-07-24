<?php


namespace Highscore\Browser\Macros;

use GuzzleHttp\Client;
use Laravel\Dusk\Browser;

/**
 * @inheritDoc Laravel\Dusk\Browser
 * @package Highscore\Browser\Macros
 */
class TransparentScreenshot
{
    public function __invoke(Browser $browser, $name)
    {
        $browser->executeCommand('Emulation.setDefaultBackgroundColorOverride', ['color' => ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]]);

        $browser->screenshot($name);

        $browser->executeCommand('Emulation.setDefaultBackgroundColorOverride', []);
    }

}
