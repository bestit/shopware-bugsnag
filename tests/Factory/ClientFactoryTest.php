<?php

declare(strict_types=1);

namespace BestItBugsnag\Tests\Factory;

use BestItBugsnag\Factory\ClientFactory;
use Bugsnag\Client;
use PHPUnit\Framework\TestCase;

/**
 * Test for the client factory
 *
 * @package BestItBugsnag\Tests\Factory
 */
class ClientFactoryTest extends TestCase
{
    /**
     * Test the create method
     *
     * @return void
     */
    public function testCreate()
    {
        $fixture = new ClientFactory(
            $environment = 'dev',
            ['apiKey' => 'foobar', 'notifyReleaseStages' => 'dev', 'anonymizeIp' => false]
        );

        static::assertInstanceOf(Client::class, $client = $fixture->create());
        static::assertEquals($environment, $client->getConfig()->getAppData()['releaseStage']);
        static::assertTrue($client->getConfig()->shouldNotify());
    }
}
