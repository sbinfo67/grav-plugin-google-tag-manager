<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class GoogleTagManagerPlugin
 * @package Grav\Plugin
 *
 * Inserts the Google Tag Manager container in every HTML page: the script at
 * the top of <head>, the <noscript> fallback right after the opening <body>.
 * Both go into the final output, so the theme needs no change.
 */
class GoogleTagManagerPlugin extends Plugin
{
    /** A GTM container ID is "GTM-" followed by letters and digits. */
    private const CONTAINER_ID_PATTERN = '/^GTM-[A-Z0-9]+$/';

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        $this->enable([
            'onOutputGenerated' => ['onOutputGenerated', 0]
        ]);
    }

    /**
     * Insert the container into the rendered page.
     *
     * The page frontmatter can override the plugin settings, e.g.
     * `google-tag-manager: false` to leave one page out.
     */
    public function onOutputGenerated(Event $event): void
    {
        $page = $event['page'] ?? $this->grav['page'];
        if (!$page || $page->templateFormat() !== 'html') {
            return;
        }

        $config = $this->mergeConfig($page);
        if (!$config->get('enabled')) {
            return;
        }

        $containerId = $this->containerId((string) $config->get('container_id', ''));
        if ($containerId === null) {
            return;
        }

        $this->grav->output = $this->insertContainer((string) $this->grav->output, $containerId);
    }

    /**
     * Normalize the configured container ID.
     *
     * An empty ID means the plugin is not configured yet: nothing is logged.
     * A malformed one is reported once in the log, not on every request.
     *
     * @return string|null The ID, or null when nothing must be inserted
     */
    private function containerId(string $value): ?string
    {
        $containerId = strtoupper(trim($value));
        if ($containerId === '') {
            return null;
        }

        if (!preg_match(self::CONTAINER_ID_PATTERN, $containerId)) {
            $cache = $this->grav['cache'];
            $key = 'google-tag-manager-invalid-' . md5($containerId);
            if (!$cache->contains($key)) {
                $this->grav['log']->warning(sprintf('Google Tag Manager plugin: container ID "%s" ignored, expected GTM-XXXXXXX', $value));
                $cache->save($key, true);
            }

            return null;
        }

        return $containerId;
    }

    /**
     * Put the head code at the top of <head>, after <meta charset> when the
     * theme declares one (it must stay within the first 1024 bytes), and the
     * noscript code right after <body>. A document without both tags is
     * left untouched.
     */
    private function insertContainer(string $html, string $containerId): string
    {
        $points = $this->findInsertionPoints($html);
        if ($points === null) {
            return $html;
        }
        [$at, $bodyEnd] = $points;

        return substr($html, 0, $at)
            . "\n" . $this->getHeadContainerCode($containerId)
            . substr($html, $at, $bodyEnd - $at)
            . "\n" . $this->getBodyContainerCode($containerId)
            . substr($html, $bodyEnd);
    }

    /**
     * Walk the document up to the opening <body> tag, skipping comments and
     * the text of script, style, title and textarea elements, so that a tag
     * written inside them (say '<body' in an inline script) is not taken for
     * the real one.
     *
     * @return array{0: int, 1: int}|null Where the head code goes (after
     *   <meta charset>, or after <head>) and the end of the <body> tag; null
     *   when the document has no <head> or no <body> tag
     */
    private function findInsertionPoints(string $html): ?array
    {
        $pattern = '/<!--|<(script|style|title|textarea)(?=[\s\/>])[^>]*>'
            . '|<head(?=[\s\/>])[^>]*>|<meta\s[^>]*charset\s*=[^>]*>|<body(?=[\s\/>])[^>]*>/i';
        $headEnd = null;
        $charsetEnd = null;
        $offset = 0;

        while (preg_match($pattern, $html, $match, PREG_OFFSET_CAPTURE, $offset)) {
            [$tag, $position] = $match[0];
            $end = $position + strlen($tag);

            if ($tag === '<!--') {
                // A comment ends at the first "-->", which may overlap "<!--".
                $close = strpos($html, '-->', $position + 2);
                if ($close === false) {
                    return null;
                }
                $offset = $close + 3;
                continue;
            }

            if (isset($match[1]) && $match[1][1] !== -1) {
                // The element's text ends at its first closing tag.
                $close = stripos($html, '</' . $match[1][0], $end);
                if ($close === false) {
                    return null;
                }
                $offset = $close;
                continue;
            }

            switch (strtolower(substr($tag, 1, 4))) {
                case 'head':
                    $headEnd = $headEnd ?? $end;
                    break;
                case 'meta':
                    if ($headEnd !== null && $charsetEnd === null) {
                        $charsetEnd = $end;
                    }
                    break;
                default: // body
                    return $headEnd === null ? null : [$charsetEnd ?? $headEnd, $end];
            }
            $offset = $end;
        }

        return null;
    }

    /**
     * Return the Google Tag Manager Head Tracking Code
     * @param string $gtmContainerId Validated GTM container ID
     * @return string
     */
    private function getHeadContainerCode(string $gtmContainerId): string
    {
        return <<<HTML
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$gtmContainerId}');</script>
<!-- End Google Tag Manager -->

HTML;
    }

    /**
     * Return the Google Tag Manager Body Tracking Code
     * @param string $gtmContainerId Validated GTM container ID
     * @return string
     */
    private function getBodyContainerCode(string $gtmContainerId): string
    {
        return <<<HTML
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={$gtmContainerId}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

HTML;
    }
}
