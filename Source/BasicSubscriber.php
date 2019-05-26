<?php

declare(strict_types=1);

namespace BestItBugsnag;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_EventArgs;
use Enlight_Event_EventArgs;

/**
 * Registers the events for handling errors with bugsnag.
 *
 * @package BestItBugsnag
 */
class BasicSubscriber implements SubscriberInterface
{
    /**
     * The injected client.
     *
     * @var BugsnagClient
     */
    private $bugsnagClient;

    /**
     * BasicSubscriber constructor.
     *
     * @param BugsnagClient $bugsnagClient
     */
    public function __construct(BugsnagClient $bugsnagClient)
    {
        $this->bugsnagClient = $bugsnagClient;
    }

    /**
     * Returns the relevant error events for bugsnag.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Front_RouteShutdown' => ['handleError', 1000],
            'Enlight_Controller_Front_PostDispatch' => ['handleError', 1000],
            'Shopware_Console_Add_Command' => ['handleError', 1000],
        ];
    }

    /**
     * Register bugsnag for an error.
     *
     * @param Enlight_Controller_EventArgs $args
     *
     * @return void
     */
    public function handleError(Enlight_Event_EventArgs $args)
    {
        $front = $args->getSubject();

        if (!$front->getParam('noErrorHandler')) {
            $this->bugsnagClient->registerHandler();
        }
    }
}
