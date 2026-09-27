# v1.0.0
## 2026-09-27

1. [](#improved)
    * The plugin is now named `google-tag-manager`: folder `user/plugins/google-tag-manager`, configuration `user/config/plugins/google-tag-manager.yaml`, `google-tag-manager:` key in page frontmatter. The README explains how to move from `gtm-plugin`
    * Repository renamed `grav-plugin-google-tag-manager`; the old URLs redirect
    * Tested on Grav 1.7.53.4 and 2.2.1, with Admin2 2.1.24

# v0.2.0
## 2026-09-27

1. [](#bugfix)
    * The GTM `<noscript>` was never inserted: the plugin looked for `<body>` in the page content instead of the rendered document. It is now inserted right after the `<body>` tag of the output
    * The shipped `gtm-plugin.yaml` enabled the plugin with a placeholder ID, so every page loaded `gtm.js?id=Add the Container ID here` right after installation. The plugin is now disabled by default, with an empty ID
    * An empty ID no longer writes a line to `grav.log` on every request
    * A page whose content contained a `<body>` tag got the iframe in the middle of its text
2. [](#improved)
    * The GTM script is placed at the top of `<head>`, after `<meta charset>`, as Google recommends, wherever the theme calls `assets.js()`
    * The container ID is upper-cased and trimmed. If it is not of the form `GTM-XXXXXXX`, nothing is inserted and a warning is logged once
    * Only HTML responses are changed: XML sitemaps, feeds and JSON are left untouched
    * Google's snippet is used as is, without HTML comments inside the `<script>`
    * Grav 1.7 and Grav 2 compatible, tested on 1.7.53.4, 2.0.26, 2.1.10 and 2.2.1, with Admin2 2.1.24
    * French translation of the settings, with an example ID
    * `vendor/` and Composer files removed: the plugin is a single file with no dependency
3. [](#new)
    * A page can turn GTM off (`gtm-plugin: false`) or use another container (`gtm-plugin: { container_id: … }`) in its frontmatter

# v0.1.0
##  08/03/2024

1. [](#new)
    * Created plugin to insert GTM Container code with out needing any code changes.
