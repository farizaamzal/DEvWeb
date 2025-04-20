<?php

/**
 * @file footer.inc.php
 * @author Fariza Amzal <amzalfariza44@gmail.com>, Nadjib Moussaoui <nadjib.moussaoui15@gmail.com>
 * @version 1.0
 * @date Avril 2025
 * @details Configure la langue et le thème (clair/sombre) via cookies et paramètres GET. Génère l'en-tête HTML avec le logo, le menu de navigation, et les boutons de bascule pour la langue et le thème. Inclut les traductions et les styles CSS.
 * @see lang.inc.php pour la gestion des traductions
 */

/** 
 * @file footer.inc.php
 * @brief Pied de page du site avec compteur de visites et liens utiles.
 */


// Vérifier les paramètres 'style' et 'lang' dans l'URL
$style = isset($_GET['style']) && $_GET['style'] == 'alternatif' ? 'styleNight.css' : 'styles.css';


// Conserver les paramètres dans l'URL
$styleParam = "style=" . ($_GET['style'] ?? 'normal');
include_once './include/functions.inc.php';
?>

<footer>
        <div><a href="./tech.php?style=<?php echo $_GET['style'] ?? 'normal'; ?>">Page developpeur</a></div>
        <a href="https://www.cyu.fr/"> <img src="./images/logo.png" alt="logo cy " width="100"/></a>
        <div>&#169; Fariza AMZAL, Nadjib MOUSSAOUI</div>
            <div style="color: #f1f1f1;">
            <a href="./planDuSite.php?style=<?php echo $_GET['style'] ?? 'normal'; ?>">Plan du site</a>
            </div>
            <div>
                <?php afficherCompteur(); ?>
            </div>
    </footer>
</body>
</html>