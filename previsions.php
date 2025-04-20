<?php

/**
 * @file previsions.php
 * @brief Page pour sélectionner une région, un département et une ville pour consulter les prévisions météo
 * @author Fariza Amzal , Nadjib Moussaoui 
 * @version 1.0
 * @date Avril 2025
 * @details Affiche une carte interactive de France et un formulaire pour choisir une région, un département, et une ville. Utilise des données CSV pour la structure hiérarchique. Gère les thèmes clair/sombre et la langue via cookies.
 * @see functions.inc.php pour les fonctions construire_regions_departements_villes() et organizeData()
 */

/**
 * @brief Charge les traductions et gère la langue
 * @details Inclut lang.inc.php pour définir la fonction t() et la variable $lang. Définit le cookie de langue pour français ou anglais.
 * @var string $lang Langue courante ('fr' ou 'en')
 */
// Charge les traductions pour définir t() et $lang
require_once "./include/lang.inc.php";

// Gère la langue (français par défaut)
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + 90*24*3600, '/', '', false, true);
} else {
    $lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr';
}


/**
 * @brief Définit le titre de la page traduit
 * @var string $title Titre traduit pour la page des prévisions
 */
// Définit le titre de la page, traduit en fonction de la langue
$title = t('title_previsions', $lang);

// Inclut le fichier d'en-tête (menu traduit, styles, cookie lang/theme)
require "./include/header.inc.php";

/**
 * @brief Inclut les fonctions utilitaires
 * @details Charge functions.inc.php pour les fonctions de gestion des données CSV.
 */
// Inclut les fonctions utilitaires
include_once "./include/functions.inc.php";


/**
 * @brief Construit les données hiérarchiques
 * @var array $data Structure contenant régions, départements, et villes
 * @details Appelle construire_regions_departements_villes() pour organiser les données des fichiers CSV.
 */
// Construit les données des régions, départements, villes à partir des CSV
$data = construire_regions_departements_villes("v_region_2024.csv", "v_departement_2024.csv", "cities.csv");

/**
 * @brief Récupère la région sélectionnée
 * @var string|null $selected_region Région sélectionnée via GET
 * @details Utilisée pour préremplir le formulaire ou rediriger vers regions.php.
 */
// Récupère la région sélectionnée depuis l'URL, null si non définie
$selected_region = $_GET['region'] ?? null;

/**
 * @brief Gère le paramètre de style
 * @var string $styleParam Paramètre de style pour l'URL, basé sur GET ou cookie
 * @details Définit le thème clair ou sombre.
 */
// Gère le paramètre de style (thème)
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');


/**
 * @brief Définit les coordonnées des régions pour la carte interactive
 * @var array $region_coords Coordonnées polygonales pour chaque région sur la carte
 * @details Associe chaque région à des coordonnées pour rendre la carte cliquable.
 */
// Associer chaque région à ses coordonnées pour la carte interactive
$region_coords = [
    "Île-de-France" => "339,143,361,143,371,154,372,179,356,196,318,182,291,148,310,136",
    "Hauts-de-France" => "307,29,340,29,404,82,385,141,369,147,309,132,294,82",
    "Grand Est" => "446,227,425,204,393,206,377,158,405,99,429,82,434,103,493,122,513,138,547,143,565,156,540,191,544,232,511,217,479,204",
    "Bourgogne-Franche-Comté" => "436,291,406,312,385,282,354,271,367,183,400,206,428,207,456,225,479,208,514,213,527,240,502,275,467,303,450,299",
    "Auvergne-Rhône-Alpes" => "467,397,458,425,406,420,381,386,321,396,329,365,343,344,332,295,352,283,377,283,402,311,439,296,471,309,508,294,524,361",
    "Provence-Alpes-Côte d'Azur" => "505,484,480,491,430,473,424,422,459,428,468,399,488,389,501,375,516,393,517,424,546,433",
    "Corse" => "614,498,620,539,614,577,605,590,587,563,588,526",
    "Occitanie" => "414,458,365,483,339,533,307,519,223,507,242,445,271,436,298,392,336,399,384,390",
    "Nouvelle-Aquitaine" => "186,493,179,319,202,296,240,257,299,299,339,319,296,381,229,440,221,509",
    "Pays de la Loire" => "142,252,159,294,200,303,226,256,267,202,251,186,229,182,213,177,198,188,180,221,147,235",
    "Bretagne" => "41,179,49,202,61,210,127,232,183,214,191,179,170,169,130,172,105,158,86,164,58,173",
    "Normandie" => "161,106,170,138,179,169,273,193,275,164,301,127,302,101,242,105,239,132,186,122",
    "Centre-Val de Loire" => "339,286,308,294,267,272,238,247,265,211,276,189,272,168,288,164,314,180,351,199,357,268",
];


