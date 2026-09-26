# Google Tag Manager Plugin

The **Gtm Plugin** is an extension for [Grav CMS](https://github.com/getgrav/grav). It inserts the Google Tag Manager container in every HTML page without any change to the theme: the GTM script at the top of `<head>` (right after `<meta charset>`), and its `<noscript>` fallback right after the opening `<body>` tag, as Google recommends.

Works with Grav 1.7 and Grav 2 (tested on 1.7.53.4, 2.0.26, 2.1.10 and 2.2.1, with Admin2 2.1.24).

## Installation

The plugin is not in the GPM directory yet, so it is installed manually, in a folder named `gtm-plugin`.

With git, from the root of your Grav installation:

    git clone https://github.com/jaymurphy1997/grav-plugin-gtm-plugin user/plugins/gtm-plugin

Or download the zip-version of this repository, unzip it under `/your/site/grav/user/plugins`, then rename the folder to `gtm-plugin`. You should now have all the plugin files under

    /your/site/grav/user/plugins/gtm-plugin

Clear the cache afterwards (`bin/grav clearcache`).

## Configuration

Before configuring this plugin, you should copy the `user/plugins/gtm-plugin/gtm-plugin.yaml` to `user/config/plugins/gtm-plugin.yaml` and only edit that copy.

Here is the default configuration:

```yaml
enabled: false
container_id: ''
```

* `enabled`: turns the plugin on.
* `container_id`: the GTM Container ID from Google Tag Manager, of the form `GTM-XXXXXXX`.

The ID is upper-cased and trimmed. While it is empty, nothing is inserted and nothing is logged. If it does not look like `GTM-` followed by letters and digits, nothing is inserted either, and a warning is written once to `logs/grav.log`.

Note that if you use the Admin Plugin (or Admin2), a file with your configuration named gtm-plugin.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

### Per page

A page can override these settings in its frontmatter:

```yaml
gtm-plugin: false            # no GTM on this page
```

```yaml
gtm-plugin:
  container_id: GTM-OTHER42  # another container
```

Only HTML responses are changed: XML sitemaps, feeds and JSON output are left untouched, and so is the admin.

## Usage
First you will need to create a Google Tag Manager container.  Follow the steps below:
1. Sign in to your [Google Tag Manager account](https://tagmanager.google.com/).
2. Select an account, then a container.
3. In the upper right hand of the _WORKSPACE_ _OVERVIEW_, find the ID starting with "GTM-" - it is your **GTM CONTAINER ID** (a string like _GTM-XXXXXXX_)

Now add this Container ID to the Grav Gtm Plugin

1. Login to your Grav CMS Admin
2. Click on the Plugins menu on the left side of your Admin panel.
3. Click on the "Google Tag Manager" link.
4. Enable the plugin
5. Add the Container ID to the "GTM Container ID" plugin field.
6. Click on the Save button (upper right hand corner).

## Consent

The plugin loads GTM on every page without waiting for the visitor's consent. Where the GDPR applies, set up Consent Mode in GTM, or a consent banner that connects to it.

## Upgrading from 0.1.0

The configuration key is unchanged. The plugin is now disabled by default: an existing `user/config/plugins/gtm-plugin.yaml` with `enabled: true` keeps it on. The code is no longer added through `assets.js()` but written into the page itself: remove any GTM code added by hand to the theme, or the container would load twice.

## Credits

Inspired by the Grav Ganalytics Plugin, John Linhart (admin@escope.cz), Christian Worreschk (cw@marsec.de)
https://github.com/escopecz/grav-ganalytics Thanks!

## To Do

- [ ] Allow different 'dataLayer' names
- [ ] Add website and page information to the dataLayer to allow marketing tag capture and future analysis
