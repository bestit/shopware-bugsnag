<?php

declare(strict_types=1);

namespace BestItBugsnag;

use Bugsnag\Client;
use Bugsnag\Handler;
use Shopware\Components\Plugin\CachedConfigReader;

/**
 * The adapter for bugsnag to handle errors.
 *
 * @package BestItBugsnag
 */
class BugsnagClient
{
    /**
     * The shopware config.
     *
     * @var CachedConfigReader
     */
    private $configReader;

    /**
     * The cached bugsnag client.
     *
     * @var Client|null
     */
    private $bugsnagClient;

    /**
     * BugsnagClient constructor.
     *
     * @param CachedConfigReader $configReader
     */
    public function __construct(CachedConfigReader $configReader)
    {
        $this->configReader = $configReader;
    }

    /**
     * Loads the original bugsnag client and handles it as a singleton.
     *
     * @return Client
     */
    public function getInstance(): Client
    {
        if (!$this->bugsnagClient) {
            $config = $this->getConfig();
            if (empty($config['apiKey'])) {
                $this->bugsnagClient = Client::make();
            } else {
                $this->bugsnagClient = Client::make($config['apiKey']);
            }

            if ($config['anonymizeIp']) {
                $this->bugsnagClient->registerCallback(
                    function ($report) {
                        // replaces the automatically collected IP
                        $report->setUser(['id' => null]);

                        // replaces the automatically collected IP
                        $report->addMetaData(['request' => ['clientIp' => null],]);
                    }
                );
            }
        }

        return $this->bugsnagClient;
    }

    /**
     * Registers the error handler for an error and resets to defaults after that.
     *
     * @return void
     */
    public function registerHandler()
    {
        $config = $this->getConfig();
        $bugsnag = $this->getInstance();
        $bugsnagHandler = new Handler($bugsnag);

        if ($config['exceptionHandler']) {
            $bugsnagHandler->registerExceptionHandler(false);
        }

        if ($config['errorHandler']) {
            $bugsnagHandler->registerErrorHandler(false);
        }

        // restore default error handler
        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * Returns the config for this service.
     *
     * @return array
     */
    private function getConfig(): array
    {
        return $this->configReader->getByPluginName('BestItBugsnag');
    }
}