/**
 * @brief Organise les données CSV
 * @var array $tab Données organisées avec clés 'regions', 'departements', 'villes'
 * @var array $regions Liste des régions
 * @var array $departements Liste des départements
 * @var array $villes Liste des villes
 * @details Appelle organizeData() pour structurer les données.
 */
// Charger et organiser les données
$tab = organizeData("v_region_2024.csv", "v_departement_2024.csv", "cities.csv");

// Extraire les données organisées
$regions = $tab['regions'];
$departements = $tab['departements'];
$villes = $tab['villes'];

/**
 * @brief Récupère les sélections du formulaire
 * @var string $selectedRegion Région sélectionnée
 * @var string $selectedDepartement Département sélectionné
 * @var string $selectedVille Ville sélectionnée
 * @details Utilisées pour préremplir le formulaire.
 */
// Récupérer les valeurs sélectionnées (si le formulaire a été soumis)
$selectedRegion = isset($_GET['region']) ? $_GET['region'] : '';
$selectedDepartement = isset($_GET['departement']) ? $_GET['departement'] : '';
$selectedVille = isset($_GET['ville']) ? $_GET['ville'] : '';
?>

<main>
<section>
    <!-- Titre traduit -->
    <h1><?= t('forecast_title', $lang) ?></h1>
    <!-- Instruction pour la carte -->
    <p><?= t('select_region_map', $lang) ?></p>

    <!-- Conteneur centré -->
    <figure class="map-container">
        <img src="./images/CarteDeFrance.jpeg" alt="Carte de France" usemap="#image-map"/>
        <figcaption><?= t('map_caption', $lang) ?></figcaption>
    </figure>

    <!-- Une image map permet de rendre certaines zones d'une image interactives (cliquables) -->
    <map name="image-map">
        <?php foreach ($data as $region => $deps): ?>
            <?php
            // Coordonnées par défaut si non définies
            $coords = $region_coords[$region] ?? "0,0,0,0,0,0";
            ?>
            <area alt="<?= htmlspecialchars($region) ?>" 
                  title="<?= htmlspecialchars($region) ?>"
                  href="./regions.php?region=<?= urlencode($region) ?>&amp;<?= $styleParam ?>&amp;lang=<?= $lang ?>" 
                  coords="<?= $coords ?>" shape="poly"/>
        <?php endforeach; ?>
    </map>
</section>

