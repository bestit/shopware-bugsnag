<?php

declare(strict_types=1);

namespace BestItBugsnag\Tests\Subscriber;

use BestItBugsnag\Subscriber\BugsnagSubscriber;
use Bugsnag\Handler;
use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Front;
use Enlight_Event_EventArgs;
use PHPUnit\Framework\TestCase;

/**
 * Test for bugsnag subscriber
 *
 * @package BestItBugsnag\Tests\Subscriber
 */
class BugsnagSubscriberTest extends TestCase
{
    /**
     * Test the handle error method
     *
     * @param bool $noErrorHandler
     *
     * @return void
     */
    public function testHandleError(bool $noErrorHandler = false)
    {
        $fixture = new BugsnagSubscriber(
            $handler = $this->createMock(Handler::class),
            ['exceptionHandler' => true, 'errorHandler' => true]
        );

        $args = $this->createMock(Enlight_Event_EventArgs::class);
        $args
            ->expects(static::once())
            ->method('get')
            ->with('subject')
            ->willReturn($front = $this->createMock(Enlight_Controller_Front::class));

        $front
            ->expects(static::once())
            ->method('getParam')
            ->with('noErrorHandler')
            ->willReturn($noErrorHandler);

        $handler
            ->expects($noErrorHandler ? static::never() : static::once())
            ->method('registerExceptionHandler')
            ->with(false);

        $handler
            ->expects($noErrorHandler ? static::never() : static::once())
            ->method('registerErrorHandler')
            ->with(false);

        $fixture->handleError($args);
    }

    /**
     * Test handle error without error handler
     *
     * @return void
     */
    public function testHandleErrorWithoutErrorHandler()
    {
        $this->testHandleError(true);
    }

    /**
     * Test that the correct events are subscribed
     *
     * @return void
     */
    public function testSubscribedEvents()
    {
        static::assertEquals(
            [
                'Enlight_Controller_Front_RouteShutdown' => ['handleError', 1000],
                'Enlight_Controller_Front_PostDispatch' => ['handleError', 1000],
                'Shopware_Console_Add_Command' => ['handleError', 1000],
            ],
            BugsnagSubscriber::getSubscribedEvents()
        );
    }

    /**
     * Test that the shopware subscriber interface is implemented
     *
     * @return void
     */
    public function testSubscriberInterface()
    {
        static::assertInstanceOf(
            SubscriberInterface::class,
            new BugsnagSubscriber($this->createMock(Handler::class), [])
        );
    }
}
