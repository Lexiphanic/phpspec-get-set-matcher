<?php

namespace Lexiphanic\PhpSpecGetSetMatcher;

use PhpSpec\Extension as ExtensionInterface;
use PhpSpec\ServiceContainer;

class Extension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     * @param mixed[]          $params
     */
    public function load(ServiceContainer $container, array $params)
    {
        $container->define(
            'lexiphanic.matchers.get_set',
            function ($container) {
                return new Matcher\GetSetMatcher();
            },
            ['matchers']
        );
    }
}
