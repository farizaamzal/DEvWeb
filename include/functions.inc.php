<?php

/**
 * Fonction pour obtenir les données de l'API NASA (APOD).
 * Cette fonction récupère les données d'image de la NASA du jour (Astronomy Picture of the Day).
 *
 * @param string $apiKey La clé API de la NASA pour s'authentifier.
 * @return array Tableau associatif contenant le titre, l'URL, le type de média et la description de l'image.
 */
function getNASAData($apiKey) {
    // Récupérer la date du jour au format YYYY-MM-DD
    $date = date("Y-m-d"); // Exemple : 2025-03-24
    // URL de l'API avec la clé API et la date du jour
    $apiUrl = "https://api.nasa.gov/planetary/apod?api_key=$apiKey&date=$date";
    // Envoi de la requête HTTP à l'API et récupération de la réponse JSON
    $response = file_get_contents($apiUrl);

    // Vérification si la réponse est valide
    if ($response !== false) {
        // Décodage du JSON reçu en tableau associatif PHP
        $data = json_decode($response, true);
        // Retourne les informations essentielles : titre, URL, type de média, et explication
        return [
            'title' => $data['title'] ?? "Titre non disponible", // Titre de l'image
            'url' => $data['url'] ?? "", // URL de l'image
            'media_type' => $data['media_type'] ?? "", // Type de média (image/vidéo)
            'explanation' => $data['explanation'] ?? "Erreur : données NASA indisponibles" // Explication de l'image
        ];
    }
    // Si la réponse est invalide, retourner un message d'erreur
    return [
        'title' => 'Erreur',
        'explanation' => 'Erreur lors de la récupération des données de NASA'
    ];
}

/**
 * Fonction pour obtenir la ville et le pays avec GeoPlugin (XML).
 * Cette fonction utilise l'API GeoPlugin pour récupérer la localisation basée sur l'IP.
 *
 * @param string $ip L'IP à utiliser pour la recherche de la localisation.
 * @return array Tableau associatif contenant la ville et le pays de l'IP.
 */
function getGeoPluginData($ip) {
    // URL de l'API GeoPlugin avec l'IP
    $geoUrl = "http://www.geoplugin.net/xml.gp?ip=$ip";
    // Récupération et chargement du XML retourné par l'API
    $geoXml = @simplexml_load_file($geoUrl);

    // Si les données XML sont valides
    if ($geoXml !== false) {
        // Retourner la ville et le pays
        return [
            'city' => $geoXml->geoplugin_city ?? "Ville non détectée", // Ville récupérée ou défaut
            'country' => $geoXml->geoplugin_countryName ?? "Pays non détecté" // Pays récupéré ou défaut
        ];
    }
    // Si les données sont invalides, retourner des valeurs par défaut
    return [
        'city' => 'Ville non détectée',
        'country' => 'Pays non détecté'
    ];
}

/**
 * Fonction pour obtenir la ville et le pays avec ipinfo.io (JSON).
 * Cette fonction utilise l'API ipinfo.io pour récupérer la localisation basée sur l'IP.
 *
 * @param string $ip L'IP à utiliser pour la recherche de la localisation.
 * @return array Tableau associatif contenant la ville et le pays de l'IP.
 */
function getIPInfoData($ip) {
    // URL de l'API ipinfo.io pour récupérer les données géographiques
    $ipinfoUrl = "https://ipinfo.io/$ip/geo";
    // Envoi de la requête HTTP à l'API et récupération de la réponse JSON
    $ipinfoResponse = @file_get_contents($ipinfoUrl);
    
    // Si la réponse est valide
    if ($ipinfoResponse !== false) {
        // Décodage du JSON reçu en tableau associatif PHP
        $ipinfoData = json_decode($ipinfoResponse, true);
        // Retourner la ville et le pays
        return [
            'city' => $ipinfoData['city'] ?? "Non détectée", // Ville récupérée ou défaut
            'country' => $ipinfoData['country'] ?? "Non détecté" // Pays récupéré ou défaut
        ];
    }
    // Si les données sont invalides, retourner des valeurs par défaut
    return [
        'city' => 'Non détectée',
        'country' => 'Non détecté'
    ];
}

/**
 * Fonction pour obtenir les données de WhatIsMyIP (XML).
 * Cette fonction interroge l'API WhatIsMyIP pour obtenir la localisation d'une IP donnée.
 *
 * @param string $apiKey La clé API pour interroger l'API WhatIsMyIP.
 * @param string $ip L'IP à utiliser pour la recherche de la localisation.
 * @return array Tableau associatif contenant la ville et le pays de l'IP.
 */
function getWhatIsMyIPData($apik, $ip) {
    // URL de l'API WhatIsMyIP avec la clé API et l'IP à interroger
    $whatismyipUrl = "https://api.whatismyip.com/ip-address-lookup.php?key=$apik&input=$ip&output=xml";
    // Récupération et chargement du XML retourné par l'API
    $whatismyipXml = simplexml_load_file($whatismyipUrl);

    // Si les données XML sont valides
    if ($whatismyipXml !== false && isset($whatismyipXml->server_data)) {
        // Retourner la ville et le pays
        return [
            'city' => (string)$whatismyipXml->server_data->city ?? "Non détectée", // Ville récupérée ou défaut
            'country' => (string)$whatismyipXml->server_data->country ?? "Non détecté" // Pays récupéré ou défaut
        ];
    }
    // Si les données sont invalides, retourner des valeurs par défaut
    return [
        'city' => 'Non détectée',
        'country' => 'Non détecté'
    ];
}

?>