<section>
    <!-- Titre de la section -->
    <h2><?= t('consult_weather_title', $lang) ?></h2>
    <!-- Instruction -->
    <p><?= t('consult_weather_text', $lang) ?></p>

    <!-- Formulaire -->
    <?php
    // Initialisation de l'ancre pour le défilement
    $ancre = '';
    if (!empty($selectedRegion) && empty($selectedDepartement)) {
        $ancre = '#departement';
    } elseif (!empty($selectedDepartement) && empty($selectedVille)) {
        $ancre = '#ville';
    }
    ?>

    <!-- Formulaire traduit -->
    <form action="<?= !empty($selectedVille) ? 'meteo.php?lang=' . $lang : htmlspecialchars($_SERVER['PHP_SELF'] . $ancre) ?>" method="get">
        <!-- Conserver le paramètre de style et langue -->
        <input type="hidden" name="style" value="<?= isset($_GET['style']) ? htmlspecialchars($_GET['style']) : (isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'normal') ?>"/>
        <input type="hidden" name="lang" value="<?= $lang ?>"/>

        <!-- Région -->
        <label for="region"><?= t('label_region', $lang) ?></label>
        <select id="region" name="region" onchange="this.form.submit()">
            <option value=""><?= t('option_region', $lang) ?></option>
            <?php foreach ($regions as $code => $region): ?>
                <option value="<?= htmlspecialchars($region['name']) ?>" 
                        <?= $selectedRegion === $region['name'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($region['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Département -->
        <label for="departement"><?= t('label_departement', $lang) ?></label>
        <select id="departement" name="departement" 
                <?= empty($selectedRegion) ? 'disabled="disabled"' : '' ?> 
                onchange="this.form.submit()" required="">
            <option value=""><?= empty($selectedRegion) ? t('option_departement_region', $lang) : t('option_departement', $lang) ?></option>
            <?php if (!empty($selectedRegion)): ?>
                <?php foreach ($departements as $dep): ?>
                    <?php 
                    $depRegionName = $regions[$dep['region_code']]['name'] ?? '';
                    if ($depRegionName === $selectedRegion): ?>
                        <option value="<?= htmlspecialchars($dep['code']) ?>"
                                <?= ($selectedDepartement === $dep['code']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dep['name'] . ' (' . $dep['code'] . ')') ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <!-- Ville -->
        <label for="ville"><?= t('label_ville', $lang) ?></label>
        <select id="ville" name="ville" 
                <?= empty($selectedDepartement) ? 'disabled="disabled"' : '' ?> 
                onchange="if(this.value) { 
                    document.getElementById('lat').value = this.options[this.selectedIndex].getAttribute('data-lat');
                    document.getElementById('lon').value = this.options[this.selectedIndex].getAttribute('data-lon');
                    this.form.submit(); 
                }" required="">
            <option value=""><?= empty($selectedDepartement) ? t('option_ville_departement', $lang) : t('option_ville', $lang) ?></option>
            <?php if (!empty($selectedDepartement)): ?>
                <?php foreach ($villes as $label => $ville): ?>
                    <?php if ($ville['departement_number'] === $selectedDepartement): ?>
                        <option value="<?= htmlspecialchars($label) ?>"
                                data-lat="<?= htmlspecialchars($ville['latitude']) ?>"
                                data-lon="<?= htmlspecialchars($ville['longitude']) ?>"
                                <?= ($selectedVille === $label) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <!-- Champs cachés pour latitude/longitude -->
        <input type="hidden" name="lat" id="lat" value="<?= 
            !empty($selectedVille) && isset($villes[$selectedVille]['latitude']) 
            ? htmlspecialchars($villes[$selectedVille]['latitude']) 
            : '' ?>"/>
        <input type="hidden" name="lon" id="lon" value="<?= 
            !empty($selectedVille) && isset($villes[$selectedVille]['longitude']) 
            ? htmlspecialchars($villes[$selectedVille]['longitude']) 
            : '' ?>"/>

        <!-- Bouton soumis -->
        <input type="submit" name="submitBtn" value="<?= t('submit_weather', $lang) ?>" 
               <?= empty($selectedVille) ? 'disabled="disabled"' : '' ?>/>
    </form>
</section>

<!-- Bouton retour en haut -->
<a href="#" class="back-to-top">↑</a>
</main>

<?php
/**
 * @brief Inclut le pied de page
 * @details Charge footer.inc.php pour les liens et informations finales de la page.
 */
require "./include/footer.inc.php";
?>