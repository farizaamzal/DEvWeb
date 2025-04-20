<?php

/**
 * @file meteo.php
 * @brief Page affichant les prévisions météo pour une ville sélectionnée
 * @author Fariza Amzal , Nadjib Moussaoui 
 * @version 1.0
 * @date Avril 2025
 * @details Utilise l'API OpenWeatherMap pour afficher les conditions actuelles et les prévisions sur plusieurs jours. Gère les thèmes clair/sombre, la langue via cookies, et sauvegarde les consultations dans un CSV et des cookies.
 * @see functions.inc.php pour les fonctions sauvegarderConsultation(), getWeatherData(), getWeatherForecast()
 */

/**
 * @brief Charge les traductions
 * @details Inclut lang.inc.php pour définir la fonction t() et la variable $lang.
 */
// Charge les traductions
require_once "./include/lang.inc.php";


/**
 * @brief Définit la langue
 * @var string $lang Langue courante ('fr' ou 'en')
 * @details Utilise le paramètre GET ou le cookie pour définir la langue, avec français par défaut. Stocke la langue dans un cookie.
 */
// Définit la langue
$lang = isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en']) ? $_GET['lang'] : (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr');
setcookie('lang', $lang, time() + 90*24*3600, '/', '', false, true);


/**
 * @brief Définit le titre et la description de la page
 * @var string $title Titre traduit pour la page météo
 * @var string $description Description traduite de la page
 */
// Définit le titre et la description
$title = t('meteo_heading', $lang); // "Prévisions Météo - Météo&Climat" (fr) ou "Weather Forecast - Weather&Climate" (en)
$description = t('description_meteo', $lang); // "Prévisions météo pour la ville sélectionnée" (fr) ou "Weather forecast for the selected city" (en)


/**
 * @brief Inclut l'en-tête
 * @details Charge header.inc.php pour le menu traduit, les styles CSS, et les cookies de langue et thème.
 */
// Inclut l'en-tête
require "./include/header.inc.php";

/**
 * @brief Charge les fonctions utilitaires
 * @details Inclut functions.inc.php pour les fonctions de gestion des consultations et des données météo.
 */
// Charge les fonctions utilitaires
require "./include/functions.inc.php";


/**
 * @brief Récupère les paramètres de l'URL
 * @var string|null $selected_region Région sélectionnée
 * @var string|null $selected_departement Département sélectionné
 * @var string|null $selected_ville Ville sélectionnée
 * @var string|null $lat Latitude de la ville
 * @var string|null $lon Longitude de la ville
 * @var string $styleParam Paramètre de style pour l'URL, basé sur GET ou cookie
 * @details Récupère les paramètres nécessaires pour afficher les prévisions météo.
 */
// Récupérer les paramètres
$selected_region = $_GET['region'] ?? null;
$selected_departement = $_GET['departement'] ?? null;
$selected_ville = $_GET['ville'] ?? null;
$lat = $_GET['lat'] ?? null;
$lon = $_GET['lon'] ?? null;
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');


/**
 * @brief Vérifie la validité des paramètres
 * @details Redirige vers index.php si les paramètres requis sont manquants.
 */
// Vérifier si les paramètres sont valides
if (!$selected_region || !$selected_departement || !$selected_ville || !$lat || !$lon) {
    header("Location: index.php?" . $styleParam);
    exit;
}


/**
 * @brief Sauvegarde la consultation
 * @details Appelle sauvegarderConsultation() pour enregistrer la ville consultée dans consultations.csv.
 */
// On sauvegarde la ville consultée
sauvegarderConsultation($selected_ville, $selected_region, $selected_departement);


/**
 * @brief Stocke les informations de consultation dans un cookie
 * @var array $cookie_data Données de la dernière ville consultée (nom et date)
 * @details Crée un cookie 'derniere_ville' avec la ville et la date de consultation.
 */
// Ajout du cookie
$cookie_data = [
    'ville' => $selected_ville,
    'date_consultation' => date('d/m/Y H:i')
];
setcookie('derniere_ville', json_encode($cookie_data), time() + 90*24*3600, '/'); // Expire dans 90 jours


/**
 * @brief Récupère les données météo via OpenWeatherMap
 * @var string $weatherApiKey Clé API OpenWeatherMap
 * @var array $weatherData Données météo actuelles (température, description, humidité, vent, icône)
 * @var array $weatherForecast Prévisions météo pour les jours suivants
 * @details Appelle getWeatherData() et getWeatherForecast() pour récupérer les données météo.
 */
// API OpenWeatherMap
$weatherApiKey = "f8e571c228bd19a1c00cedc4a50ea893";
$weatherData = getWeatherData($lat, $lon, $weatherApiKey);
$weatherForecast = getWeatherForecast($lat, $lon, $weatherApiKey);


