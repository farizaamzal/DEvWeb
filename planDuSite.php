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

<main style="max-width: 900px; margin: 0 auto; padding: 40px 20px; font-family: 'Segoe UI', sans-serif; color: #333;">
    <h1 style="text-align: center; font-size: 2.8em; color: #2c6e91; margin-bottom: 10px;">🗺️ Plan du Site</h1>
    <p style="text-align: center; font-size: 1.1em; color: #555; margin-bottom: 40px;">
        Retrouvez en un clin d’œil toutes les pages principales de notre site. Accédez rapidement à ce qui vous intéresse.
    </p>

    <section style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 1.8em; color: #3a3a3a; margin-bottom: 20px;">🔗 Navigation rapide</h2>
        <nav>
            <ul style="list-style-type: none; padding: 0; display: flex; flex-direction: column; gap: 15px; font-size: 1.2em;">
                <li>
                    <a href="./index.php?style=<?= $_GET['style'] ?? 'normal'; ?>" style="color: #007acc; text-decoration: none;">
                        🏠 Accueil – Explorez la météo et les images du jour
                    </a>
                </li>
                <li>
                    <a href="./prévisions.php?style=<?= $_GET['style'] ?? 'normal'; ?>" style="color: #007acc; text-decoration: none;">
                        ☁️ Prévisions – Consultez la météo de votre région
                    </a>
                </li>
                <li>
                    <a href="./tech.php?style=<?= $_GET['style'] ?? 'normal'; ?>" style="color: #007acc; text-decoration: none;">
                        👨‍💻 Espace Développeur – Découverte technique du projet
                    </a>
                </li>
                <li>
                    <a href="./statistiques.php?style=<?= $_GET['style'] ?? 'normal'; ?>" style="color: #007acc; text-decoration: none;">
                        📊 Historique – Voir les villes consultées
                    </a>
                </li>
                <li>
                    <a href="./planDuSite.php?style=<?= $_GET['style'] ?? 'normal'; ?>" style="color: #007acc; text-decoration: none; font-weight: bold;">
                        🗺️ Plan du site – Vous êtes ici !
                    </a>
                </li>
            </ul>
        </nav>
    </section>
</main>


<?php
require "./include/footer.inc.php";
?>
