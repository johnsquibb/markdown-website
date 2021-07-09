<?php

namespace Website\Application\Controller;

use DOMDocument;
use PhpMicroframework\Framework\Controller\Response\HtmlResponse;
use PhpMicroframework\Framework\Controller\Response\ResponseInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Website\Application\Application;

class ApplicationController extends MarkdownController
{
    /**
     * Dynamically load all pages by URI if the markdown template exists, else 404.
     * Load the index page by default.
     * @param string ...$segment
     * @return ResponseInterface
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function handle(string ...$segment): ResponseInterface
    {
        // Show home page by default.
        $path = 'index';

        if (count($segment)) {
            $path = implode('/', $segment);
        }

        try {
            $body = $this->renderMarkdown($path);
            $html = $this->render('containers/default.html.twig', ['body' => $body]);

            $this->cacheStaticPage($path, $html);

            return new HtmlResponse($html);
        } catch (LoaderError $e) {
            Application::error404();
        }
    }

    private function cacheStaticPage(string $path, string $html): void
    {
        $publicPath = dirname(__DIR__, 3) . '/cache';
        $file = $publicPath . '/' . $path . '.html';

        $directory = dirname($file);
        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }

        $html = $this->replaceLocalLinksWithStaticLinks($html);

        file_put_contents($file, $html);
    }

    private function buildRelativeUrl(array $parts): string
    {
        $url = $parts['path'] ?? '';
        if (isset($parts['query'])) {
            $url .= '?' . $parts['query'];
        }

        if (isset($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }

        return $url;
    }

    private function replaceLocalLinksWithStaticLinks(string $html): string
    {
        // Suffix relative site links.
        $dom = new DomDocument();
        $dom->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR );
        $hrefs = $dom->getElementsByTagName('a');

        $localHrefs = [];
        foreach ($hrefs as $href) {
            $attributes = $href->attributes;
            foreach ($attributes as $attribute) {
                if ($attribute->name === 'href') {
                    $value = $attribute->value;
                    if (!str_starts_with($value, 'http')) {
                        $localHrefs[] = $value;
                    }
                }
            }
        }

        foreach ($localHrefs as $localHref) {
            // Parse anchors.
            $parts = parse_url($localHref);

            // Apply suffix to path.
            if (isset($parts['path'])) {
                if ($parts['path'] === '/') {
                    $parts['path'] = '/index';
                }

                $parts['path'] .= '.html';
            }

            $href = $this->buildRelativeUrl($parts);

            // Replace relative URL with suffixed URL.
            // Replace exact matches only, i.e. where <a href="match"... with quote boundaries.
            $exactMatch = '"' . str_replace('&', '&amp;', $localHref) . '"';
            $exactReplace = '"' . $href . '"';
            $html = str_replace($exactMatch, $exactReplace, $html);
        }

        return $html;
    }
}