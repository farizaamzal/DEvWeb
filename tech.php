<?php
$title="Page Tech - météo&Climat";
require './include/header.inc.php';
// Inclure le fichier contenant les fonctions
require './include/functions.inc.php'; 

// Récupérer la clé API de la NASA
$apiKey = "WejnCDC0fIVzW3tEUw6p9E20Ct2FN6r5IZFuirda"; 

// Récupérer les données de la NASA
$nasaData = getNASAData($apiKey); 
$title = $nasaData['title'];
$mediaUrl = $nasaData['url'];
$mediaType = $nasaData['media_type'];
$description = $nasaData['explanation'];

// Récupérer l'IP publique du visiteur
$userIP = $_SERVER['REMOTE_ADDR']; 

// Récupérer les données de géolocalisation à partir de GeoPlugin
$geoPluginData = getGeoPluginData($userIP); 
$cityGeoPlugin = $geoPluginData['city'];
$countryGeoPlugin = $geoPluginData['country'];

// Récupérer les données de géolocalisation à partir de ipinfo.io
$ipInfoData = getIPInfoData($userIP);
$cityIpInfo = $ipInfoData['city'];
$countryIpInfo = $ipInfoData['country'];

// Récupérer les données de géolocalisation à partir de WhatIsMyIP
$apik="f84419e2fa26e6152e63e6e6cca02cf7";
$whatIsMyIPData = getWhatIsMyIPData($apik, $userIP);
$cityWhatIsMyIP = $whatIsMyIPData['city'];
$countryWhatIsMyIP = $whatIsMyIPData['country'];

?>
<main>
<h1>Bienvenue sur la page Développeur</h1>

<!-- 🔹 NASA APOD -->
<h2>🌌 Image/Vidéo du jour (NASA - <?php echo date("Y-m-d"); ?>)</h2>
<h3><?php echo $title;?></h3>
<?php if ($mediaUrl): ?>
    <?php if ($mediaType == "image"): ?>
        <img src="<?php echo $mediaUrl; ?>" alt="Image NASA" width="400"/>
    <?php elseif ($mediaType == "video"): ?>
        <video width="400" controls>
            <source src="<?php echo $mediaUrl; ?>" type="video/mp4"/>
            Votre navigateur ne supporte pas la vidéo.
        </video>
    <?php else: ?>
        <p>Type de média inconnu : <?php echo $mediaType; ?> (données du jour indisponibles ou format non supporté)</p>
    <?php endif; ?>
<?php else: ?>
    <p>Erreur : impossible de charger le média NASA (données du jour peut-être indisponibles).</p>
<?php endif; ?>
<p><?php echo $description; ?></p>

<!-- 🔹 GeoPlugin -->
<h2>📍 Localisation GeoPlugin (IP : <?php echo $userIP; ?>)</h2>
<p>Ville : <?php echo $cityGeoPlugin; ?></p>
<p>Pays : <?php echo $countryGeoPlugin; ?></p>

<!-- 🔹 ipinfo.io -->
<h2>📍 Localisation ipinfo.io (IP : <?php echo $userIP; ?>)</h2>
<p>Ville : <?php echo $cityIpInfo; ?></p>
<p>Pays : <?php echo $countryIpInfo; ?></p>

<!-- 🔹 whatismyip.com -->
<h2>📍 Localisation whatismyip.com (IP : <?php echo $userIP; ?>)</h2>
<p>Ville : <?php echo $cityWhatIsMyIP; ?></p>
<p>Pays : <?php echo $countryWhatIsMyIP; ?></p>
<!-- Bouton retour en haut -->
<a href="#" class="back-to-top">↑</a>
</main>
<?php
    require "./include/footer.inc.php";
?>