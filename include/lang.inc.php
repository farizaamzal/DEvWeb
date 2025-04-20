<?php

/**
 * @file lang.inc.php
 * @brief Fichier de gestion des traductions pour le site Météo&Climat
 * @author Fariza Amzal , Nadjib Moussaoui 
 * @version 1.0
 * @date Avril 2025
 * @details Définit un tableau global de traductions pour les textes statiques de l'interface en français et anglais. Fournit la fonction t() pour récupérer les textes traduits. Utilisé dans toutes les pages pour assurer une interface multilingue.
 */

/**
 * @brief Tableau des traductions
 * @var array $translations Tableau associatif contenant les traductions en français ('fr') et anglais ('en')
 * @details Contient les clés pour les titres, menus, libellés, et messages statiques utilisés dans les pages comme index.php, previsions.php, regions.php, meteo.php, tech.php, statistiques.php, et planDuSite.php.
 */
// Définit les traductions pour les textes statiques de toutes les pages, y compris le menu
$translations = [
    'fr' => [
        // Menu (liens dans header.inc.php)
        'menu_home' => 'Accueil',
        'menu_forecast' => 'Prévisions',
        'menu_tech' => 'Page développeur',
        'menu_stats' => 'Statistiques',
        'menu_sitemap' => 'Plan du site',

        // index.php
        'title_index' => 'Accueil - Météo&Climat',
        'welcome' => 'Bienvenue sur Météo&amp;Climat !',
        'intro_1' => 'Découvrez en un coup d’œil la météo qu’il fait chez vous et partout en France !',
        'intro_2' => '☀️ Soleil, 🌧️ pluie, ⛈️ orages… Soyez toujours prêt grâce à nos mises à jour fiables et détaillées.',
        'search_label' => 'Rechercher la météo d’une ville :',
        'search_placeholder' => 'Entrez une ville',
        'search_button' => 'Rechercher',
        'last_city' => 'Dernière ville consultée :',
        'last_city_date' => 'Consulté le',
        'revoir' => 'Revoir cette météo',
        'meteo_title' => 'Météo pour',
        'meteo_last_title' => 'Météo de votre dernière consultation :',
        'meteo_local_title' => 'Météo à',
        'temperature' => 'Température',
        'description' => 'Description',
        'humidity' => 'Humidité',
        'wind_speed' => 'Vitesse du vent',
        'weather_icon' => 'Icône météo',
        'search_error' => 'Impossible de récupérer la météo pour ',
        'city_not_found' => 'Ville non trouvée : ',
        'conclusion' => 'Restez informé, restez préparé !',
        'links_title' => 'Consultez dès maintenant',
        'link_prevision' => 'La Météo de votre région',
        'link_tech' => 'L’image du jour de la NASA',
        'link_stats' => 'L’historique des villes consultées',
        'random_image_text' => 'Chaque visite vous réserve une surprise ! Découvrez aléatoirement l’une des images du projet à chaque rafraîchissement de la page.',

        // previsions.php
        'title_previsions' => 'Prévisions - Météo&Climat',
        'forecast_title' => 'Prévisions Météo',
        'select_region_map' => 'Sélectionnez une région sur la carte :',
        'map_caption' => 'Carte de la France avec une image cliquable',
        'consult_weather_title' => 'Consultez la météo',
        'consult_weather_text' => 'Entrez les informations ci-dessous pour obtenir les prévisions.',
        'label_region' => 'Région :',
        'label_departement' => 'Département :',
        'label_ville' => 'Ville :',
        'option_region' => 'Choisir une région',
        'option_departement' => 'Choisir un département',
        'option_departement_region' => 'Sélectionnez d’abord une région',
        'option_ville' => 'Choisir une ville',
        'option_ville_departement' => 'Sélectionnez d’abord un département',
        'submit_weather' => 'Afficher la météo',

        // statistiques.php
        'title_stats' => 'Statistiques - Météo&Climat',
        'stats_title' => 'Statistiques des consultations',
        'top_cities' => 'Top 10 des villes consultées',
        'no_data' => 'Aucune donnée de consultation disponible.',

        // tech.php
        'title_tech' => 'Page Développeur - Météo&Climat',
        'welcome_tech' => 'Bienvenue sur la page Développeur',
        'nasa_apod_title' => 'Image/Vidéo du jour (NASA)',
        'media_alt_image' => 'Image NASA',
        'video_error' => 'Votre navigateur ne supporte pas la vidéo.',
        'media_unknown' => 'Type de média inconnu : ',
        'media_unavailable' => ' (données du jour indisponibles ou format non supporté)',
        'media_alt_default' => 'Image par défaut NASA',
        'media_error' => 'Erreur : impossible de charger le média NASA (données du jour peut-être indisponibles).',
        'geoplugin_title' => 'Localisation GeoPlugin (IP : ',
        'ipinfo_title' => 'Localisation ipinfo.io (IP : ',
        'whatismyip_title' => 'Localisation whatismyip.com (IP : ',
        'city_label' => 'Ville : ',
        'country_label' => 'Pays : ',

        // regions.php
        'title_regions' => 'Régions et Départements - Météo&Climat',
        'selection_heading' => 'Sélection du département et de la ville',
        'region_selected' => 'Région sélectionnée : ',
        'return_map' => 'Retour à la carte',
        'choose_department' => 'Choisissez un département :',
        'cities_in_department' => 'Villes dans le département ',


        // planDuSite.php
        'title_sitemap' => 'Plan du Site - Météo&Climat',
        'sitemap_heading' => 'Plan du Site',
        'sitemap_description' => 'Retrouvez en un clin d’œil toutes les pages principales de notre site. Accédez rapidement à ce qui vous intéresse.',
        'nav_title' => 'Navigation rapide',
        'link_home' => 'Accueil – Explorez la météo et les images du jour',
        'link_forecast' => 'Prévisions – Consultez la météo de votre région',
        'link_tech' => 'Espace Développeur – Découverte technique du projet',
        'link_stats' => 'Historique – Voir les villes consultées',
        'link_sitemap' => 'Plan du site – Vous êtes ici !',

        //meteo.php
        'meteo_heading' => 'Prévisions Météo',
        'weather_for' => 'Météo pour',
        'region' => 'Région',
        'department' => 'Département',
        'current_conditions' => 'Conditions actuelles',
        'temperature' => 'Température',
        'description' => 'Description',
        'humidity' => 'Humidité',
        'wind_speed' => 'Vitesse du vent',
        'wind' => 'Vent',
        'current_icon' => 'Icône actuelle',
        'weather_icon' => 'Icône météo',
        'forecast_next_days' => 'Prévisions météo pour les prochains jours',
        'back_to_department' => 'Retour à la sélection du département',
        'back_to_map' => 'Retour à la carte',
        'last_consultation' => 'Dernière consultation',
    ],
    'en' => [
        // Menu (liens dans header.inc.php)
        'menu_home' => 'Home',
        'menu_forecast' => 'Forecast',
        'menu_tech' => 'Developer Page',
        'menu_stats' => 'Statistics',
        'menu_sitemap' => 'Site Map',

        // index.php
        'title_index' => 'Home - Weather&Climate',
        'welcome' => 'Welcome to Weather&amp;Climate!',
        'intro_1' => 'Discover the weather in France at a glance!',
        'intro_2' => '☀️ Sun, 🌧️ rain, ⛈️ storms… Be ready with our reliable and detailed updates.',
        'search_label' => 'Search the weather for a city:',
        'search_placeholder' => 'Enter a city',
        'search_button' => 'Search',
        'last_city' => 'Last city consulted:',
        'last_city_date' => 'Consulted on',
        'revoir' => 'Review this weather',
        'meteo_title' => 'Weather for',
        'meteo_last_title' => 'Weather from your last consultation:',
        'meteo_local_title' => 'Weather in',
        'temperature' => 'Temperature',
        'description' => 'Description',
        'humidity' => 'Humidity',
        'wind_speed' => 'Wind speed',
        'weather_icon' => 'Weather icon',
        'search_error' => 'Unable to retrieve weather for ',
        'city_not_found' => 'City not found: ',
        'conclusion' => 'Stay informed, stay prepared!',
        'links_title' => 'Explore now',
        'link_prevision' => 'Weather in your region',
        'link_tech' => 'NASA’s picture of the day',
        'link_stats' => 'History of consulted cities',
        'random_image_text' => 'Each visit brings a surprise! Discover a random project image on every page refresh.',

        
        // previsions.php
        'title_previsions' => 'Forecast - Weather&Climate',
        'forecast_title' => 'Weather Forecast',
        'select_region_map' => 'Select a region on the map:',
        'map_caption' => 'Map of France with a clickable image',
        'consult_weather_title' => 'Check the weather',
        'consult_weather_text' => 'Enter the information below to get the forecast.',
        'label_region' => 'Region:',
        'label_departement' => 'Department:',
        'label_ville' => 'City:',
        'option_region' => 'Choose a region',
        'option_departement' => 'Choose a department',
        'option_departement_region' => 'Select a region first',
        'option_ville' => 'Choose a city',
        'option_ville_departement' => 'Select a department first',
        'submit_weather' => 'Show weather',

        // statistiques.php
        'title_stats' => 'Statistics - Weather&Climate',
        'stats_title' => 'Consultation Statistics',
        'top_cities' => 'Top 10 Most Visited Cities',
        'no_data' => 'No consultation data available.',

        // tech.php
        'title_tech' => 'Developer Page - Weather&Climate',
        'welcome_tech' => 'Welcome to the Developer Page',
        'nasa_apod_title' => 'Picture/Video of the Day (NASA)',
        'media_alt_image' => 'NASA Image',
        'video_error' => 'Your browser does not support video.',
        'media_unknown' => 'Unknown media type: ',
        'media_unavailable' => ' (data for today unavailable or format not supported)',
        'media_alt_default' => 'Default NASA Image',
        'media_error' => 'Error: unable to load NASA media (data for today may be unavailable).',
        'geoplugin_title' => 'GeoPlugin Location (IP: ',
        'ipinfo_title' => 'ipinfo.io Location (IP: ',
        'whatismyip_title' => 'whatismyip.com Location (IP: ',
        'city_label' => 'City: ',
        'country_label' => 'Country: ',

        // regions.php
        'title_regions' => 'Regions and Departments - Weather&Climate',
        'selection_heading' => 'Select a department and city',
        'region_selected' => 'Selected region: ',
        'return_map' => 'Back to the map',
        'choose_department' => 'Choose a department:',
        'cities_in_department' => 'Cities in the department ',


        // planDuSite.php
        'title_sitemap' => 'Site Map - Weather&Climate',
        'sitemap_heading' => 'Site Map',
        'sitemap_description' => 'Find all the main pages of our site at a glance. Quickly access what interests you.',
        'nav_title' => 'Quick Navigation',
        'link_home' => 'Home – Explore weather and daily images',
        'link_forecast' => 'Forecast – Check the weather in your region',
        'link_tech' => 'Developer Space – Technical discovery of the project',
        'link_stats' => 'History – View consulted cities',
        'link_sitemap' => 'Site Map – You are here!',

        //meteo.php
        'meteo_heading' => 'Weather Forecast',
        'weather_for' => 'Weather for',
        'region' => 'Region',
        'department' => 'Department',
        'current_conditions' => 'Current Conditions',
        'temperature' => 'Temperature',
        'description' => 'Description',
        'humidity' => 'Humidity',
        'wind_speed' => 'Wind Speed',
        'wind' => 'Wind',
        'current_icon' => 'Current Icon',
        'weather_icon' => 'Weather Icon',
        'forecast_next_days' => 'Weather forecast for the next few days',
        'back_to_department' => 'Back to department selection',
        'back_to_map' => 'Back to the map',
        'last_consultation' => 'Last consultation',
    ],
];

/**
 * Fonction pour récupérer un texte traduit dans la langue spécifiée.
 * Cette fonction traduit les textes statiques de l'interface (ex. titres, menu, libellés) 
 * en français ou en anglais à partir d'une clé et du tableau global $translations.
 *
 * @param string $key La clé du texte à traduire (ex. "title_meteo", "menu_home").
 * @param string $lang La langue cible ("fr" pour français, "en" pour anglais).
 * @return string Le texte traduit ou la clé si la traduction est absente.
 * @details Utilise le tableau global $translations pour fournir des textes statiques traduits pour l'interface. Si la traduction n'existe pas, retourne la clé pour éviter les erreurs.
 */
 
function t($key, $lang) {
    global $translations;
    return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key; //si la traduction existe on la renvoie sinon on renvoie key pour eviter les erreurs
}
?>