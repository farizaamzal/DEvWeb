<?php

/**
 * @file statistiques.php
 * @brief Page affichant les statistiques des villes les plus consultées pour les prévisions météo
 * @author Fariza Amzal , Nadjib Moussaoui 
 * @version 1.0
 * @date Avril 2025
 * @details Lit les données de consultations.csv pour générer un histogramme des 10 villes les plus consultées. Gère les thèmes clair/sombre et la langue via cookies.
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
 * @var string $title Titre traduit pour la page de statistiques
 */
// Définit le titre de la page, traduit en fonction de la langue
$title = t('title_stats', $lang);

/**
 * @brief Inclut l'en-tête avec menu et styles
 * @details Charge header.inc.php pour le menu traduit, les styles CSS, et les cookies de langue et thème.
 */
// Inclut le fichier d'en-tête (menu traduit, styles, cookies lang/theme)
require "./include/header.inc.php";


/**
 * @brief Gère le paramètre de style (thème)
 * @var string $styleParam Paramètre de style pour l'URL, basé sur GET ou cookie
 * @details Définit le thème clair ou sombre en fonction des paramètres ou du cookie.
 */
// Gère le paramètre de style (thème)
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');



/**
 * @brief Lit et traite les données du fichier CSV
 * @details Charge consultations.csv pour compter les consultations par ville et sélectionner les 10 plus consultées.
 * @var array $consultations Tableau associatif ville => nombre de consultations
 * @var array $top_villes Top 10 des villes les plus consultées
 */
// Lire les données du CSV
$consultations = [];
if (file_exists('consultations.csv')) {
    $file = fopen('consultations.csv', 'r'); // ouvrir le fichier avec droit de lecture
    fgetcsv($file); // Ignorer l'en-tête
    
    while (($line = fgetcsv($file)) !== false) {
        $ville = $line[1]; // La ville est en 2ème colonne
        if (!empty($ville)) {
            $consultations[$ville] = ($consultations[$ville] ?? 0) + 1;
        }
    }
    fclose($file);
    
    // Trier par nombre de consultations (ordre décroissant)
    arsort($consultations);
    $top_villes = array_slice($consultations, 0, 10); // Top 10
}
?>

<main>
    <section>
        <!-- Titre traduit -->
        <h1><?= t('stats_title', $lang) ?></h1>
        
        <?php if (!empty($top_villes)): ?>
            <div class="histogramme">
                <!-- Sous-titre traduit -->
                <h2><?= t('top_cities', $lang) ?></h2>
                
                <?php 
                $max_visites = max($top_villes);
                foreach ($top_villes as $ville => $visites): 
                    $width = ($visites / $max_visites) * 100;
                ?>
                    <div class="barre-container">
                        <span class="ville"><?= htmlspecialchars($ville) ?></span>
                        <div class="barre" style="width: <?= $width ?>%">
                            <span class="compteur"><?= $visites ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Message d'absence de données traduit -->
            <p><?= t('no_data', $lang) ?></p>
        <?php endif; ?>
    </section>
</main>

<?php 

/**
 * @brief Inclut le pied de page
 * @details Charge footer.inc.php pour les liens et informations finales de la page.
 */
require "./include/footer.inc.php";
 ?>