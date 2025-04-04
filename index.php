<?php
    $title="Accueil - Météo&amp;Climat";
    require "./include/header.inc.php";
    // Vérifier si le paramètre 'style' existe dans l'URL, sinon définir 'style' à 'default'
    $styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');
include_once"./include/functions.inc.php";
incrementerCompteur();

?>

<main>

    <section>
        <h1>Bienvenue sur Météo&amp;Climat !</h1>

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
    <h2>Consultez dès maintenant📝</h2>
    <a href="./previsions.php">La Météo de votre région 🌤️</a>
    <a href="./tech.php">L'image du jour de la NASA 🔭</a>
    <a href="./statistiques.php">L'historique des villes consultées 📊</a>
</section>

    <!-- Bouton retour en haut -->
  <a href="#" class="back-to-top">↑</a>
</main>

<?php
    require "./include/footer.inc.php";
?>