<?php
// Vérifier les paramètres 'style' et 'lang' dans l'URL
$style = isset($_GET['style']) && $_GET['style'] == 'alternatif' ? 'styleNight.css' : 'styles.css';


// Conserver les paramètres dans l'URL
$styleParam = "style=" . ($_GET['style'] ?? 'normal');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="author" content="Fariza et Nadjib"/>
    <title> <?=$title?> </title>
    <link rel="icon" href="./images/favicon.png"/>
    <!-- Lien vers le style dynamique (mode jour/nuit) -->
    <link rel="stylesheet" href="<?=$style?>" />
    <style>
        header, footer {
            background-color: #4682B4; /* Bleu foncé */
            color: white;
            text-align: center;
            padding: 20px;
        }

        /* Style du bouton */
        .back-to-top {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background-color: #007bff; /* Bleu */
                color: white;
                padding: 10px 15px;
                border-radius: 50%;
                font-size: 20px;
                text-decoration: none;
                text-align: center;
                box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
                transition: background 0.3s, transform 0.2s;
            }

            /* Effet au survol */
            .back-to-top:hover {
                background-color: #0056b3; /* Bleu plus foncé */
                transform: scale(1.1);
            }
    </style>
</head>
<body>
    <header>
        <a href="index.php"><img src="./images/weather.png" alt="meteo"></a>
        
            <!-- Bouton pour ouvrir le menu -->
            <a href="#sidebar" id="menuButton">☰ Menu</a>
            <!-- Menu latéral -->
            <aside class="sidebar" id="sidebar">
                <a href="#close-btn" class="close-btn">✖</a>
                <ul>
                    <li><a href="./index.php?<?= $styleParam ?>">Accueil</a></li>
                    <li><a href="./tech.php?style=<?php echo $_GET['style'] ?? 'normal'; ?>">Page developpeur</a></li>

                </ul>
            </aside>
            <nav>
                <ul>
                    <li><a href="./index.php?style=<?php echo $_GET['style'] ?? 'normal'; ?>">Accueil</a></li>
                    <li><a href="./tech.php?style=<?php echo $_GET['style'] ?? 'normal'; ?>">Page developpeur</a></li>
                </ul>
            </nav>
            <a href="index.php?style=normal" style=" background: white; color: black; border-radius: 15px; text-decoration: none;">☀</a>
            <a href="index.php?style=alternatif" style=" background: black; color: white; border-radius: 15px; text-decoration: none;">🌙</a>
    </header>