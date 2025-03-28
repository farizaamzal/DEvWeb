<?php

// Vérifier les paramètres 'style' et 'lang' dans l'URL
$style = isset($_GET['style']) && $_GET['style'] == 'alternatif' ? 'styleNight.css' : 'styles.css';


// Conserver les paramètres dans l'URL
$styleParam = "style=" . ($_GET['style'] ?? 'normal');

$title = "Plan du site web";
$description = "Plan montrant la structure du site web";

require "./include/header.inc.php";
require"./include/functions.inc.php";

?>

<main style="max-width: 900px; margin: 0 auto; padding: 20px;">
    <h1 style="text-align: center; font-size: 2.5em; color: #4280aa;">Plan du Site</h1>

    <section style="text-align: center; margin-bottom: 30px;">
        <h2>Navigation</h2>
        <nav>
            <ul style="list-style-type: none; padding: 0; font-size: 1.2em;">
                <li><a href="./index.php?style=<?php echo $_GET['style'] ?? 'normal'; ?>" style="color: #0066cc; text-decoration: none;">Page d'Accueil</a></li>
                <li><a href="./tech.php?style=<?php echo $_GET['style'] ?? 'normal'; ?>" style="color: #0066cc; text-decoration: none;">Page developpeur</a></li>
                <li><a href="./planDuSite.php?style=<?php echo $_GET['style'] ?? 'normal'; ?>" style="color: #0066cc; text-decoration: none;">Plan du Site</a></li> <!-- Lien vers cette page -->
            </ul>
        </nav>
    </section>
</main>

<?php
require "./include/footer.inc.php";
?>
