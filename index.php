<?php

/**
 * @file index.php
 * @brief Page d'accueil du site Météo&Climat
 * @author Fariza Amzal , Nadjib Moussaoui 
 * @version 1.0
 * @date Avril 2025
 * @details Affiche la météo locale basée sur l'IP, permet la recherche de météo par ville, affiche la dernière météo consultée, et propose des liens vers d'autres pages. Gère les thèmes clair/sombre et la langue via cookies. Utilise l'API OpenWeatherMap pour les données météo.
 * @see functions.inc.php pour les fonctions incrementerCompteur(), getUserIP(), getLocationFromIP(), getWeatherData(), organizeData(), sauvegarderConsultation(), afficherImageAleatoire()
 */

/**
 * @brief Charge les traductions et gère la langue
 * @details Inclut lang.inc.php pour définir la fonction t() et la variable $lang. Définit le cookie de langue pour français ou anglais.
 * @var string $lang Langue courante ('fr' ou 'en')
 */
// Charge les traductions pour définir t() et $lang
require_once "./include/lang.inc.php";

// Gère la langue (français par défaut)
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + 90*24*3600, '/', '', false, true);
} else {
    $lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr';
}

/**
 * @brief Définit le titre de la page traduit
 * @var string $title Titre traduit pour la page d'accueil
 */
// Définit le titre avant header.inc.php
$title = t('title_index', $lang); // "Accueil - Météo&Climat" (fr) ou "Home - Weather&Climate" (en)


/**
 * @brief Inclut le fichier d'en-tête
 * @details Charge header.inc.php pour le menu traduit, les styles CSS, et les cookies de langue et thème.
 */
// Inclut le fichier d'en-tête (menu, CSS)
require "./include/header.inc.php";


/**
 * @brief Inclut les fonctions utilitaires
 * @details Charge functions.inc.php pour les fonctions de gestion des visites, localisation, météo, et affichage d'images.
 */
// Inclut les fonctions utilitaires
include_once "./include/functions.inc.php";

/**
 * @brief Incrémente le compteur de visites
 * @details Appelle incrementerCompteur() pour enregistrer la visite dans un fichier ou une base de données.
 */
// Incrémente le compteur de visites
incrementerCompteur();


/**
 * @brief Gère le thème
 * @var string $styleParam Paramètre de style pour l'URL, basé sur GET ou cookie
 * @details Définit le thème clair ou sombre.
 */
// Gère le thème
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');


/**
 * @brief Récupère l'adresse IP de l'utilisateur
 * @var string $ip Adresse IP de l'utilisateur
 * @details Appelle getUserIP() pour obtenir l'IP.
 */
// Récupère l'adresse IP
$ip = getUserIP();


/**
 * @brief Récupère la localisation basée sur l'IP
 * @var array|null $localisation Données de localisation (ville, latitude, longitude)
 * @details Appelle getLocationFromIP() pour obtenir la localisation.
 */
// Localisation
$localisation = getLocationFromIP($ip);


/**
 * @brief Récupère les données météo locales
 * @var string $weatherApiKey Clé API OpenWeatherMap
 * @var array|null $weatherData Données météo locales (température, description, humidité, vent, icône)
 * @details Appelle getWeatherData() si la localisation est valide.
 */
// Clé API OpenWeatherMap
$weatherApiKey = "f8e571c228bd19a1c00cedc4a50ea893";

// Météo locale
$weatherData = null;
if ($localisation && $localisation['lat'] && $localisation['lon']) {
    $weatherData = getWeatherData($localisation['lat'], $localisation['lon'], $weatherApiKey);
}


/**
 * @brief Gère l'affichage de la dernière météo consultée
 * @var bool $showLastMeteo Indique si l'utilisateur a demandé à revoir la dernière météo
 * @var array|null $meteoData Données de la dernière météo depuis le cookie
 * @var array|null $lastVille Données de la dernière ville depuis le cookie
 * @details Vérifie l'action 'revoir' et la présence des cookies 'derniere_meteo' et 'derniere_ville'.
 */
// Revoir la dernière météo
$showLastMeteo = isset($_GET['action']) && $_GET['action'] === 'revoir' && isset($_COOKIE['derniere_meteo']) && isset($_COOKIE['derniere_ville']);
$meteoData = null;
$lastVille = null;
if ($showLastMeteo) {
    $meteoData = json_decode($_COOKIE['derniere_meteo'], true);
    $lastVille = json_decode($_COOKIE['derniere_ville'], true);
}


/**
 * @brief Gère la recherche de ville
 * @var string $searchVille Nom de la ville recherchée
 * @var array|null $searchWeatherData Données météo pour la ville recherchée
 * @var string|null $searchError Message d'erreur en cas d'échec de la recherche
 * @var array|null $foundVille Données de la ville trouvée
 * @details Recherche une ville dans les données CSV, récupère sa météo, et sauvegarde la consultation.
 */
