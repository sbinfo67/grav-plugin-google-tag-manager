# v1.0.0
## 2026-09-27

1. [](#improved)
    * L'extension s'appelle désormais `google-tag-manager` : dossier `user/plugins/google-tag-manager`, configuration `user/config/plugins/google-tag-manager.yaml`, clé `google-tag-manager:` dans l'en-tête des pages. Le README explique le passage depuis `gtm-plugin`
    * Dépôt renommé `grav-plugin-google-tag-manager`, les anciennes adresses redirigent
    * Testée sur Grav 1.7.53.4 et 2.2.1, avec Admin2 2.1.24

# v0.2.0
## 2026-09-27

1. [](#bugfix)
    * Le `<noscript>` de GTM n'était jamais posé : l'extension cherchait `<body>` dans le contenu de la page et non dans le document. Il est désormais inséré juste après la balise `<body>` de la page produite
    * Le fichier `gtm-plugin.yaml` livré activait l'extension avec un identifiant factice : dès l'installation, chaque page chargeait `gtm.js?id=Add the Container ID here`. L'extension est maintenant désactivée par défaut, avec un identifiant vide
    * Un identifiant vide n'écrit plus une ligne dans `grav.log` à chaque visite
    * Une page dont le contenu contenait une balise `<body>` recevait l'iframe au milieu de son texte
2. [](#improved)
    * Le script de GTM est placé en haut du `<head>`, après `<meta charset>`, comme le recommande Google, quel que soit l'endroit où le thème appelle `assets.js()`
    * L'identifiant est ramené en majuscules et débarrassé de ses espaces. S'il n'a pas la forme `GTM-XXXXXXX`, rien n'est inséré et un avertissement est journalisé une seule fois
    * Seules les réponses HTML sont modifiées : plans de site XML, flux et JSON restent intacts
    * Code de Google repris à l'identique, sans commentaires HTML à l'intérieur du `<script>`
    * Compatible Grav 1.7 et Grav 2, testée sur 1.7.53.4, 2.0.26, 2.1.10 et 2.2.1, avec Admin2 2.1.24
    * Écran de configuration traduit en français, avec un exemple d'identifiant
    * Dossier `vendor/` et fichiers Composer retirés : l'extension tient en un fichier, sans dépendance
3. [](#new)
    * L'en-tête d'une page peut désactiver GTM (`gtm-plugin: false`) ou choisir un autre conteneur (`gtm-plugin: { container_id: … }`)

# v0.1.0
##  08/03/2024

1. [](#new)
    * Created plugin to insert GTM Container code with out needing any code changes.
