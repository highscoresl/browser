<?php

namespace Illuminate\Foundation\Testing;

use Highscore\Browser\BrowserBuilder;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{

    public function test_builds_a_with_a_config()
    {
        $config = (new BrowserBuilder)->configure()
            ->agent('AutomatedBrowser')
            ->size(1500, 700)
            ->singleProcess()
            ->proxy('http://proxy')
            ->fullScreen()
            ->headless()
            ->host('http://127.0.0.1')
            ->port('9515')
            ->lang('ru_RU')
            ->storeSessionAt('/tmp/test')
            ->enableRequestLog()
            ->getOptions();

        $this->assertEquals([
            '--mute-audio',
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-infobars',
            '--disable-extensions-file-access-check',
            '--disable-extensions-http-throttling',
            '--test-type',
            '--ignore-certificate-errors',
            '--start-maximized',
            '--user-agent=AutomatedBrowser',
            '--window-size=1500,700',
            '--single-process',
            '--proxy-server=http://proxy',
            '--kiosk',
            '--full-screen',
            '--window-position=0,0',
            '--headless',
            '--disable-gpu',
            '--port=9515',
            '--lang=ru_RU',
            '--user-data-dir=/tmp/test',
        ], $config);
    }

}