// Recherche ville
$searchVille = isset($_GET['ville_search']) ? trim($_GET['ville_search']) : '';
$searchWeatherData = null;
$searchError = null;
if ($searchVille) {
    $tab = organizeData('v_region_2024.csv', 'v_departement_2024.csv', 'cities.csv');
    $villes = $tab['villes'];
    $foundVille = null;
    foreach ($villes as $label => $villeData) {
        if (strcasecmp($label, $searchVille) === 0) {
            $foundVille = $villeData;
            $foundVille['label'] = $label;
            break;
        }
    }
    if ($foundVille && isset($foundVille['latitude'], $foundVille['longitude'])) {
        $searchWeatherData = getWeatherData($foundVille['latitude'], $foundVille['longitude'], $weatherApiKey);
        if ($searchWeatherData['temperature'] === "Erreur") {
            $searchError = t('search_error', $lang) . htmlspecialchars($searchVille);
            $searchWeatherData = null;
        } else {
            sauvegarderConsultation($foundVille['label'], $foundVille['region_name'], $foundVille['departement_number']);
            $cookie_data = [
                'ville' => $foundVille['label'],
                'date_consultation' => date('d/m/Y H:i')
            ];
            setcookie('derniere_ville', json_encode($cookie_data), time() + 90*24*3600, '/', '', false, true);
            if ($searchWeatherData) {
                $meteo_data = [
                    'temperature' => $searchWeatherData['temperature'],
                    'description' => $searchWeatherData['description'],
                    'humidity' => $searchWeatherData['humidity'],
                    'wind_speed' => $searchWeatherData['wind_speed'],
                    'icon' => $searchWeatherData['icon'],
                ];
                setcookie('derniere_meteo', json_encode($meteo_data), time() + 90*24*3600, '/', '', false, true);
            }
        }
    } else {
        $searchError = t('city_not_found', $lang) . htmlspecialchars($searchVille);
    }
}


/**
 * @brief Affiche une notification pour la dernière ville consultée
 * @var array $last Données de la dernière ville depuis le cookie
 * @details Affiche une notification stylée avec un lien pour revoir la météo si le cookie 'derniere_ville' existe.
 */
// Notification dernière ville
if (isset($_COOKIE['derniere_ville'])) {
    $last = json_decode($_COOKIE['derniere_ville'], true);
    if ($last && isset($last['ville'], $last['date_consultation'])) {
        echo "
<div style='
    background: rgba(81, 100, 139, 0.85);
    backdrop-filter: blur(6px);
    border-left: 4px solid #FFA726;
    padding: 1.25em;
    margin: 1.5em auto;
    font-size: 1.1em;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    color: #FAFAFA;
    max-width: 800px;
    transition: all 0.3s ease;
'>
    <div style='flex: 1; min-width: 250px;'>
        <span style='color: #FFA726; font-size: 1.2em;'>🌍</span> 
        <strong style='color: #FFA726;'>" . t('last_city', $lang) . "</strong> 
        <span style='color: #64B5F6; font-weight: 600;'>" . htmlspecialchars($last['ville']) . "</span><br/>
        <span style='color: #B0BEC5; font-size: 0.9em;'>🕒 " . t('last_city_date', $lang) . " {$last['date_consultation']}</span>
    </div>
    <div style='margin-top: 0.5em;'>
        <a href='index.php?action=revoir&amp;{$styleParam}&amp;lang=$lang#last-meteo' style='
            background: rgba(255, 167, 38, 0.15);
            color: #FFA726;
            padding: 0.6em 1.2em;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid #FFA726;
            transition: all 0.3s ease;
            display: inline-block;
        '>
            <span style='margin-right: 0.5em;'>🔁</span> " . t('revoir', $lang) . "
        </a>
    </div>
</div>
";
    }
}
?>

