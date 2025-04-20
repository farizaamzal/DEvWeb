<?php

/**
 * @file header.inc.php
 * @brief Fichier d'en-tête commun pour toutes les pages du site Météo&Climat
 * @author Fariza Amzal <amzalfariza44@gmail.com>, Nadjib Moussaoui <nadjib.moussaoui15@gmail.com>
 * @version 1.0
 * @date Avril 2025
 * @details Configure la langue et le thème (clair/sombre) via cookies et paramètres GET. Génère l'en-tête HTML avec le logo, le menu de navigation, et les boutons de bascule pour la langue et le thème. Inclut les traductions et les styles CSS.
 * @see lang.inc.php pour la gestion des traductions
 */

/**
 * @brief Charge les traductions
 * @details Inclut lang.inc.php pour définir la fonction t() et la variable $lang.
 */
// Inclut le fichier de traductions pour toutes les pages
require_once "./include/lang.inc.php";

/**
 * @brief Gère la langue
 * @var string $lang Langue courante ('fr' ou 'en')
 * @details Définit la langue via GET ou cookie, avec français par défaut. Stocke la langue dans un cookie sécurisé.
 */
// Gère la langue via cookie ou URL (français par défaut)
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + 90*24*3600, '/', '', false, true); 
} else {
    $lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr';
}


/**
 * @brief Définit le chemin du cookie
 * @var string $cookie_path Chemin pour les cookies (racine du site)
 * @details Permet d'appliquer les cookies à toutes les pages sauf sous-dossiers spécifiques.
 */
// Définition du chemin du cookie 
$cookie_path = '/';   // Permet d'utiliser le cookie sur tout le site sauf dans un sous-dossier spécifique


/**
 * @brief Gère le thème (clair/sombre)
 * @var string $style Fichier CSS à utiliser ('styles.css' ou 'styleNight.css')
 * @details Définit le thème via GET ou cookie, avec 'styles.css' par défaut. Stocke le thème dans un cookie.
 */
// Vérifier si un style est passé en paramètre d'URL
if (isset($_GET['style'])) {
    if ($_GET['style'] === 'alternatif') { //si mode sombre
        $style = 'styleNight.css';
        setcookie('theme', 'alternatif', time() + 90*24*3600, $cookie_path); // Cookie pour 90 jours
    } else {
        $style = 'styles.css';
        setcookie('theme', 'normal', time() + 90*24*3600, $cookie_path);
    }
} elseif (isset($_COOKIE['theme'])) {
    if (!in_array($_COOKIE['theme'], ['normal', 'alternatif'])) { //si la valeur du cookie n'est pas correcte
        setcookie('theme', '', time() - 3600, $cookie_path); // Supprime le cookie
        $style = 'styles.css'; // Valeur par défaut
    } else {
        // Valeur correcte, on applique le style correspondant
        $style = ($_COOKIE['theme'] === 'alternatif') ? 'styleNight.css' : 'styles.css';
    }
} else {
    // Aucun paramètre, aucun cookie : style par défaut
    $style = 'styles.css';
}


/**
 * @brief Définit le paramètre de style pour les URL
 * @var string $styleParam Paramètre de style pour les liens ('style=normal' ou 'style=alternatif')
 * @details Utilisé pour maintenir le thème dans les liens de navigation.
 */
// Vérifie si un paramètre 'style' est passé dans l'URL. Si oui, utilise cette valeur. 
// Sinon, vérifie si un cookie 'theme' est défini, et utilise sa valeur. 
// Si aucune des deux conditions n'est remplie, utilise 'style=normal' (mode jour par défaut).
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>"> <!-- Langue dynamique pour accessibilité -->
<head>
    <meta charset="UTF-8"/>
    <meta name="author" content="Fariza et Nadjib"/>
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="description" content="La page principale du site meteo"/>
    <link rel="icon" type="image/png" href="./images/weather.png"/>
    <link rel="stylesheet" href="<?= $style ?>" />
    <style>
        /* The switch - the box around the slider */
.switch {
  font-size: 17px;
  position: relative;
  display: inline-block;
  width: 3.5em;
  height: 2em;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  --background: #28096b;
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: var(--background);
  transition: .5s;
  border-radius: 30px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 1.4em;
  width: 1.4em;
  border-radius: 50%;
  left: 10%;
  bottom: 15%;
  box-shadow: inset 8px -4px 0px 0px #fff000;
  background: var(--background);
  transition: .5s;
}

input:checked + .slider {
  background-color: #522ba7;
}

input:checked + .slider:before {
  transform: translateX(100%);
  box-shadow: inset 15px -4px 0px 15px #fff000;
}

.light-mode {
  background: white;
  color: black;
}

.dark-mode {
  background: black;
  color: white;
}

/* Style du bouton */
.back-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: rgb(187, 189, 68); /* Bleu */
    color: white;
    padding: 10px 15px;
    border-radius: 50%;
    font-size: 20px;
    text-decoration: none;
    text-align: center;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
}

/* Effet au survol */
.back-to-top:hover {
    background-color: #0056b3; /* Bleu plus foncé */
    transform: scale(1.1);
}

/* Style pour le bouton de langue */
.lang-switch {
    display: inline-block;
    margin-left: 20px;
}
.lang-button {
    background: #007ACC;
    color: white;
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9em;
}
.lang-button:hover {
    background: #005F99;
}
    </style>
</head>
<body>
    <header>
        <!-- Logo -->
        <a href="index.php?lang=<?= $lang ?>&amp;<?= $styleParam ?>"><img src="./images/logo1.png" alt="meteo" style="width: 100px; height: auto;" /></a>

        <!-- Navigation -->
        <nav id="navIndex">
            <ul>
                <li><a href="./index.php?lang=<?= $lang ?>&amp;<?= $styleParam ?>"><?= t('menu_home', $lang) ?></a></li>
                <li><a href="./previsions.php?lang=<?= $lang ?>&amp;<?= $styleParam ?>"><?= t('menu_forecast', $lang) ?></a></li>
                <li><a href="./tech.php?lang=<?= $lang ?>&amp;<?= $styleParam ?>"><?= t('menu_tech', $lang) ?></a></li>
                <li><a href="./statistiques.php?lang=<?= $lang ?>&amp;<?= $styleParam ?>"><?= t('menu_stats', $lang) ?></a></li>
            </ul>
        </nav>

        <div class="theme-switch">
    <label class="switch">
        <!-- Input checkbox pour changer le thème -->
        <input type="checkbox" id="theme-toggle"<?php echo isset($_GET['style']) && $_GET['style'] === 'alternatif' ? ' checked="checked"' : ''; ?>
               onclick="window.location.href=this.checked ? '<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?style=alternatif&amp;lang=<?php echo $lang; ?>' : '<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?style=normal&amp;lang=<?php echo $lang; ?>'" />
        <span class="slider"></span>
    </label>
</div>

        <!-- Bouton pour basculer la langue -->
        <div class="lang-switch">
            <?php if ($lang === 'fr'): ?>
                <a href="?lang=en&amp;<?= $styleParam ?>" class="lang-button">🇬🇧 English</a>
            <?php else: ?>
                <a href="?lang=fr&amp;<?= $styleParam ?>" class="lang-button">🇫🇷 Français</a>
            <?php endif; ?>
        </div>
    </header>