<?php

declare(strict_types=1);

namespace BestItBugsnag\Factory;

use Bugsnag\Client;
use Bugsnag\Report;

/**
 * Factory to create a bugsnag client from a given config
 *
 * @package BestItBugsnag\Factory
 */
class ClientFactory
{
    /**
     * The plugin config
     *
     * @var array
     */
    private $config;

    /**
     * The shop environment
     *
     * @var string
     */
    private $environment;

    /**
     * ClientFactory constructor.
     *
     * @param string $environment
     * @param array $config
     */
    public function __construct(string $environment, array $config)
    {
        $this->environment = $environment;
        $this->config = $config;
    }

    /**
     * Creates the bugsnag client
     *
     * @return Client
     */
    public function create(): Client
    {
        if (empty($this->config['apiKey'])) {
            $client = Client::make();
        } else {
            $client = Client::make($this->config['apiKey']);
        }

        $client->setReleaseStage($this->environment);

        if ($this->config['notifyReleaseStages']) {
            $client->setNotifyReleaseStages($this->parseNotifyReleaseStages());
        }

        if ($this->config['anonymizeIp']) {
            $client->registerCallback(
                function (Report $report) {
                    // replaces the automatically collected ID
                    $report->setUser(['id' => null]);

                    // replaces the automatically collected IP
                    $report->addMetaData(['request' => ['clientIp' => null],]);
                }
            );
        }

        return $client;
    }

    /**
     * The config field is either an array or a string depending on how the config is used.
     * Selecting options from the dropdown will make it an array. Writing into the field sends makes it a string
     *
     * @return array
     */
    private function parseNotifyReleaseStages(): array
    {
        $notifyReleaseStages = $this->config['notifyReleaseStages'];

        if (is_string($notifyReleaseStages)) {
            $notifyReleaseStages = array_map(
                'trim',
                explode(',', $this->config['notifyReleaseStages'])
            );
        }

        return $notifyReleaseStages;
    }
}
