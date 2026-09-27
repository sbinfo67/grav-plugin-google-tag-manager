# Google Tag Manager for Grav

A Grav plugin that inserts a Google Tag Manager container in every HTML page, without
touching the theme: the GTM script right at the top of `<head>`, and its `<noscript>`
fallback right after the opening `<body>` tag, as Google recommends. Works with Grav 1.7
and Grav 2, tested up to Grav 2.2.1 with Admin2 2.1.24.

## Installation

From Admin2 (Plugins, then Add) or the classic admin, or on the command line:

```bash
bin/gpm install google-tag-manager
```

Or by hand, in a folder that must be named `google-tag-manager`. With git, from the root
of your Grav installation:

```bash
git clone https://github.com/sbinfo67/grav-plugin-google-tag-manager user/plugins/google-tag-manager
```

Or download the archive of the
[latest release](https://github.com/sbinfo67/grav-plugin-google-tag-manager/releases/latest),
then rename the unzipped folder to `user/plugins/google-tag-manager`, and clear the cache
(`bin/grav clearcache`, or empty the `cache/` folder).

## Configuration

In Admin2 or the classic admin: Plugins, then **Google Tag Manager**. Enable the plugin
and enter the container ID, shown at the top right of the Tag Manager workspace.

Or in `user/config/plugins/google-tag-manager.yaml`:

```yaml
enabled: true
container_id: GTM-XXXXXXX
```

| Key | Default | Purpose |
|---|---|---|
| `enabled` | `false` | Turns the plugin on |
| `container_id` | empty | Container ID, of the form `GTM-XXXXXXX` |

The ID is upper-cased and trimmed. While it is empty, nothing is inserted and nothing is
logged. If it does not look like `GTM-` followed by letters and digits, nothing is
inserted either, and a warning is written once to `logs/grav.log`.

The settings screen is available in English and French.

### Per page

A page can override these settings for itself in its frontmatter:

```yaml
google-tag-manager: false    # no GTM on this page
```

```yaml
google-tag-manager:
  container_id: GTM-OTHER42  # another container
```

## What the plugin does

Once the theme has rendered the page, the plugin inserts:

- the GTM script at the top of `<head>`, right after `<meta charset>` when there is one
  (the encoding declaration must stay within the first 1,024 bytes), otherwise right
  after `<head>`;
- the `<noscript>` and its iframe right after the `<body>` tag.

Comments and the text of `script`, `style`, `title` and `textarea` elements are skipped
while looking for these tags, so a `<body` or `<meta charset` written inside them is not
taken for the real one.

The code is the one Google provides, with the `dataLayer` data layer. The theme has
nothing to call. Only HTML responses are changed: XML sitemaps, feeds and JSON output are
left untouched, and so are the admin and the API.

## Consent

The plugin loads GTM on every page, without waiting for the visitor's consent. Where the
GDPR applies, set up Consent Mode in GTM, or a consent banner that connects to it.

## Moving from `gtm-plugin`

Up to 0.2.0, the plugin was named `gtm-plugin`. Since 1.0.0 it is named
`google-tag-manager`, and all its names followed:

1. Delete the `user/plugins/gtm-plugin` folder and install this one in
   `user/plugins/google-tag-manager`.
2. Rename `user/config/plugins/gtm-plugin.yaml` to `google-tag-manager.yaml`. The
   `enabled` and `container_id` keys do not change.
3. In page frontmatter, replace `gtm-plugin:` with `google-tag-manager:`.
4. Clear the cache.

Since 0.2.0, the code is no longer added through `assets.js()` but written into the page
itself, at the top of `<head>`: remove any GTM code added by hand to the theme, or the
container would load twice.

## Credits

Plugin created by James H Murphy
([jaymurphy1997/grav-plugin-gtm-plugin](https://github.com/jaymurphy1997/grav-plugin-gtm-plugin)),
itself inspired by the [Grav Ganalytics](https://github.com/escopecz/grav-ganalytics)
plugin. Taken over and fixed by SBINFO from 0.2.0, renamed `google-tag-manager` in
1.0.0.

## License

MIT, see [LICENSE](LICENSE).
