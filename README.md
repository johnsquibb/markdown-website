# markdown-website

A website framework that generates and caches pages from Markdown files.

Built on [johnsquibb/php-microframework](https://github.com/johnsquibb/php-microframework).

## Features

- Automatic routing based on markdown directory and file structure.
- Caches complete static HTML web pages to cache directory when navigating the site.

## Development Status

The framework is currently in development and subject to frequent change. A stable version with
tagged release will be made available when ready.

## Installation

`composer create-project johnsquibb/markdown-website:dev-main`

## Usage

Use the builtin PHP server to serve from the public directory during development:

`php -S localhost:8080 -t public`

Then visit: http://localhost:8080 to view the website.

Create markdown files and navigate the site to create or update the cache for each page.

### Testing static renders

To test static HTML cache renders:

`php -S localhost:8080 -t cache`

Then visit: http://localhost:8080 to view the static content.

**Note**: Copy assets from the `public` to `cache` directory to view custom assets, styles, scripts
when testing static renders.

## How caching works

While navigating pages in development mode, any found Markdown files will produce a web page. The
complete HTML, including header and footer will be rendered into a static HTML file matching the
file name and directory structure of the loaded Markdown file. All relative links will be suffixed
with `.html` to faciliate connecting up other cached content.

## Exporting cached files

Copy the contents of the cache directory to your website. Only the HTML is cached. Any assets in the
public directory such as CSS, images, etc. must be manually copied to your website directory. A
future version may support copying these files automatically.

[Learn more](http://johnsquibb.com/resources-for-building-static-websites.html) about hosting static
HTML websites using Amazon S3.
