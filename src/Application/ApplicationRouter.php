<?php

namespace Website\Application;

use PhpMicroframework\Framework\Router;
use Website\Application\Controller\ApplicationController;

/**
 * Custom router allows for fully dynamic path loading of application resources.
 * @package PhpMicroframework\Framework
 */
final class ApplicationRouter extends Router
{
    public static function current(): void
    {
        self::$method = 'handle';
        self::$controller = ApplicationController::class;

        $current = self::getRequestPath();

        $parts = array();

        if (strlen($current) > 0) {
            $parts = explode('/', $current);
        }

        self::$arguments = $parts;
    }
}
