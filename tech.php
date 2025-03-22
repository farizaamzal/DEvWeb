<?php
// 🔹 1. Récupérer l'image du jour de la NASA en JSON
$apiKey = "DEMO_KEY"; // Remplace par ta propre clé API de la NASA
$apiUrl = "https://api.nasa.gov/planetary/apod?api_key=$apiKey";
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);
$mediaUrl = $data['url'];
$mediaType = $data['media_type'];
$description = $data['explanation'];

// 🔹 2. Récupérer la localisation de l'utilisateur via son IP (format XML)
$geoXml = simplexml_load_file("http://www.geoplugin.net/xml.gp");
$city = isset($geoXml->geoplugin_city) && !empty($geoXml->geoplugin_city) ? $geoXml->geoplugin_city : "Ville non détectée";
$country = $geoXml->geoplugin_countryName;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Tech</title>
    <meta name="author" content="Fariza et Nadjib"/>
    <link rel="icon" href="./images/favicon.jpeg"/>
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>
    <h1>Bienvenue sur la page Développeur</h1>

    <!-- 🔹 Affichage de l’image ou de la vidéo du jour -->
    <h2>🌌 Image/Vidéo du jour (NASA)</h2>
    <?php if ($mediaType == "image"): ?>
        <img src="<?php echo $mediaUrl; ?>" alt="Image de la NASA" width="400">
    <?php else: ?>
        <video width="400" controls>
            <source src="<?php echo $mediaUrl; ?>" type="video/mp4">
            Votre navigateur ne supporte pas la vidéo.
        </video>
    <?php endif; ?>
    <p><?php echo $description; ?></p>

    <!-- 🔹 Affichage de la localisation de l'utilisateur -->
    <h2>📍 Votre localisation</h2>
    <p>Ville : <?php echo $city ?: "Non détectée"; ?></p>
    <p>Pays : <?php echo $country ?: "Non détecté"; ?></p>
</body>
</html>