/**
 * @brief Stocke les données météo dans un cookie
 * @var array $meteo_data Données météo à sauvegarder (température, description, humidité, vent, icône)
 * @details Crée un cookie 'derniere_meteo' si les données météo sont valides.
 */
// On crée les données météo à stocker dans le cookie
if ($weatherData && $weatherData['temperature'] !== "Erreur") {
    $meteo_data = [
        'temperature' => $weatherData['temperature'],
        'description' => $weatherData['description'],
        'humidity' => $weatherData['humidity'],
        'wind_speed' => $weatherData['wind_speed'],
        'icon' => $weatherData['icon'],
    ];
    // Sauvegarder ces données dans un cookie
    setcookie('derniere_meteo', json_encode($meteo_data), time() + 90*24*3600, '/'); // Expire dans 90 jours
}
?>

<main>
<?php
if (isset($_COOKIE['derniere_ville'])) {
    $last = json_decode($_COOKIE['derniere_ville'], true);
    echo "<div class='cookie-notif'>" . t('last_consultation', $lang) . " <strong>" . htmlspecialchars($last['ville']) . "</strong> (" . htmlspecialchars($last['date_consultation']) . ")</div>";
}
?>
    <h1><?= t('meteo_heading', $lang) ?></h1>
    <section>
        <h2><?= t('weather_for', $lang) ?> <?= htmlspecialchars($selected_ville) ?></h2>
        <p><strong><?= t('region', $lang) ?> :</strong> <?= htmlspecialchars($selected_region) ?></p>
        <p><strong><?= t('department', $lang) ?> :</strong> <?= htmlspecialchars($selected_departement) ?></p>

        <h3><?= t('current_conditions', $lang) ?> :</h3>
        <ul class="list">
            <li><strong><?= t('temperature', $lang) ?> :</strong> <?= htmlspecialchars($weatherData['temperature']) ?> °C</li>
            <li><strong><?= t('description', $lang) ?> :</strong> <?= htmlspecialchars($weatherData['description']) ?></li>
            <li><strong><?= t('humidity', $lang) ?> :</strong> <?= htmlspecialchars($weatherData['humidity']) ?> %</li>
            <li><strong><?= t('wind_speed', $lang) ?> :</strong> <?= htmlspecialchars($weatherData['wind_speed']) ?> m/s</li>
            <!-- Affichage de l'icône météo pour les conditions actuelles -->
            <?php if (isset($weatherData['icon'])): ?>
                <li><strong><?= t('current_icon', $lang) ?> :</strong> 
                    <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($weatherData['icon']) ?>@2x.png" alt="<?= t('weather_icon', $lang) ?>"/>
                </li>
            <?php endif; ?>
        </ul>
        <h3><?= t('forecast_next_days', $lang) ?> :</h3>
        <ul class="list">
            <!-- Boucle à travers le tableau des prévisions météo -->
            <!-- Chaque élément contient une date ($date) et un tableau d'informations météo ($info) -->
            <?php foreach ($weatherForecast as $date => $info): ?>
                <li>
                    <strong><?= htmlspecialchars($date) ?> :</strong>
                    <?= htmlspecialchars($info['temperature']) ?> °C, 
                    <?= htmlspecialchars($info['description']) ?>, 
                    <?= t('humidity', $lang) ?> : <?= htmlspecialchars($info['humidity']) ?>%, 
                    <?= t('wind', $lang) ?> : <?= htmlspecialchars($info['wind_speed']) ?> m/s
                    <!-- Affichage de l'icône météo pour les conditions actuelles -->
                    <?php if (isset($info['icon'])): ?>
                        <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($info['icon']) ?>@2x.png" alt="<?= t('weather_icon', $lang) ?>"/>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Un lien dynamique, garde les infos d'avant pas à regions.php directement -->
        <p class="lien"><a href="regions.php?region=<?= urlencode($selected_region) ?>&amp;<?= $styleParam ?>&amp;lang=<?= $lang ?>"><?= t('back_to_department', $lang) ?></a></p>
        <p class="lien"><a href="previsions.php?<?= $styleParam ?>&amp;lang=<?= $lang ?>"><?= t('back_to_map', $lang) ?></a></p> <!-- Avec le style déjà utilisé -->
    </section>
    <!-- Bouton retour en haut -->
    <a href="#" class="back-to_top">↑</a>
</main>

<?php
/**
 * @brief Inclut le pied de page
 * @details Charge footer.inc.php pour les liens et informations finales de la page.
 */
// Inclut le pied de page
require "./include/footer.inc.php";
?>