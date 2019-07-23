<?php

declare(strict_types=1);

namespace BestItBugsnag\Subscriber;

use Bugsnag\Handler;
use Enlight\Event\SubscriberInterface;
use Enlight_Controller_EventArgs;
use Enlight_Event_EventArgs;
use function method_exists;

/**
 * Registers the events for handling errors with bugsnag.
 *
 * @package BestItBugsnag\Subscriber
 */
class BugsnagSubscriber implements SubscriberInterface
{
    /**
     * The bugsnag handler
     *
     * @var Handler
     */
    private $bugsnagHandler;

    /**
     * The plugin config values
     *
     * @var array
     */
    private $config;

    /**
     * BasicSubscriber constructor.
     *
     * @param Handler $bugsnagHandler
     * @param array $config
     */
    public function __construct(Handler $bugsnagHandler, array $config)
    {
        $this->bugsnagHandler = $bugsnagHandler;
        $this->config = $config;
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
     * @param Enlight_Event_EventArgs $args
     *
     * @return void
     */
    public function handleError(Enlight_Event_EventArgs $args)
    {
        $front = $args->get('subject');

        if (!method_exists($front, 'getParam') || !$front->getParam('noErrorHandler')) {
            if (isset($this->config['exceptionHandler'])) {
                $this->bugsnagHandler->registerExceptionHandler(false);

                // restore default error handler
                restore_exception_handler();
            }

            if (isset($this->config['errorHandler'])) {
                $this->bugsnagHandler->registerErrorHandler(false);

                // restore default error handler
                restore_error_handler();
            }
        }
    }
}
