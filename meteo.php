<?php
$title = "Prévisions Météo";
$description = "Prévisions météo pour la ville sélectionnée";

require "./include/header.inc.php";
require "./include/functions.inc.php";

// Récupérer les paramètres
$selected_region = $_GET['region'] ?? null;
$selected_departement = $_GET['departement'] ?? null;
$selected_ville = $_GET['ville'] ?? null;
$lat = $_GET['lat'] ?? null;
$lon = $_GET['lon'] ?? null;
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');

// Vérifier si les paramètres sont valides
if (!$selected_region || !$selected_departement || !$selected_ville || !$lat || !$lon) {
    header("Location: index.php?" . $styleParam);
    exit;
}

//on sauvegarde la ville consultée
sauvegarderConsultation($selected_ville, $selected_region, $selected_departement);


//ajout du cookie
$cookie_data = [
    'ville' => $selected_ville,
    'date_consultation' => date('d/m/Y H:i')
];
setcookie('derniere_ville', json_encode($cookie_data), time() + 90*24*3600, '/'); // Expire dans 90 jours


// API OpenWeatherMap
$weatherApiKey = "f8e571c228bd19a1c00cedc4a50ea893";
$weatherData = getWeatherData($lat, $lon, $weatherApiKey);
$weatherForecast = getWeatherForecast($lat, $lon, $weatherApiKey);
?>

<main>
<?php
if (isset($_COOKIE['derniere_ville'])) {
    $last = json_decode($_COOKIE['derniere_ville'], true);
    echo "<div class='cookie-notif'>Dernière consultation : <strong>{$last['ville']}</strong> ({$last['date_consultation']})</div>";
}
?>
    <h1>Prévisions Météo</h1>
    <section>
        <h2>Météo pour <?php echo $selected_ville; ?></h2>
        <p><strong>Région :</strong> <?php echo $selected_region; ?></p>
        <p><strong>Département :</strong> <?php echo $selected_departement; ?></p>

        <h3>Conditions actuelles :</h3>
        <ul class="list">
            <li><strong>Température :</strong> <?php echo $weatherData['temperature']; ?> °C</li>
            <li><strong>Description :</strong> <?php echo $weatherData['description']; ?></li>
            <li><strong>Humidité :</strong> <?php echo $weatherData['humidity']; ?> %</li>
            <li><strong>Vitesse du vent :</strong> <?php echo $weatherData['wind_speed']; ?> m/s</li>
        </ul>
        <h3>Prévisions météo pour les prochains jours :</h3>
            <ul class="list">
                <!--Boucle à travers le tableau des prévisions météo -->
                <!--Chaque élément contient une date ($date) et un tableau d'informations météo ($info)-->
                <?php foreach ($weatherForecast as $date => $info): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($date); ?> :</strong>
                        <?php echo $info['temperature']; ?> °C, 
                        <?php echo htmlspecialchars($info['description']); ?>, 
                        Humidité : <?php echo $info['humidity']; ?>%, 
                        Vent : <?php echo $info['wind_speed']; ?> m/s
                    </li>
                    <?php endforeach; ?>
            </ul>
            <!--un lien dynamique,garde les infos d'avant pas a regions.php directement-->
            <p><a href="regions.php?region=<?php echo urlencode($selected_region); ?>&amp;<?php echo $styleParam; ?>">Retour à la sélection du département</a></p>
            <p><a href="previsions.php?<?php echo $styleParam; ?>">Retour à la carte</a></p> <!--avec le style déja utilisé-->
    </section>
    <!-- Bouton retour en haut -->
    <a href="#" class="back-to-top">↑</a>
</main>

<?php
require "./include/footer.inc.php";
?>