<?php


namespace Highscore\Browser;


use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Illuminate\Support\Traits\ForwardsCalls;
use Laravel\Dusk\Browser;

/**
 * @method Browser static create()
 */
class BrowserBuilder
{
    use ForwardsCalls;
    private static $baseConfig = [
    ];

    protected $host;
    protected $port;
    protected $after = [];

    /** @var DesiredCapabilities */
    protected $capabilities;
    private $connectionTimeout = 5000;
    private $requestTimeout = 60000;
    /**
     * @var ChromeOptions
     */
    protected $options;

    public function __construct($baseConfig = [
        '--mute-audio',
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-infobars',
        '--disable-extensions-file-access-check',
        '--disable-extensions-http-throttling',
        '--test-type',
        '--ignore-certificate-errors',
        '--start-maximized',
    ])
    {
        $this->options = (new ChromeOptions)->addArguments($baseConfig);
        $this->capabilities = DesiredCapabilities::chrome();
    }

    /**
     * Allow interactive builder config
     * @return BrowserBuilder
     */
    public static function configure()
    {
        return new static;
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    /**
     * Builds the configured browser
     * @return Browser
     */
    public function make(): Browser
    {

        $this->capabilities->setCapability(ChromeOptions::CAPABILITY, $this->options);

        $this->host = $this->host ?: config('browser.host', 'http://127.0.0.1');
        $this->port = $this->port ?: config('browser.port', 9515);

        $driver = RemoteWebDriver::create(
            $this->host . ':' . $this->port,
            $this->capabilities,
            $this->connectionTimeout,
            $this->requestTimeout
        );

        $browser = new Browser($driver);

        collect($this->after)->each(function($closure) use ($browser) {
            $closure($browser);
        });

        return $browser;
    }

    public function getOptions()
    {
        return $this->options->toArray()['args'];
    }

    /**
     * Sets the browser user agent
     * @param $userAgent
     * @return BrowserBuilder
     */
    public function agent($userAgent): self
    {
        $this->options->addArguments([
            "--user-agent={$userAgent}"
        ]);
        return $this;
    }

    /**
     * Sets the window dimensions
     * @param int $width
     * @param int $height
     * @return BrowserBuilder
     */
    public function size(int $width, int $height): self
    {
        $this->options->addArguments([
            "--window-size={$width},{$height}"
        ]);

        return $this;
    }

    /**
     * Chromedriver location (http://127.0.0.1)
     * @param string $host
     * @return BrowserBuilder
     */
    public function host(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Chromedriver port (9515)
     * @param int $port
     * @return BrowserBuilder
     */
    public function port(int $port): self
    {
        $this->options->addArguments([
            "--port={$port}"
        ]);

        $this->port = $port;

        return $this;
    }

    /**
     * Tells the chrome to use a single process
     * @return BrowserBuilder
     */
    public function singleProcess(): self
    {
        $this->options->addArguments([
            "--single-process"
        ]);

        return $this;
    }

    /**
     * Open the browser on 'fullscreen'
     * @return BrowserBuilder
     */
    public function fullScreen(): self
    {

        $this->options->addArguments([
            '--kiosk',
            '--full-screen',
            '--window-position=0,0',
        ]);

        return $this;
    }

    /**
     * Run in headless mode
     * @return BrowserBuilder
     */
    public function headless(): self
    {
        $this->options->addArguments([
            '--headless',
            '--disable-gpu',
        ]);

        return $this;
    }

    /**
     * Set the chrome proxy url
     * @param $url
     * @return BrowserBuilder
     */
    public function proxy($url): self
    {
        $this->capabilities->setCapability(WebDriverCapabilityType::PROXY, [
            'proxyType' => 'MANUAL',
            'httpProxy' => $url,
            'sslProxy' => $url,
        ]);

        $this->options->addArguments(["--proxy-server={$url}"]);

        return $this;
    }

    /**
     * Set the browsers language
     * @param string $langCode
     * @return BrowserBuilder
     */
    public function lang(string $langCode): self
    {
        $this->options->addArguments([
            "--lang={$langCode}",
        ]);
        return $this;
    }

    /**
     * Grant all permissions for a site
     * @param string $origin
     * @return BrowserBuilder
     */
    public function grantAllPermisions(string $origin = '*'): self
    {
        $this->after[] = function($browser) use ($origin){
            $browser->executeCommand('Browser.grantPermissions', [
                'origin' => $origin,
                'permissions' => [
                    'accessibilityEvents',
                    'audioCapture',
                    'backgroundSync',
                    'clipboardRead',
                    'clipboardWrite',
                    'durableStorage',
                    'flash',
                    'geolocation',
                    'midi',
                    'midiSysex',
                    'notifications',
                    'paymentHandler',
                    'protectedMediaIdentifier',
                    'sensors',
                    'videoCapture',
                ],
            ]);
        };
        return $this;
    }

    /**
     * Allow downloads to a path
     * @param string $downloadPath
     * @return BrowserBuilder
     */
    public function allowDownloadsTo($downloadPath = '/tmp'): self
    {
        $this->after[] = function($browser) use ($downloadPath){
            $browser->executeCommand('Page.setDownloadBehavior', [
                'behavior' => 'allow',
                'downloadPath' => $downloadPath,
            ]);
        };

        $this->options->setExperimentalOption('prefs', [
            'profile.default_content_settings.popups' => 0,
            'profile.managed_default_content_settings.images' => 1,
            'profile.managed_default_content_settings.notifications' => 1,
            'profile.default_content_setting_values.durable_storage' => 1,
            'browser.setDownloadBehavior' => 'allow',
            'download.default_directory' => $downloadPath,
            'download.prompt_for_download' => false,
            'download.directory_upgrade' => true,
            'safebrowsing.enabled' => true,
        ]);

        return $this;
    }

    /**
     * Set the path to store this session. Subsequent requests will remember the cookies and other navigation data.
     * @param $sessionPath
     * @return BrowserBuilder
     */
    public function storeSessionAt($sessionPath): self
    {
        $this->options->addArguments([
            "--user-data-dir={$sessionPath}"
        ]);

        return $this;
    }

    /**
     * Iphone size and user agent
     * @return BrowserBuilder
     */
    public function mobile(): self
    {
        return $this->agent("Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1")
            ->size(375, 812);
    }

    /**
     * Enable request logging for later retrival
     * @return BrowserBuilder
     */
    public function enableRequestLog(): self
    {
        $this->capabilities->setCapability('loggingPrefs', (object) ['performance' => 'ALL']);

        return $this;
    }

    /**
     * Set where the screenshots should be stored
     * @param $path
     * @return BrowserBuilder
     */
    public function storeScreenshotsAt($path): self
    {
        Browser::$storeScreenshotsAt = $path;

        return $this;
    }
}
