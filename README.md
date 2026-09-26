# Google Tag Manager pour Grav

> **In English.** A Grav plugin that inserts a Google Tag Manager container in every
> HTML page, without touching the theme: the GTM script right at the top of `<head>`
> (after `<meta charset>`), and its `<noscript>` fallback right after the opening
> `<body>` tag, as Google recommends. Works with Grav 1.7 and Grav 2 (tested up to
> 2.2.1 with Admin2 2.1.24). Set `container_id` to your `GTM-XXXXXXX` ID; an empty or
> malformed ID inserts nothing. A page can opt out with `google-tag-manager: false` in
> its frontmatter, or use another container with
> `google-tag-manager: { container_id: GTM-… }`.
> XML, JSON and other non-HTML responses are left untouched. The plugin does not
> handle consent: use Consent Mode in GTM or a consent banner. Up to 0.2.0 the plugin
> was named `gtm-plugin`: see below to move to `google-tag-manager`.

## Installation

L'extension n'est pas au catalogue GPM : on l'installe à la main, dans un dossier qui
doit s'appeler `google-tag-manager`.

Avec git, depuis la racine de Grav :

```bash
git clone https://github.com/sbinfo67/grav-plugin-google-tag-manager user/plugins/google-tag-manager
```

Ou en téléchargeant l'archive de la
[dernière version](https://github.com/sbinfo67/grav-plugin-google-tag-manager/releases/latest),
puis en renommant le dossier décompressé en `user/plugins/google-tag-manager`.

Videz ensuite le cache (`bin/grav clearcache`, ou le contenu de `cache/`).

## Réglages

Dans Admin2 ou l'administration classique : Extensions, puis **Google Tag Manager**.
Activez l'extension et saisissez l'identifiant du conteneur, affiché en haut à droite
de l'espace de travail Tag Manager.

Ou dans `user/config/plugins/google-tag-manager.yaml` :

```yaml
enabled: true
container_id: GTM-XXXXXXX
```

| Clé | Par défaut | Rôle |
|---|---|---|
| `enabled` | `false` | Active l'extension |
| `container_id` | vide | Identifiant du conteneur, de la forme `GTM-XXXXXXX` |

L'identifiant est ramené en majuscules et débarrassé de ses espaces. Tant qu'il est
vide, rien n'est inséré et rien n'est journalisé. S'il n'a pas la forme `GTM-` suivie
de lettres et de chiffres, rien n'est inséré non plus, et un avertissement est écrit
une fois dans `logs/grav.log`.

### Page par page

L'en-tête d'une page peut changer ces réglages pour elle seule :

```yaml
google-tag-manager: false    # pas de GTM sur cette page
```

```yaml
google-tag-manager:
  container_id: GTM-AUTRE42  # un autre conteneur
```

## Ce que fait l'extension

Une fois la page produite par le thème, l'extension insère :

- le script de GTM en haut du `<head>`, juste après `<meta charset>` s'il existe
  (la déclaration d'encodage doit rester dans les 1 024 premiers octets), sinon
  juste après `<head>` ;
- le `<noscript>` et son iframe juste après la balise `<body>`.

Le code est celui que fournit Google, avec la couche de données `dataLayer`. Le thème
n'a rien à appeler. Seules les réponses HTML sont modifiées, et l'administration n'est
jamais touchée.

## Consentement

L'extension charge GTM sur chaque page, sans attendre le consentement du visiteur.
Sur un site soumis au RGPD, configurez le mode Consentement (Consent Mode) dans GTM,
ou un bandeau de gestion du consentement qui s'y raccorde.

## Passage depuis `gtm-plugin`

Jusqu'à la 0.2.0, l'extension s'appelait `gtm-plugin`. Depuis la 1.0.0, elle s'appelle
`google-tag-manager`, et tous ses noms ont suivi :

1. Supprimez le dossier `user/plugins/gtm-plugin` et installez celui-ci dans
   `user/plugins/google-tag-manager`.
2. Renommez `user/config/plugins/gtm-plugin.yaml` en `google-tag-manager.yaml`. Les clés
   `enabled` et `container_id` ne changent pas.
3. Dans l'en-tête des pages, remplacez `gtm-plugin:` par `google-tag-manager:`.
4. Videz le cache.

Depuis la 0.1.0, le code n'est plus inséré par `assets.js()` mais directement dans la
page, en haut du `<head>` : retirez tout code GTM ajouté à la main dans le thème, sans
quoi le conteneur serait chargé deux fois.

## Crédits

Extension créée par James H Murphy
([jaymurphy1997/grav-plugin-gtm-plugin](https://github.com/jaymurphy1997/grav-plugin-gtm-plugin)),
elle-même inspirée de l'extension
[Grav Ganalytics](https://github.com/escopecz/grav-ganalytics). Reprise et corrigée
par SBINFO à partir de la 0.2.0, renommée `google-tag-manager` en 1.0.0.

## Licence

MIT, voir [LICENSE](LICENSE).
