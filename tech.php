<?php

/**
 * @file tech.php
 * @brief Page développeur affichant l'image/vidéo NASA APOD et la géolocalisation par IP
 * @author Fariza Amzal , Nadjib Moussaoui
 * @version 1.0
 * @date Avril 2025
 * @details Cette page utilise les APIs NASA APOD, GeoPlugin, ipinfo.io, et WhatIsMyIP pour afficher des données dynamiques. Elle gère les thèmes clair/sombre et la langue via cookies.
 * @see functions.inc.php pour les fonctions getNASAData(), getGeoPluginData(), getIPInfoData(), getWhatIsMyIPData()
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
 * @var string $title Titre traduit pour la page développeur
 */
// Définit le titre de la page, traduit en fonction de la langue
$title = t('title_tech', $lang);

/**
 * @brief Inclut l'en-tête avec menu et styles
 * @details Charge header.inc.php pour le menu traduit, les styles CSS, et les cookies de langue et thème.
 */
// Inclut le fichier d'en-tête (menu traduit, styles, cookies lang/theme)
require './include/header.inc.php';


/**
 * @brief Gère le paramètre de style (thème)
 * @var string $styleParam Paramètre de style pour l'URL, basé sur GET ou cookie
 * @details Définit le thème clair ou sombre en fonction des paramètres ou du cookie.
 */
// Gère le paramètre de style (thème)
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');


/**
 * @brief Charge les fonctions utilitaires
 * @details Inclut functions.inc.php pour les fonctions de récupération des données des APIs.
 */
// Inclure le fichier contenant les fonctions
require './include/functions.inc.php'; 


/**
 * @brief Récupère les données NASA APOD
 * @var string $apiKey Clé API NASA
 * @var array $nasaData Données JSON de l'API APOD (titre, URL, type de média, description)
 * @var string $mediaTitle Titre de l'image ou vidéo
 * @var string $mediaUrl URL du média
 * @var string $mediaType Type de média ('image', 'video', ou autre)
 * @var string $description Description du média
 * @details Appelle getNASAData() pour récupérer les données dynamiques de l'API NASA APOD.
 */
// Récupérer la clé API de la NASA
$apiKey = "WejnCDC0fIVzW3tEUw6p9E20Ct2FN6r5IZFuirda"; 

// Récupérer les données de la NASA
$nasaData = getNASAData($apiKey); 
$mediaTitle = $nasaData['title']; // Titre dynamique, non traduit
$mediaUrl = $nasaData['url'];
$mediaType = $nasaData['media_type'];
$description = $nasaData['explanation']; // Description dynamique, non traduite


/**
 * @brief Récupère l'adresse IP publique du visiteur
 * @var string $userIP Adresse IP du client
 * @details Utilise $_SERVER['REMOTE_ADDR'] pour identifier l'IP utilisée par les APIs de géolocalisation.
 */
// Récupérer l'IP publique du visiteur
$userIP = $_SERVER['REMOTE_ADDR']; 


/**
 * @brief Récupère les données de géolocalisation via GeoPlugin
 * @var array $geoPluginData Données GeoPlugin (ville, pays)
 * @var string $cityGeoPlugin Ville détectée par GeoPlugin
 * @var string $countryGeoPlugin Pays détecté par GeoPlugin
 * @details Appelle getGeoPluginData() pour estimer la localisation à partir de l'IP.
 */
// Récupérer les données de géolocalisation à partir de GeoPlugin
$geoPluginData = getGeoPluginData($userIP); 
$cityGeoPlugin = $geoPluginData['city'];
$countryGeoPlugin = $geoPluginData['country'];


/**
 * @brief Récupère les données de géolocalisation via ipinfo.io
 * @var array $ipInfoData Données ipinfo.io (ville, pays)
 * @var string $cityIpInfo Ville détectée par ipinfo.io
 * @var string $countryIpInfo Pays détecté par ipinfo.io
 * @details Appelle getIPInfoData() pour estimer la localisation à partir de l'IP.
 */
