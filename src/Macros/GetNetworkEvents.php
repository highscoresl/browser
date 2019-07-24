<?php


namespace Highscore\Browser\Macros;

use Facebook\WebDriver\Exception\UnknownServerException;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;

/**
 * @inheritDoc Laravel\Dusk\Browser
 * @package Highscore\Browser\Macros
 */
class GetNetworkEvents
{
    public function __invoke(Browser $browser): array
    {
        $browser->executeCommand('Network.enable', []);
        try {
            $logs = $browser->driver->manage()->getLog('performance');
        } catch (UnknownServerException $exception) {
            throw new \RuntimeException("Cannot get log, Try calling enableRequestLog on the builder");
        }

        return collect($logs)->map(function ($item) {
            return json_decode($item['message'], null, 512, JSON_THROW_ON_ERROR);
        })
            ->pluck('message')
            ->filter(function ($item) {
                return Str::contains($item->method, 'Network');
            })->all();
    }

}
