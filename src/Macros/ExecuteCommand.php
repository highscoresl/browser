<?php


namespace Highscore\Browser\Macros;


use GuzzleHttp\Client;
use Laravel\Dusk\Browser;

class ExecuteCommand
{
    public function __invoke(Browser $browser, string $command, $data = [])
    {
        $session = $browser->driver->getSessionID();
        $url = $browser->driver->getCommandExecutor()->getAddressOfRemoteServer() . "/session/{$session}/chromium/send_command_and_get_result";

        $client = new Client;

        return $client->post($url, [
            'body' => json_encode([
                'cmd' => $command,
                'params' => $data,
            ], JSON_THROW_ON_ERROR),
        ]);
    }
}
