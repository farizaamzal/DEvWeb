<?php
// Définit le titre de la page affiché dans l'onglet du navigateur
$title = "Accueil - Météo&Climat";

// Inclut le fichier d'en-tête (header.inc.php) qui contient le HTML commun (menu, CSS, etc.)
require "./include/header.inc.php";

// Inclut les fonctions utilitaires (une seule fois) définies dans functions.inc.php
include_once "./include/functions.inc.php";

// Incrémente un compteur de visites (probablement stocké dans un fichier ou une base de données)
incrementerCompteur();

// Gère le thème de style (normal ou alternatif) pour l'affichage
// Vérifie si un paramètre 'style' est passé dans l'URL, sinon utilise le cookie 'theme', ou 'normal' par défaut
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');

// Récupère l'adresse IP de l'utilisateur pour estimer sa localisation
$ip = getUserIP();

// Essaie de déterminer la localisation (ville, latitude, longitude) à partir de l'IP
$localisation = getLocationFromIP($ip);

// Clé API pour OpenWeatherMap, utilisée pour récupérer les données météo
$weatherApiKey = "f8e571c228bd19a1c00cedc4a50ea893";

// Initialise les données météo locales à null
$weatherData = null;
// Si la localisation est valide (contient latitude et longitude), récupère la météo locale
if ($localisation && $localisation['lat'] && $localisation['lon']) {
    $weatherData = getWeatherData($localisation['lat'], $localisation['lon'], $weatherApiKey);
}

// Vérifie si l'utilisateur veut revoir la météo de la dernière ville consultée
// Cela se produit si l'URL contient 'action=revoir' et que les cookies nécessaires existent
$showLastMeteo = isset($_GET['action']) && $_GET['action'] === 'revoir' && isset($_COOKIE['derniere_meteo']) && isset($_COOKIE['derniere_ville']);
$meteoData = null; // Données météo de la dernière ville
$lastVille = null; // Informations sur la dernière ville
if ($showLastMeteo) {
    // Décode les cookies JSON pour récupérer les données météo et la ville
    $meteoData = json_decode($_COOKIE['derniere_meteo'], true);
    $lastVille = json_decode($_COOKIE['derniere_ville'], true);
}

// Gère la recherche d'une ville saisie par l'utilisateur
$searchVille = isset($_GET['ville_search']) ? trim($_GET['ville_search']) : ''; // Récupère la ville saisie, supprime les espaces inutiles
$searchWeatherData = null; // Données météo pour la ville recherchée
$searchError = null; // Message d'erreur en cas de problème
if ($searchVille) {
    // Charge les données des villes à partir des fichiers CSV
    $tab = organizeData('v_region_2024.csv', 'v_departement_2024.csv', 'cities.csv');
    $villes = $tab['villes'];
    
    // Cherche la ville saisie (comparaison insensible à la casse)
    $foundVille = null;
    foreach ($villes as $label => $villeData) {
        if (strcasecmp($label, $searchVille) === 0) {
            $foundVille = $villeData;
            $foundVille['label'] = $label; // Conserve le nom exact de la ville
            break;
        }
    }
    
    // Si la ville est trouvée et a des coordonnées valides
    if ($foundVille && isset($foundVille['latitude'], $foundVille['longitude'])) {
        // Récupère la météo pour cette ville via l'API OpenWeatherMap
        $searchWeatherData = getWeatherData($foundVille['latitude'], $foundVille['longitude'], $weatherApiKey);
        if ($searchWeatherData['temperature'] === "Erreur") {
            // En cas d'erreur de l'API, définit un message d'erreur
            $searchError = "Impossible de récupérer la météo pour " . htmlspecialchars($searchVille);
            $searchWeatherData = null;
        } else {
            // Enregistre la consultation dans l'historique
            sauvegarderConsultation($foundVille['label'], $foundVille['region_name'], $foundVille['departement_number']);
            // Met à jour le cookie 'derniere_ville'
            $cookie_data = [
                'ville' => $foundVille['label'],
                'date_consultation' => date('d/m/Y H:i') // Date et heure de la consultation
            ];
            setcookie('derniere_ville', json_encode($cookie_data), time() + 90*24*3600, '/');
            // Met à jour le cookie 'derniere_meteo' avec les données météo
            if ($searchWeatherData) {
                $meteo_data = [
                    'temperature' => $searchWeatherData['temperature'],
                    'description' => $searchWeatherData['description'],
                    'humidity' => $searchWeatherData['humidity'],
                    'wind_speed' => $searchWeatherData['wind_speed'],
                    'icon' => $searchWeatherData['icon'],
                ];
                setcookie('derniere_meteo', json_encode($meteo_data), time() + 90*24*3600, '/');
            }
        }
    } else {
        // Si la ville n'est pas trouvée, définit un message d'erreur
        $searchError = "Ville non trouvée : " . htmlspecialchars($searchVille);
    }
}

