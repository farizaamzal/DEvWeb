<?php

/**
 * @file planDuSite.php
 * @brief Page affichant le plan du site avec des liens vers les principales sections
 * @author Fariza Amzal , Nadjib Moussaoui 
 * @version 1.0
 * @date Avril 2025
 * @details Fournit une navigation centralisée vers les pages index.php, previsions.php, tech.php, statistiques.php, et planDuSite.php. Gère les thèmes clair/sombre et la langue via cookies.
 * @see functions.inc.php pour les fonctions utilitaires
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
 * @var string $title Titre traduit pour la page du plan du site
 */
// Définit le titre de la page, traduit en fonction de la langue
$title = t('title_sitemap', $lang);


/**
 * @brief Inclut l'en-tête avec menu et styles
 * @details Charge header.inc.php pour le menu traduit, les styles CSS, et les cookies de langue et thème.
 */
// Inclut le fichier d'en-tête (menu traduit, styles, cookies lang/theme)
require "./include/header.inc.php";


/**
 * @brief Charge les fonctions utilitaires
 * @details Inclut functions.inc.php pour d'éventuelles fonctions communes, bien que non utilisées directement ici.
 */
require "./include/functions.inc.php";


/**
 * @brief Gère le thème (clair/sombre)
 * @var string $style Nom du fichier CSS à utiliser ('styles.css' ou 'styleNight.css')
 * @var string $styleParam Paramètre de style pour l'URL, basé sur GET
 * @details Sélectionne le fichier CSS en fonction du paramètre 'style' dans l'URL.
 */
// Vérifier les paramètres 'style' et 'lang' dans l'URL
$style = isset($_GET['style']) && $_GET['style'] == 'alternatif' ? 'styleNight.css' : 'styles.css';

// Conserver les paramètres dans l'URL
$styleParam = "style=" . ($_GET['style'] ?? 'normal');
?>

<main style="max-width: 900px; margin: 0 auto; padding: 40px 20px; font-family: 'Segoe UI', sans-serif; color: #333;">
    <!-- Titre traduit -->
    <h1 style="text-align: center; font-size: 2.8em; color: #2c6e91; margin-bottom: 10px;">🗺️ <?= t('sitemap_heading', $lang) ?></h1>
    <!-- Description traduite -->
    <p style="text-align: center; font-size: 1.1em; color: #555; margin-bottom: 40px;">
        <?= t('sitemap_description', $lang) ?>
    </p>

    <section style="text-align: center; margin-bottom: 30px;">
        <!-- Sous-titre traduit -->
        <h2 style="font-size: 1.8em; color: #3a3a3a; margin-bottom: 20px;">🔗 <?= t('nav_title', $lang) ?></h2>
        <nav>
            <ul style="list-style-type: none; padding: 0; display: flex; flex-direction: column; gap: 15px; font-size: 1.2em;">
                <li>
                    <a href="./index.php?lang=<?= $lang ?>&amp;style=<?= $_GET['style'] ?? 'normal' ?>" style="color: #007acc; text-decoration: none;">
                        🏠 <?= t('link_home', $lang) ?>
                    </a>
                </li>
                <li>
                    <a href="./previsions.php?lang=<?= $lang ?>&amp;style=<?= $_GET['style'] ?? 'normal' ?>" style="color: #007acc; text-decoration: none;">
                        ☁️ <?= t('link_forecast', $lang) ?>
                    </a>
                </li>
                <li>
                    <a href="./tech.php?lang=<?= $lang ?>&amp;style=<?= $_GET['style'] ?? 'normal' ?>" style="color: #007acc; text-decoration: none;">
                        👨‍💻 <?= t('link_tech', $lang) ?>
                    </a>
                </li>
                <li>
                    <a href="./statistiques.php?lang=<?= $lang ?>&amp;style=<?= $_GET['style'] ?? 'normal' ?>" style="color: #007acc; text-decoration: none;">
                        📊 <?= t('link_stats', $lang) ?>
                    </a>
                </li>
                <li>
                    <a href="./planDuSite.php?lang=<?= $lang ?>&amp;style=<?= $_GET['style'] ?? 'normal' ?>" style="color: #007acc; text-decoration: none; font-weight: bold;">
                        🗺️ <?= t('link_sitemap', $lang) ?>
                    </a>
                </li>
            </ul>
        </nav>
    </section>
</main>

<?php
/**
 * @brief Inclut le pied de page
 * @details Charge footer.inc.php pour les liens et informations finales de la page.
 */
require "./include/footer.inc.php";
?>