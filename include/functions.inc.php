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


// Définition du fichier où sera stocké le compteur
define("FICHIER_COMPTEUR", "compteur.txt");

/**
 * Initialise le fichier compteur s'il n'existe pas.
 */
function initialiserCompteur() {
    if (!file_exists(FICHIER_COMPTEUR)) {
        file_put_contents(FICHIER_COMPTEUR, "0");
    }
}

/**
 * Récupère la valeur actuelle du compteur.
 *
 * @return int Nombre de visites enregistrées.
 */
function lireCompteur() {
    initialiserCompteur(); // S'assure que le fichier existe
    return (int) file_get_contents(FICHIER_COMPTEUR);
}

/**
 * Incrémente le compteur de 1 et sauvegarde la nouvelle valeur.
 */
function incrementerCompteur() {
    $compteur = lireCompteur() + 1; // Lire et incrémenter
    file_put_contents(FICHIER_COMPTEUR, $compteur); // Sauvegarder
}

/**
 * Affiche le compteur dans le footer.
 */
function afficherCompteur() {
    echo "Nombre de visites : " . lireCompteur();
}


/**
 * Lit un fichier CSV et retourne un tableau associatif
 *
 * @param string $fichier Le chemin du fichier CSV à lire
 * @return array Un tableau contenant les données du fichier CSV sous forme de tableau associatif
 */
function lire_csv($fichier) {
    $donnees = []; // Initialisation d'un tableau vide pour stocker les données

    // Ouvrir le fichier CSV en mode lecture
    if (($handle = fopen($fichier, "r")) !== FALSE) {
        $entetes = fgetcsv($handle, 1000, ","); // Lire la première ligne (en-têtes de colonnes)

        // Lire chaque ligne du fichier CSV
        while (($ligne = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $donnees[] = array_combine($entetes, $ligne); // Associer les valeurs aux clés des en-têtes
        }

        fclose($handle); // Fermer le fichier après lecture
    }

    return $donnees; // Retourner les données lues
}

/**
 * Construit un tableau associatif contenant la liste des régions et leurs départements
 *
 * @param string $fichier_regions Le fichier CSV contenant les régions
 * @param string $fichier_departements Le fichier CSV contenant les départements
 * @return array Un tableau associatif avec les noms des régions comme clé et une liste des départements comme valeur
 */
function construire_regions_departements_villes($fichier_regions, $fichier_departements, $fichier_villes) {
    $regions = lire_csv($fichier_regions);
    $departements = lire_csv($fichier_departements);
    $villes = lire_csv($fichier_villes);

    $regions_dict = [];
    foreach ($regions as $region) {
        $regions_dict[$region["REG"]] = $region["LIBELLE"];
    }

    $resultat = [];
    foreach ($departements as $dep) {
        $code_region = $dep["REG"];
        $num_dep = $dep["DEP"];
        $nom_dep = $dep["NCCENR"];
        if (isset($regions_dict[$code_region])) {
            $nom_region = $regions_dict[$code_region];
            if (!isset($resultat[$nom_region])) {
                $resultat[$nom_region] = [];
            }
            $resultat[$nom_region][$num_dep] = ["nom" => $nom_dep, "villes" => []];
        }
    }

    foreach ($villes as $ville) {
        $code_dep = $ville["department_number"]; // Remplacé CODE_DEP par department_number
        $nom_ville = $ville["city_code"]; // Remplacé NOM_VILLE par city
        $lat = $ville["latitude"]; // Remplacé LATITUDE par lattitude
        $lon = $ville["longitude"]; // Remplacé LONGITUDE par longitude
        foreach ($resultat as $nom_region => &$deps) {
            if (isset($deps[$code_dep])) {
                $deps[$code_dep]["villes"][] = [
                    "nom" => $nom_ville,
                    "lat" => $lat,
                    "lon" => $lon
                ];
            }
        }
    }

    return $resultat;
}

/**
 * Affiche les régions et leurs départements sous forme de liste de définition HTML
 *
 * @param array $regions_departements Tableau associatif des régions et de leurs départements
 */
function afficher_liste_definitions($regions_departements) {
    echo "<dl>\n"; // Début de la liste de définition
    foreach ($regions_departements as $nom_region => $departements) {
        echo "<dt><strong>$nom_region</strong></dt>\n"; // Affichage du nom de la région
        foreach ($departements as $dep) {
            echo "<dd>{$dep['num']} - {$dep['nom']}</dd>\n"; // Affichage des départements (numéro - nom)
        }
    }
    echo "</dl>"; // Fin de la liste de définition
}

/**
 * Récupère les données météo via l'API OpenWeatherMap
 * @param float $lat Latitude de la ville
 * @param float $lon Longitude de la ville
 * @param string $apiKey Clé API OpenWeatherMap
 * @return array Données météo
 */
function getWeatherData($lat, $lon, $apiKey) {
    $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$apiKey&units=metric&lang=fr";
    $response = file_get_contents($weatherUrl);

    if ($response !== false) {
        $data = json_decode($response, true);
        return [
            'temperature' => $data['main']['temp'] ?? "Non disponible",
            'description' => $data['weather'][0]['description'] ?? "Non disponible",
            'humidity' => $data['main']['humidity'] ?? "Non disponible",
            'wind_speed' => $data['wind']['speed'] ?? "Non disponible",
        ];
    }
    return [
        'temperature' => "Erreur",
        'description' => "Erreur lors de la récupération des données météo",
        'humidity' => "Erreur",
        'wind_speed' => "Erreur",
    ];
}

?>
