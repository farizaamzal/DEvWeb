# Projet : Prévisions météo et climat

### Date de réalisation : avril 2025
### Année universitaire : 2024-2025
### Établissement : CY Cergy Paris Université

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

## Introduction

Bienvenue sur le site web de notre projet de développement web : Météo&Climat.
Ce projet, réalisé dans le cadre de l'UE Web (L2 Informatique, S4), est une application web interactive dédiée aux prévisions météorologiques en France. Elle permet aux utilisateurs de consulter la météo via une carte interactive ou une sélection par localité, d'explorer des statistiques sur les consultations, et d'accéder à des contenus spécialisés comme l'image astronomique du jour de la NASA. Le site est conçu pour être ergonomique, esthétique, et multilingue (français/anglais), avec un mode clair/sombre personnalisable.

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

## Équipe de développement

### Nadjib MOUSSAOUI
**Email** : nadjib.moussaoui15@gmail.com
**Github** : [Nadjibmss](https://github.com/Nadjibmss)

### Fariza Amzal
**Email** : amzalfariza44@gmail.com
**Github** : [farizaamzal](https://github.com/farizaamzal)

Nous avons travaillé en binôme pour concevoir, développer, et tester ce site, en appliquant nos compétences en HTML5, CSS3, PHP, et gestion d'APIs REST (JSON et XML).

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

## Contenu du site

Le site est structuré autour des pages suivantes, répondant aux exigences du cahier des charges :

1. `index.php` : Page d'accueil affichant une image aléatoire, la météo locale basée sur l'adresse IP, et un formulaire de recherche météo.
2. `previsions.php` : Sélection de régions via une carte interactive (balises <map>, <area>) ou des menus déroulants pour accéder aux prévisions.
3. `regions.php` : Navigation hiérarchique (régions, départements, villes) pour choisir une localité.
4. `meteo.php` : Affichage des prévisions météorologiques détaillées (jour actuel et jours suivants) pour la ville sélectionnée.
5. `statistiques.php` : Visualisation d'un histogramme des villes les plus consultées, basé sur un fichier CSV.
6. `tech.php`: Page développeur présentant l'image ou vidéo du jour de la NASA (API APOD, JSON) et la géolocalisation approximative via trois APIs (GeoPlugin, ipinfo.io, WhatIsMyIP ; JSON et XML).
7. `planDuSite.php`: Plan du site pour une navigation rapide vers toutes les sections.

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

## Objectifs

* Développer un site web fonctionnel en architecture trois tiers (client, serveur, données) pour consulter les prévisions météo en France.
* Permettre la sélection intuitive de localités via une carte interactive et des menus déroulants, en s'appuyant sur des fichiers CSV pour les données statiques.
* Intégrer des APIs REST (OpenWeatherMap, NASA APOD, GeoPlugin, etc.) pour des données dynamiques en JSON et XML.
* Stocker les consultations dans un fichier CSV côté serveur et mémoriser la dernière ville consultée via un cookie côté client.
* Offrir une personnalisation de l'affichage (mode clair/sombre via cookie, multilinguisme français/anglais).
* Générer des statistiques graphiques (histogramme) pour analyser l'utilisation du site.
* Assurer une interface esthétique, ergonomique, et accessible, validée pour HTML5, CSS3, et PHP 8.

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

## Technologies utilisées

Le site a été construit avec les technologies suivantes :

1. PHP 8 : Logique serveur, gestion des APIs, traitement des fichiers CSV, et génération dynamique des pages.
2. HTML5 : Structure des pages, avec utilisation des balises <map>, <area>, <figure>, <figcaption>, et <video> pour la page tech.php.
3. CSS3 : Deux chartes graphiques (clair : styles.css, sombre : styleNight.css) avec animations (nuages, soleil) et design responsive.
4. JavaScript : Interactivité client (bascule thème/langue, formulaires).
5. APIs REST :
    1. OpenWeatherMap : Prévisions météorologiques (JSON).
    2. NASA APOD : Image/vidéo astronomique du jour (JSON).
    3. GeoPlugin, ipinfo.io, WhatIsMyIP : Géolocalisation par IP (JSON et XML).
6. CSV : Données statiques (v_region_2024.csv, v_departement_2024.csv, cities.csv) et stockage des consultations (consultations.csv).
7. Cookies : Mémorisation de la langue, du thème, et de la dernière ville consultée.
8. Outils de collaboration : GitHub pour la gestion de version.

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

## Contributions

Nous accueillons volontiers toute suggestion d'amélioration ! Vous pouvez contribuer en ouvrant une issue ou en soumettant une pull request sur le dépôt GitHub du projet.

**Merci pour votre intérêt !**

