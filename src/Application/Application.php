<?php

namespace Website\Application;

use PhpMicroframework\Framework\Core;

/**
 * Class Application provides custom framework functionality by overloading Core methods.
 * @package PhpMicroframework\Application
 */
class Application extends Core
{
    public static function initialize(): void
    {
        parent::initialize();

        // Overload core routing discovery with custom application router.
        ApplicationRouter::current();
    }
}