// Affiche une notification pour la dernière ville consultée (si disponible)
if (isset($_COOKIE['derniere_ville'])) {
    $last = json_decode($_COOKIE['derniere_ville'], true);
    // Vérifie que les données nécessaires sont présentes
    if ($last && isset($last['ville'], $last['date_consultation'])) {
        // Génère une boîte stylée avec le nom de la ville et un bouton pour revoir la météo
        echo "
<div style='
    background: rgba(81, 100, 139, 0.85); /* Fond semi-transparent */
    backdrop-filter: blur(6px); /* Effet de flou */
    border-left: 4px solid #FFA726; /* Bordure orange */
    padding: 1.25em;
    margin: 1.5em auto; /* Centré avec marges */
    font-size: 1.1em;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2); /* Ombre */
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    color: #FAFAFA; /* Texte clair */
    max-width: 800px;
    transition: all 0.3s ease; /* Animation douce */
'>
    <div style='flex: 1; min-width: 250px;'>
        <span style='color: #FFA726; font-size: 1.2em;'>🌍</span> 
        <strong style='color: #FFA726;'>Dernière ville consultée :</strong> 
        <span style='color: #64B5F6; font-weight: 600;'>{$last['ville']}</span><br>
        <span style='color: #B0BEC5; font-size: 0.9em;'>🕒 {$last['date_consultation']}</span>
    </div>
    <div style='margin-top: 0.5em;'>
        <a href='index.php?action=revoir&{$styleParam}#last-meteo' style='
            background: rgba(255, 167, 38, 0.15); /* Fond bouton */
            color: #FFA726;
            padding: 0.6em 1.2em;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid #FFA726;
            transition: all 0.3s ease;
            display: inline-block;
        '>
            <span style='margin-right: 0.5em;'>🔁</span> Revoir cette météo
        </a>
    </div>
</div>
";
    }
}
?>

