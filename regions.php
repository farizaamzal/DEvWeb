<?php

/**
 * @file regions.php
 * @brief Page pour sélectionner un département et une ville dans une région donnée
 * @author Fariza Amzal , Nadjib Moussaoui 
 * @version 1.0
 * @date Avril 2025
 * @details Affiche une liste de départements pour une région sélectionnée, puis une liste de villes pour un département sélectionné. Utilise des données CSV pour la structure hiérarchique. Gère les thèmes clair/sombre et la langue via cookies.
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
 * @var string $title Titre traduit pour la page de sélection des régions
 */
// Définit le titre de la page, traduit en fonction de la langue
$title = t('title_regions', $lang);


/**
 * @brief Inclut les fichiers nécessaires
 * @details Charge header.inc.php pour le menu traduit, les styles CSS, et les cookies de langue et thème.
 */
// Inclut les fichiers nécessaires
require "./include/header.inc.php";

/**
 * @brief Charge les fonctions utilitaires
 * @details Inclut functions.inc.php pour les fonctions de gestion des données CSV.
 */
require "./include/functions.inc.php";


/**
 * @brief Récupère les paramètres de région et de style
 * @var string|null $selected_region Région sélectionnée via GET
 * @var string $styleParam Paramètre de style pour l'URL, basé sur GET ou cookie
 * @details Définit la région sélectionnée et le thème clair/sombre.
 */
// Définir $selected_region et $styleParam
$selected_region = $_GET['region'] ?? null;
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');


/**
 * @brief Charge les données hiérarchiques
 * @var array $data Structure contenant régions, départements, et villes
 * @details Appelle construire_regions_departements_villes() pour organiser les données des fichiers CSV.
 */
// Charger les données
$data = construire_regions_departements_villes("v_region_2024.csv", "v_departement_2024.csv", "cities.csv");


/**
 * @brief Vérifie la validité de la région
 * @details Redirige vers index.php si la région est invalide ou non définie.
 */
// Vérifier si la région existe dans les données
if (!$selected_region || !isset($data[$selected_region])) {
    // Rediriger vers index.php si la région est invalide
    header("Location: index.php?lang=$lang&$styleParam");
    exit;
}


/**
 * @brief Récupère le département sélectionné
 * @var string|null $selected_departement Département sélectionné via GET
 * @details Utilisé pour afficher les villes correspondantes si défini.
 */
// Récupérer le département sélectionné (s'il y en a un)
$selected_departement = $_GET['departement'] ?? null;


/**
 * @brief Organise les données CSV
 * @var array $tab Données organisées avec clés 'regions', 'departements', 'villes'
 * @var array $regions Liste des régions
 * @details Appelle organizeData() pour structurer les données.
 */
$tab = organizeData('v_region_2024.csv', 'v_departement_2024.csv', 'cities.csv');
$regions = $tab['regions'];


/**
 * @brief Définit une ancre HTML dynamique
 * @var string $ancre Ancre pour défiler vers '#departement' ou '#ville'
 * @details Détermine l’ancre en fonction des sélections pour améliorer la navigation.
 */
// Détermine dynamiquement une ancre HTML (#departement ou #ville)
// pour revenir automatiquement à la bonne section après soumission du formulaire
$ancre = '';
if (!empty($selectedRegion) && empty($selectedDepartement)) {
    // Si une région est sélectionnée mais pas de département → revenir à la section départements
    $ancre = '#departement';
} elseif (!empty($selectedDepartement) && empty($selectedVille)) {
    // Si un département est sélectionné mais pas de ville → revenir à la section villes
    $ancre = '#ville';
}
?>

<main>
    <!-- Titre traduit -->
    <h1><?= t('selection_heading', $lang) ?></h1>
    <section>
        <!-- Région sélectionnée -->
        <h2><?= t('region_selected', $lang) ?><?php echo htmlspecialchars($selected_region); ?></h2>
        <!-- Lien de retour -->
        <p class="lien"><a href="previsions.php?lang=<?= $lang ?>&amp;<?php echo $styleParam; ?>"><?= t('return_map', $lang) ?></a></p>

        <!-- Sélection du département -->
        <h3 id="departement"><?= t('choose_department', $lang) ?></h3>
        <ul class="list">
            <?php
            // Trier les départements par nom
            $departements = $data[$selected_region];
            uasort($departements, function($a, $b) {
                return strcmp($a['nom'], $b['nom']);
            });
            foreach ($departements as $dep_code => $dep_data): ?>
                <li>
                    <a href="regions.php?lang=<?= $lang ?>&amp;region=<?php echo urlencode($selected_region); ?>&amp;departement=<?php echo urlencode($dep_code); ?>&amp;<?php echo $styleParam; ?>#ville">
                        <?php echo htmlspecialchars($dep_data['nom']) . " ($dep_code)"; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Afficher les villes si un département est sélectionné -->
        <?php if ($selected_departement && isset($data[$selected_region][$selected_departement])): ?>
            <h3 id="ville"><?= t('cities_in_department', $lang) ?><?php echo htmlspecialchars($data[$selected_region][$selected_departement]['nom']) . " ($selected_departement)"; ?> :</h3>
            <ul class="list">
                <?php 
                // Trier les villes par nom
                $villes = $data[$selected_region][$selected_departement]['villes'];
                usort($villes, function($a, $b):int {
                    return strcmp($a['nom'], $b['nom']);
                });
                $villes_affichees = []; // pour stocker les noms déjà affichés

                foreach ($villes as $ville): 
                    if (in_array($ville['nom'], $villes_affichees)) continue;
                    $villes_affichees[] = $ville['nom'];
                    ?>
                    <li>
                        <!-- Lien vers la page météo avec tous les paramètres nécessaires -->
                        <a href="meteo.php?lang=<?= $lang ?>&amp;region=<?php echo urlencode($selected_region); ?>&amp;departement=<?php echo urlencode($selected_departement); ?>&amp;ville=<?php echo urlencode($ville['nom']); ?>&amp;lat=<?php echo urlencode($ville['lat']); ?>&amp;lon=<?php echo urlencode($ville['lon']); ?>&amp;<?php echo $styleParam; ?>">
                            <?php echo htmlspecialchars($ville['nom']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
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