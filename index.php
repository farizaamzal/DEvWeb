<?php
    $title="Accueil - Météo&amp;Climat";
    require "./include/header.inc.php";
    // Vérifier si le paramètre 'style' existe dans l'URL, sinon définir 'style' à 'default'
    $styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');
include_once"./include/functions.inc.php";
incrementerCompteur();
// Récupère l'adresse IP de l'utilisateur
$ip = getUserIP();

// Tente d'obtenir la localisation approximative à partir de l'IP
$localisation = getLocationFromIP($ip);

// Clé API OpenWeather (mets ta vraie clé ici)
$weatherApiKey = "f8e571c228bd19a1c00cedc4a50ea893";

$weatherData = null;

if ($localisation && $localisation['lat'] && $localisation['lon']) {
    // On appelle OpenWeather pour les données actuelles
    $weatherData = getWeatherData($localisation['lat'], $localisation['lon'], $weatherApiKey);
}

?>

<main>

    <section>
        <h1>Bienvenue sur Météo&amp;Climat !</h1>

         <!-- From Uiverse.io by zanina-yassine --> 
<div class="container">
  <div class="cloud front">
    <span class="left-front"></span>
    <span class="right-front"></span>
  </div>
  <span class="sun sunshine"></span>
  <span class="sun"></span>
  <div class="cloud back">
    <span class="left-back"></span>
    <span class="right-back"></span>
  </div>
</div>

        <p>Découvrez en un coup d'œil la météo qu’il fait chez vous et partout en France !</p>
        <p>☀️ Soleil, 🌧️ pluie, ⛈️ orages… Soyez toujours prêt grâce à nos mises à jour fiables et détaillées.</p>
        <?php if ($weatherData): ?>
          <?php if (isset($weatherData) && isset($localisation['ville'])): ?>
    <section class="meteo-box">
        <h2>Météo à <?php echo htmlspecialchars($localisation['ville']); ?> (détection automatique)</h2>
        <ul class="meteo-horizontal">
            <li><strong>Température :</strong> <?php echo $weatherData['temperature']; ?> °C</li>
            <li><strong>Description :</strong> <?php echo $weatherData['description']; ?></li>
            <li><strong>Humidité :</strong> <?php echo $weatherData['humidity']; ?> %</li>
            <li><strong>Vent :</strong> <?php echo $weatherData['wind_speed']; ?> m/s</li>
            <?php if (isset($weatherData['icon'])): ?>
                <li>
                    <img src="https://openweathermap.org/img/wn/<?php echo $weatherData['icon']; ?>@2x.png" alt="Météo actuelle">
                </li>
            <?php endif; ?>
        </ul>
    </section>
    <?php endif; ?>
    <?php endif; ?>

        <p><strong> restez informé, restez préparé !</strong></p>
</section>

<section class="links-section">
    <h2>Consultez dès maintenant 📝</h2>
    <a href="./previsions.php">🌤️ La Météo de votre région</a>
    <a href="./tech.php">🔭 L'image du jour de la NASA</a>
    <a href="./statistiques.php">📊 L'historique des villes consultées</a>
    <p style="font-size: 1.2em; color: #333;">
      🎲 Chaque visite vous réserve une surprise ! Découvrez aléatoirement l'une des images du projet à chaque rafraîchissement de la page. Qui sait ce que vous verrez ensuite ?
    </p>

  <?php
    // Appel de la fonction pour afficher une image aléatoire
    afficherImageAleatoire();
  ?>
</section>

    <!-- Bouton retour en haut -->
  <a href="#" class="back-to-top">↑</a>
</main>

<?php
    require "./include/footer.inc.php";
?>