<main>
    <section>
        <h1><?= t('welcome', $lang) ?></h1>
        <p><?= t('intro_1', $lang) ?></p>
        <p><?= t('intro_2', $lang) ?></p>

        <form action="index.php?lang=<?= $lang ?>#search-meteo" method="get" style="justify-content: center; align-items: center;">
            <label for="ville_search"><?= t('search_label', $lang) ?></label>
            <input type="text" id="ville_search" name="ville_search" value="<?= htmlspecialchars($searchVille) ?>" placeholder="<?= t('search_placeholder', $lang) ?>" required=""/>
            <input type="hidden" name="style" value="<?= isset($_GET['style']) ? htmlspecialchars($_GET['style']) : (isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'normal') ?>"/>
            <input type="hidden" name="lang" value="<?= $lang ?>"/>
            <button type="submit">🔍 <?= t('search_button', $lang) ?></button>
        </form>

        <?php if ($searchWeatherData): ?>
            <section class="meteo-box" id="search-meteo">
                <h2><?= t('meteo_title', $lang) ?> <?= htmlspecialchars($searchVille) ?> <span>📍</span></h2>
                <div class="meteo-grid">
                    <?php if (isset($searchWeatherData['icon'])): ?>
                        <div class="weather-icon">
                            <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($searchWeatherData['icon']) ?>@2x.png" alt="<?= t('weather_icon', $lang) ?>"/>
                        </div>
                    <?php endif; ?>
                    <div class="weather-item">
                        <strong><?= t('temperature', $lang) ?></strong>
                        <span><?= htmlspecialchars($searchWeatherData['temperature']) ?> °C</span>
                    </div>
                    <div class="weather-item">
                        <strong><?= t('description', $lang) ?></strong>
                        <span><?= htmlspecialchars($searchWeatherData['description']) ?></span>
                    </div>
                    <div class="weather-item">
                        <strong><?= t('humidity', $lang) ?></strong>
                        <span><?= htmlspecialchars($searchWeatherData['humidity']) ?> %</span>
                    </div>
                    <div class="weather-item">
                        <strong><?= t('wind_speed', $lang) ?></strong>
                        <span><?= htmlspecialchars($searchWeatherData['wind_speed']) ?> m/s</span>
                    </div>
                </div>
            </section>
        <?php elseif ($searchError): ?>
            <section class="meteo-box" id="search-meteo">
                <p style="color: red;"><?= htmlspecialchars($searchError) ?></p>
            </section>
        <?php endif; ?>

        <?php if ($showLastMeteo && $meteoData && $lastVille): ?>
            <section class="meteo-box" id="last-meteo">
                <h2><?= t('meteo_last_title', $lang) ?> <?= htmlspecialchars($lastVille['ville']) ?> <span>📍</span></h2>
                <div class="meteo-grid">
                    <?php if (isset($meteoData['icon'])): ?>
                        <div class="weather-icon">
                            <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($meteoData['icon']) ?>@2x.png" alt="<?= t('weather_icon', $lang) ?>"/>
                        </div>
                    <?php endif; ?>
                    <div class="weather-item">
                        <strong><?= t('temperature', $lang) ?></strong>
                        <span><?= htmlspecialchars($meteoData['temperature']) ?> °C</span>
                    </div>
                    <div class="weather-item">
                        <strong><?= t('description', $lang) ?></strong>
                        <span><?= htmlspecialchars($meteoData['description']) ?></span>
                    </div>
                    <div class="weather-item">
                        <strong><?= t('humidity', $lang) ?></strong>
                        <span><?= htmlspecialchars($meteoData['humidity']) ?> %</span>
                    </div>
                    <div class="weather-item">
                        <strong><?= t('wind_speed', $lang) ?></strong>
                        <span><?= htmlspecialchars($meteoData['wind_speed']) ?> m/s</span>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($weatherData && isset($localisation['ville'])): ?>
            <section class="meteo-box">
                <h2><?= t('meteo_local_title', $lang) ?> <?= htmlspecialchars($localisation['ville']) ?> <span>📍</span></h2>
                <div class="meteo-grid">
                    <?php if (isset($weatherData['icon'])): ?>
                        <div class="weather-icon">
                            <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($weatherData['icon']) ?>@2x.png" alt="<?= t('weather_icon', $lang) ?>"/>
                        </div>
                    <?php endif; ?>
                    <div class="weather-item">
                        <strong><?= t('temperature', $lang) ?></strong>
                        <span><?= htmlspecialchars($weatherData['temperature']) ?> °C</span>
                    </div>
                    <div class="weather-item">
                        <strong><?= t('description', $lang) ?></strong>
                        <span><?= htmlspecialchars($weatherData['description']) ?></span>
                    </div>
                    <div class="weather-item">
                        <strong><?= t('humidity', $lang) ?></strong>
                        <span><?= htmlspecialchars($weatherData['humidity']) ?> %</span>
                    </div>
                    <div class="weather-item">
                        <strong><?= t('wind_speed', $lang) ?></strong>
                        <span><?= htmlspecialchars($weatherData['wind_speed']) ?> m/s</span>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <p><strong><?= t('conclusion', $lang) ?></strong></p>
    </section>

    <section class="links-section">
        <h2><?= t('links_title', $lang) ?></h2>
        <a href="./previsions.php?lang=<?= $lang ?>&amp;<?= $styleParam ?>"><?= t('link_prevision', $lang) ?></a>
        <a href="./tech.php?lang=<?= $lang ?>&amp;<?= $styleParam ?>"><?= t('link_tech', $lang) ?></a>
        <a href="./statistiques.php?lang=<?= $lang ?>&amp;<?= $styleParam ?>"><?= t('link_stats', $lang) ?></a>
        <p style="font-size: 1.2em">
            <?= t('random_image_text', $lang) ?>
        </p>
        <?php afficherImageAleatoire(); ?>
    </section>

    <a href="#" class="back-to-top">↑</a>
</main>

<?php 
/**
 * @brief Inclut le pied de page
 * @details Charge footer.inc.php pour les liens et informations finales de la page.
 */
require "./include/footer.inc.php";
 ?>