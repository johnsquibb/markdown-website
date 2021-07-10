<?php

namespace Website\Application\Controller;

use Parsedown;
use PhpMicroframework\Framework\Controller\TemplateController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class MarkdownController extends ApplicationTemplateController
{
    protected function renderMarkdown(string $path, array $context = []): string
    {
        $template = "{$path}.md";
        $templatePath = dirname(__DIR__, 3) . '/markdown';
        $loader = new FilesystemLoader($templatePath);
        $twig = new Environment($loader);

        $markdown = $twig->render($template, $context);

        $parsedown = new Parsedown();
        return $parsedown->text($markdown);
    }
}
