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
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : 'style=default';

// Vérifier si les paramètres sont valides
if (!$selected_region || !$selected_departement || !$selected_ville || !$lat || !$lon) {
    header("Location: index.php?" . $styleParam);
    exit;
}

// Remplace par ta clé API OpenWeatherMap
$weatherApiKey = "f8e571c228bd19a1c00cedc4a50ea893";
$weatherData = getWeatherData($lat, $lon, $weatherApiKey);
$weatherForecast = getWeatherForecast($lat, $lon, $weatherApiKey);
?>

<main>
    <h1>Prévisions Météo</h1>
    <section>
        <h2>Météo pour <?php echo htmlspecialchars($selected_ville); ?></h2>
        <p><strong>Région :</strong> <?php echo htmlspecialchars($selected_region); ?></p>
        <p><strong>Département :</strong> <?php echo htmlspecialchars($selected_departement); ?></p>
        <p><a href="regions.php?region=<?php echo urlencode($selected_region); ?>&<?php echo $styleParam; ?>">Retour à la sélection du département</a></p>
        <p><a href="index.php?<?php echo $styleParam; ?>">Retour à la carte</a></p>

        <h3>Conditions actuelles :</h3>
        <ul>
            <li><strong>Température :</strong> <?php echo $weatherData['temperature']; ?> °C</li>
            <li><strong>Description :</strong> <?php echo $weatherData['description']; ?></li>
            <li><strong>Humidité :</strong> <?php echo $weatherData['humidity']; ?> %</li>
            <li><strong>Vitesse du vent :</strong> <?php echo $weatherData['wind_speed']; ?> m/s</li>
        </ul>
        <h3>Prévisions météo pour les prochains jours :</h3>
            <ul>
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

    </section>
    <!-- Bouton retour en haut -->
    <a href="#" class="back-to-top">↑</a>
</main>

<?php
require "./include/footer.inc.php";
?>