<main>
    <section>
        <!-- Titre principal de la page -->
        <h1>Bienvenue sur Météo&Climat !</h1>
        
        <!-- Texte d'introduction -->
        <p>Découvrez en un coup d'œil la météo qu’il fait chez vous et partout en France !</p>
        <p>☀️ Soleil, 🌧️ pluie, ⛈️ orages… Soyez toujours prêt grâce à nos mises à jour fiables et détaillées.</p>

        <!-- Formulaire pour rechercher la météo d'une ville -->
        <form action="index.php#search-meteo" method="get" style=" justify-content: center; align-items: center;">
            <!-- Label pour le champ de saisie -->
            <label for="ville_search">Rechercher la météo d'une ville :</label>
            <!-- Champ de texte pour entrer le nom de la ville -->
            <input type="text" id="ville_search" name="ville_search" value="<?= htmlspecialchars($searchVille) ?>" placeholder="Entrez une ville" required/>
            <!-- Champ caché pour préserver le thème (style) -->
            <input type="hidden" name="style" value="<?= isset($_GET['style']) ? htmlspecialchars($_GET['style']) : (isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'normal') ?>"/>
            <!-- Bouton pour soumettre la recherche -->
            <button type="submit">🔍 Rechercher</button>
        </form>

        <!-- Affichage de la météo pour la ville recherchée -->
        <?php if ($searchWeatherData): ?>
            <!-- Bloc météo avec identifiant pour défilement automatique -->
            <section class="meteo-box" id="search-meteo">
                <h2>Météo pour <?= htmlspecialchars($searchVille) ?> <span>📍</span></h2>
                <div class="meteo-grid">
                    <!-- Icône météo, si disponible -->
                    <?php if (isset($searchWeatherData['icon'])): ?>
                        <div class="weather-icon">
                            <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($searchWeatherData['icon']) ?>@2x.png" alt="Icône météo"/>
                        </div>
                    <?php endif; ?>
                    <!-- Température -->
                    <div class="weather-item">
                        <strong>Température</strong>
                        <span><?= htmlspecialchars($searchWeatherData['temperature']) ?> °C</span>
                    </div>
                    <!-- Description -->
                    <div class="weather-item">
                        <strong>Description</strong>
                        <span><?= htmlspecialchars($searchWeatherData['description']) ?></span>
                    </div>
                    <!-- Humidité -->
                    <div class="weather-item">
                        <strong>Humidité</strong>
                        <span><?= htmlspecialchars($searchWeatherData['humidity']) ?> %</span>
                    </div>
                    <!-- Vitesse du vent -->
                    <div class="weather-item">
                        <strong>Vitesse du vent</strong>
                        <span><?= htmlspecialchars($searchWeatherData['wind_speed']) ?> m/s</span>
                    </div>
                </div>
            </section>
        <?php elseif ($searchError): ?>
            <!-- Bloc pour afficher les erreurs de recherche -->
            <section class="meteo-box" id="search-meteo">
                <p style="color: red;"><?= htmlspecialchars($searchError) ?></p>
            </section>
        <?php endif; ?>

        <!-- Affichage de la météo de la dernière ville consultée -->
        <?php if ($showLastMeteo && $meteoData && $lastVille): ?>
            <!-- Bloc météo avec identifiant pour défilement automatique -->
            <section class="meteo-box" id="last-meteo">
                <h2>Météo de votre dernière consultation : <?= htmlspecialchars($lastVille['ville']) ?> <span>📍</span></h2>
                <div class="meteo-grid">
                    <!-- Icône météo, si disponible -->
                    <?php if (isset($meteoData['icon'])): ?>
                        <div class="weather-icon">
                            <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($meteoData['icon']) ?>@2x.png" alt="Icône météo"/>
                        </div>
                    <?php endif; ?>
                    <!-- Température -->
                    <div class="weather-item">
                        <strong>Température</strong>
                        <span><?= htmlspecialchars($meteoData['temperature']) ?> °C</span>
                    </div>
                    <!-- Description -->
                    <div class="weather-item">
                        <strong>Description</strong>
                        <span><?= htmlspecialchars($meteoData['description']) ?></span>
                    </div>
                    <!-- Humidité -->
                    <div class="weather-item">
                        <strong>Humidité</strong>
                        <span><?= htmlspecialchars($meteoData['humidity']) ?> %</span>
                    </div>
                    <!-- Vitesse du vent -->
                    <div class="weather-item">
                        <strong>Vitesse du vent</strong>
                        <span><?= htmlspecialchars($meteoData['wind_speed']) ?> m/s</span>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Affichage de la météo locale -->
        <?php if ($weatherData && isset($localisation['ville'])): ?>
            <!-- Bloc météo pour la localisation actuelle de l'utilisateur -->
            <section class="meteo-box">
                <h2>Météo à <?= htmlspecialchars($localisation['ville']) ?> <span>📍</span></h2>
                <div class="meteo-grid">
                    <!-- Icône météo, si disponible -->
                    <?php if (isset($weatherData['icon'])): ?>
                        <div class="weather-icon">
                            <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($weatherData['icon']) ?>@2x.png" alt="Icône météo"/>
                        </div>
                    <?php endif; ?>
                    <!-- Température -->
                    <div class="weather-item">
                        <strong>Température</strong>
                        <span><?= htmlspecialchars($weatherData['temperature']) ?> °C</span>
                    </div>
                    <!-- Description -->
                    <div class="weather-item">
                        <strong>Description</strong>
                        <span><?= htmlspecialchars($weatherData['description']) ?></span>
                    </div>
                    <!-- Humidité -->
                    <div class="weather-item">
                        <strong>Humidité</strong>
                        <span><?= htmlspecialchars($weatherData['humidity']) ?> %</span>
                    </div>
                    <!-- Vitesse du vent -->
                    <div class="weather-item">
                        <strong>Vitesse du vent</strong>
                        <span><?= htmlspecialchars($weatherData['wind_speed']) ?> m/s</span>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Message de conclusion -->
        <p><strong>Restez informé, restez préparé !</strong></p>
    </section>

    <!-- Section avec des liens vers d'autres pages -->
    <section class="links-section">
        <h2>Consultez dès maintenant 📝</h2>
        <a href="./previsions.php">🌤️ La Météo de votre région</a>
        <a href="./tech.php">🔭 L'image du jour de la NASA</a>
        <a href="./statistiques.php">📊 L'historique des villes consultées</a>
        <p style="font-size: 1.2em">
            🎲 Chaque visite vous réserve une surprise ! Découvrez aléatoirement l'une des images du projet à chaque rafraîchissement de la page. Qui sait ce que vous verrez ensuite ?
        </p>

        <?php
        // Affiche une image aléatoire (fonction définie dans functions.inc.php)
        afficherImageAleatoire();
        ?>
    </section>

    <!-- Bouton pour remonter en haut de la page -->
    <a href="#" class="back-to-top">↑</a>
</main>

<?php
// Inclut le pied de page (footer.inc.php) avec les éléments de fin (liens, scripts, etc.)
require "./include/footer.inc.php";
?>