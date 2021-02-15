[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/shopware-blog/shopware-bugsnag/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/shopware-blog/shopware-bugsnag/?branch=master)

# Shopware-Bugsnag Plugin

This is a very simple implementation of bugsnag.com nothing more :)

## Installation

Either create a Plugin.zip using the create_package.sh and install it through your shopware backend or require the plugin with composer

``
    $ composer require best-it/shopware-bugsnag
`` 

## Configuration

API Key:

Before activating the plugin make sure to enter you bugsnag API key. 
You can do this in the plugin config in the backend or through the BUGSNAG_API_KEY environment variable.

**If your API Key is empty and no environment is set your shop will throw an exception.**

## Testing

PHPUnit tests enabled and should be written in the tests folder. To run them use

``
    $ ./vendor/bin/phpunit
``

To see whether the plugin works in your environment, implement this in your code and check in the bugsnag portal.

``
    $this->get('best_it_bugsnag.client')->notifyException(new \Exception('Test error'));
``