// Récupérer les données de géolocalisation à partir de ipinfo.io
$ipInfoData = getIPInfoData($userIP);
$cityIpInfo = $ipInfoData['city'];
$countryIpInfo = $ipInfoData['country'];


/**
 * @brief Récupère les données de géolocalisation via WhatIsMyIP
 * @var string $apik Clé API WhatIsMyIP
 * @var array $whatIsMyIPData Données WhatIsMyIP (ville, pays)
 * @var string $cityWhatIsMyIP Ville détectée par WhatIsMyIP
 * @var string $countryWhatIsMyIP Pays détecté par WhatIsMyIP
 * @details Appelle getWhatIsMyIPData() pour estimer la localisation à partir de l'IP.
 */
// Récupérer les données de géolocalisation à partir de WhatIsMyIP
$apik = "1cdb0e5f0e9b4c6d02faa6939c72023b";
$whatIsMyIPData = getWhatIsMyIPData($apik, $userIP);
$cityWhatIsMyIP = $whatIsMyIPData['city'];
$countryWhatIsMyIP = $whatIsMyIPData['country'];
?>

<main>
<section>
    <!-- Titre traduit -->
    <h1><?= t('welcome_tech', $lang) ?></h1>

    <!-- 🔹 NASA APOD -->
    <h2>🌌 <?= t('nasa_apod_title', $lang) ?> - <?php echo date("Y-m-d"); ?>)</h2>
    <h3><?= htmlspecialchars($mediaTitle) ?></h3>
<?php if ($mediaUrl): ?>
    <?php if ($mediaType == "image"): ?>
        <img src="<?= htmlspecialchars($mediaUrl) ?>" alt="<?= t('media_alt_image', $lang) ?>" width="400"/>
    <?php elseif ($mediaType == "video"): ?>
        <video width="400" controls>
            <source src="<?= htmlspecialchars($mediaUrl) ?>" type="video/mp4"/>
            <?= t('video_error', $lang) ?>
        </video>
    <?php else: ?>
        <img src="./images/sat.jpg" alt="<?= t('media_alt_default', $lang) ?>" width="400"/>
        <p><?= t('media_unknown', $lang) ?> <?= htmlspecialchars($mediaType) ?><?= t('media_unavailable', $lang) ?></p>
    <?php endif; ?>
<?php else: ?>
    <p><?= t('media_error', $lang) ?></p>
<?php endif; ?>
<p><?= htmlspecialchars($description) ?></p>

    <!-- 🔹 GeoPlugin -->
    <h2>📍 <?= t('geoplugin_title', $lang) ?> <?= htmlspecialchars($userIP) ?>)</h2>
    <p><?= t('city_label', $lang) ?> <?= htmlspecialchars($cityGeoPlugin) ?></p>
    <p><?= t('country_label', $lang) ?> <?= htmlspecialchars($countryGeoPlugin) ?></p>

    <!-- 🔹 ipinfo.io -->
    <h2>📍 <?= t('ipinfo_title', $lang) ?> <?= htmlspecialchars($userIP) ?>)</h2>
    <p><?= t('city_label', $lang) ?> <?= htmlspecialchars($cityIpInfo) ?></p>
    <p><?= t('country_label', $lang) ?> <?= htmlspecialchars($countryIpInfo) ?></p>

    <!-- 🔹 whatismyip.com -->
    <h2>📍 <?= t('whatismyip_title', $lang) ?> <?= htmlspecialchars($userIP) ?>)</h2>
    <p><?= t('city_label', $lang) ?> <?= htmlspecialchars($cityWhatIsMyIP) ?></p>
    <p><?= t('country_label', $lang) ?> <?= htmlspecialchars($countryWhatIsMyIP) ?></p>
</section>

<!-- Bouton retour en haut -->
<a href="#" class="back-to-top">↑</a>
</main>

<?php
/**
 * @brief Inclut le pied de page
 * @details Charge footer.inc.php pour les liens et informations finales de la page.
 */
// Inclut le pied de page
require "./include/footer.inc.php";
?>