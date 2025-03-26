<?php
// 🔹 1. NASA APOD (JSON) - Date dynamique
$apiKey = "WejnCDC0fIVzW3tEUw6p9E20Ct2FN6r5IZFuirda";
$date = date("Y-m-d"); // Date du jour actuel (ex. 2025-03-24 aujourd'hui)
$apiUrl = "https://api.nasa.gov/planetary/apod?api_key=$apiKey&date=$date";
$response = @file_get_contents($apiUrl);

    $data = json_decode($response, true);
    $title = $data['title'] ?? "Titre non disponible";
    $mediaUrl = $data['url'] ?? "";
    $mediaType = $data['media_type'] ?? "";
    $description = $data['explanation'] ??  "Erreur : données NASA indisponibles";


// 🔹 2. GeoPlugin (XML)
$iip = "193.54.115.192";
$geoUrl = "http://www.geoplugin.net/xml.gp?ip=$iip";
$geoXml = @simplexml_load_file($geoUrl);
$city = $geoXml->geoplugin_city?? "Ville non détectée";
$country = $geoXml->geoplugin_countryName ?? "Pays non détecté";

// 🔹 3. ipinfo.io (JSON supplémentaire)
$ip = $_SERVER['REMOTE_ADDR']; // Récupère l'IP du visiteur
$ipinfoUrl = "https://ipinfo.io/$ip/geo"; // URL de l'API
$ipinfoResponse = @file_get_contents($ipinfoUrl);
$ipinfoCity = "Non détectée";
$ipinfoCountry = "Non détecté";
if ($ipinfoResponse !== false) {
    $ipinfoData = json_decode($ipinfoResponse, true);
    $ipinfoCity = $ipinfoData['city'] ?? "Non détectée";
    $ipinfoCountry = $ipinfoData['country'] ?? "Non détecté";
}

// 🔹 4. whatismyip.com (XML)
$whatismyipKey = "2ea156fc65a7bf50eea4cc7c9364c4e6";
$whatismyipUrl = "https://api.whatismyip.com/ip-address-lookup.php?key=$whatismyipKey&input=193.54.115.235&output=xml";
$whatismyipXml = @simplexml_load_file($whatismyipUrl);
$whatismyipCity = $whatismyipXml->server_data->city ?? "Non détectée";
$whatismyipCountry = $whatismyipXml->server_data->country ?? "Non détecté";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <title>Page Tech</title>
    <meta name="author" content="Fariza et Nadjib"/>
    <link rel="icon" href="./images/favicon.jpeg"/>
    <link rel="stylesheet" href="styles.css"/>
    <style>
        header, footer {
            background-color: #4682B4; /* Bleu foncé */
            color: white;
            text-align: center;
            padding: 20px;
        }

    </style>
</head>
<body>
    <header></header>
    <h1>Bienvenue sur la page Développeur</h1>

    <!-- 🔹 NASA APOD -->
    <h2>🌌 Image/Vidéo du jour (NASA - <?php echo $date; ?>)</h2>
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
    <h2>📍 Localisation GeoPlugin (IP : <?php echo $iip; ?>)</h2>
    <p>Ville : <?php echo $city; ?></p>
    <p>Pays : <?php echo $country; ?></p>

    <!-- 🔹 ipinfo.io -->
    <h2>📍 Localisation ipinfo.io (IP : <?php echo $ip;?>)</h2>
    <p>Ville : <?php echo $ipinfoCity; ?></p>
    <p>Pays : <?php echo $ipinfoCountry; ?></p>

    <!-- 🔹 whatismyip.com -->
    <h2>📍 Localisation whatismyip.com (IP : 193.54.115.235)</h2>
    <p>Ville : <?php echo $whatismyipCity; ?></p>
    <p>Pays : <?php echo $whatismyipCountry; ?></p>

    <footer>
        <div><a href="index.php">Retour à l'accueil</a></div>
        <a href="https://www.cyu.fr/"> <img src="./images/logo.png" alt="logo cy " width="100"/></a>
        <div>&#169; Fariza AMZAL, Nadjib MOUSSAOUI</div>
            <div>mis à jour le 26 mars 2025</div>
    </footer>
</body>
</html>