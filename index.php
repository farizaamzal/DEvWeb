<?php
    $title="Accueil - Météo&Climat";
    require "./include/header.inc.php";
    // Vérifier si le paramètre 'style' existe dans l'URL, sinon définir 'style' à 'default'
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : 'style=default';
include_once"./include/functions.inc.php";
incrementerCompteur();

$data = construire_regions_departements_villes("v_region_2024.csv", "v_departement_2024.csv", "cities.csv");

$selected_region = $_GET['region'] ?? null;
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : 'style=default';

// Associer chaque région à ses coordonnées (avec image-map.net)
$region_coords = [
    "Île-de-France" => "340,180,420,260",
    "Hauts-de-France" => "340,40,420,180",
    "Grand Est" => "420,80,520,220",
    "Bourgogne-Franche-Comté" => "420,220,520,340",
    "Auvergne-Rhône-Alpes" => "420,340,520,460",
    "Provence-Alpes-Côte d'Azur" => "420,460,520,580",
    "Corse" => "580,540,645,600",
    "Occitanie" => "220,460,340,580",
    "Nouvelle-Aquitaine" => "160,340,260,460",
    "Pays de la Loire" => "160,220,260,340",
    "Bretagne" => "60,120,160,220",
    "Normandie" => "160,120,260,220",
    "Centre-Val de Loire" => "260,260,360,340",
];
?>

<main>

    <section>
        <h1>Bienvenue sur Météo&Climat !</h1>

         <!-- From Uiverse.io by zanina-yassine --> 
<div class="container">
  <div class="cloud front">
    <span class="left-front"></span>
    <span class="right-front"></span>
  </div>
  <span class="sun sunshine"></span>
  <span class="sun"></span>
  <div class="cloud back">
    <span class="left-back"></span>
    <span class="right-back"></span>
  </div>
</div>

        <p>Découvrez en un coup d'œil la météo qu’il fait chez vous et partout en France !</p>
        <p>☀️ Soleil, 🌧️ pluie, ⛈️ orages… Soyez toujours prêt grâce à nos mises à jour fiables et détaillées.</p>
        <p>📍Entrez votre ville et obtenez immédiatement les prévisions pour votre localisation.</p>
        <p><strong> restez informé, restez préparé !</strong></p>
</section>

<section class="links-section">
    <a href="./previsions.php">La Météo</a>
    <a href="./tech.php">Consultez la page développeur</a>
    <a href="./statistiques.php">Statistiques et historiques</a>
</section>

    <!-- Bouton retour en haut -->
  <a href="#" class="back-to-top">↑</a>
</main>

<?php
    require "./include/footer.inc.php";
?>