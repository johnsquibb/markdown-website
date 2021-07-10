<?php

namespace Website\Application\Controller;

use PhpMicroframework\Framework\Controller\TemplateController;

class ApplicationTemplateController extends TemplateController
{
    /**
     * Get the template path.
     * Override to set custom template path local to project application directory.
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return dirname(__DIR__, 3) . '/templates';
    }
}
