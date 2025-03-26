<?php
    $title="Accueil - Prévisions Météo ET Climat";
    require "./include/header.inc.php";
    // Vérifier si le paramètre 'style' existe dans l'URL, sinon définir 'style' à 'default'
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : 'style=default';
?>

<main>

    <section>
        <h1>Bienvenue sur Prévisions Météo ET Climat !</h1>
        <p>Découvrez en un coup d'œil la météo qu’il fait chez vous et partout ailleurs !</p>
        <p>☀️ Soleil, 🌧️ pluie, ⛈️ orages… Soyez toujours prêt grâce à nos mises à jour fiables et détaillées.</p>
        <p>📍Entrez votre ville et obtenez immédiatement les prévisions pour votre localisation.
        </p>
        <p>
        <strong> restez informé, restez préparé !</strong></p>
    </section>
    <!-- Bouton retour en haut -->
  <a href="#" class="back-to-top">↑</a>
</main>

<?php
    require "./include/footer.inc.php";
?>
