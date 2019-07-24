<?php

namespace Laravel\Dusk {

    use Highscore\Browser\BrowserBuilder;

    class Browser {
        public static function configure(): BrowserBuilder {}
        public static function html(): string {}

        public function executeCommand(string $command, $data = []): Browser {}
        public function transparentScreenshot($name): Browser {}
        public function waitForVisible(string $xpath): Browser {}
        public function waitForClickable(string $xpath): Browser {}
        public function waitFontLoad($timeoutInSeconds = 30): Browser {}
        public function waitDocumentLoad($timeoutInSeconds = 30): Browser {}
        public function javascript(string $script): mixed {}
        public function getNetworkEvents(string $script): array {}

    }
}
