<?php
// Définition du chemin du cookie 
$cookie_path = '/';   // Permet d'utiliser le cookie sur tout le site sauf dans un sous-dossier spécifique

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

// Vérifie si un paramètre 'style' est passé dans l'URL. Si oui, utilise cette valeur. 
// Sinon, vérifie si un cookie 'theme' est défini, et utilise sa valeur. 
// Si aucune des deux conditions n'est remplie, utilise 'style=normal' (mode jour par défaut).
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="author" content="Fariza et Nadjib"/>
    <title> <?=$title?> </title>
    <meta name="description" content="La page principale du site meteo"/>
    <link rel="icon" type="image/png" href="../images/weather.png"/>
    <link rel="stylesheet" href="<?=$style?>" />
    <style>
    
        header {
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgb(112, 182, 239);
            color: white;
            padding: 10px;
            text-align: center;
            margin: 10px; /* Ajoute un espace en haut, à gauche et à droite */
        }

        nav {
            flex-grow: 1;
            text-align: center;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            padding: 5px 10px;
            transition: color 0.3s, background 0.3s, border-radius 0.3s;
        }

        /* Effet hover sur les liens */
        nav ul li a:hover {
            background: white;
            color: rgb(112, 182, 239);
            border-radius: 10px;
        }

        .theme-switch {
            display: flex;
            gap: 10px;
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
        

        
    </style>
</head>
<body>
    <header>
        <!-- Logo -->
        <a href="index.php"><img src="./images/logo1.png" alt="meteo" style="width: 100px; height: auto;" /></a>

        <!-- Navigation -->
        <nav id="navIndex">
            <ul>
                <li><a href="./index.php?<?=$styleParam?>">Accueil</a></li>
                <li><a href="./previsions.php?<?=$styleParam?>">Prévisions</a></li>
                <li><a href="./tech.php?<?=$styleParam?>">Page développeur</a></li>
                <li><a href="./statistiques.php?<?=$styleParam?>">Statistiques</a></li>
            </ul>
        </nav>

    

        <div class="theme-switch">
    <label class="switch">
        <!-- Input checkbox pour changer le thème -->
        <input type="checkbox" id="theme-toggle" 
               <?php echo isset($_GET['style']) && $_GET['style'] == 'alternatif' ? 'checked' : ''; ?>
               onclick="window.location.href=this.checked ? 'index.php?style=alternatif' : 'index.php?style=normal'"/>
        <span class="slider"></span>
    </label>
</div>



    </header>
