<?php
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
            <div>mis à jour le 26 mars 2025</div>
            <div style="color: #f1f1f1;">
            <a href="./planDuSite.php?style=<?php echo $_GET['style'] ?? 'normal'; ?>" style="color: inherit; text-decoration: none;">Plan du site</a>
            </div>
            <div>
                <?php afficherCompteur(); ?>
            </div>
    </footer>
</body>
</html>