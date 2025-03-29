<?php
    $title="Accueil - Prévisions Météo ET Climat";
    require "./include/header.inc.php";
    // Vérifier si le paramètre 'style' existe dans l'URL, sinon définir 'style' à 'default'
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : 'style=default';
include_once"./include/functions.inc.php";
incrementerCompteur();

$data = construire_regions_departements_villes("v_region_2024.csv", "v_departement_2024.csv", "cities.csv");

$selected_region = $_GET['region'] ?? null;
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : 'style=default';

// Associer chaque région à ses coordonnées (à ajuster avec image-map.net)
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
        <h1>Bienvenue sur Prévisions Météo ET Climat !</h1>
        <p>Découvrez en un coup d'œil la météo qu’il fait chez vous et partout ailleurs !</p>
        <p>☀️ Soleil, 🌧️ pluie, ⛈️ orages… Soyez toujours prêt grâce à nos mises à jour fiables et détaillées.</p>
        <p>📍Entrez votre ville et obtenez immédiatement les prévisions pour votre localisation.
        </p>
        <p>
        <strong> restez informé, restez préparé !</strong></p>


            <div class="section-banner">
          <div id="star-1">
            <div class="curved-corner-star">
              <div id="curved-corner-bottomright"></div>
              <div id="curved-corner-bottomleft"></div>
            </div>
            <div class="curved-corner-star">
              <div id="curved-corner-topright"></div>
              <div id="curved-corner-topleft"></div>
            </div>
          </div>

          <div id="star-2">
            <div class="curved-corner-star">
              <div id="curved-corner-bottomright"></div>
              <div id="curved-corner-bottomleft"></div>
            </div>
            <div class="curved-corner-star">
              <div id="curved-corner-topright"></div>
              <div id="curved-corner-topleft"></div>
            </div>
          </div>

          <div id="star-3">
            <div class="curved-corner-star">
              <div id="curved-corner-bottomright"></div>
              <div id="curved-corner-bottomleft"></div>
            </div>
            <div class="curved-corner-star">
              <div id="curved-corner-topright"></div>
              <div id="curved-corner-topleft"></div>
            </div>
          </div>

          <div id="star-4">
            <div class="curved-corner-star">
              <div id="curved-corner-bottomright"></div>
              <div id="curved-corner-bottomleft"></div>
            </div>
            <div class="curved-corner-star">
              <div id="curved-corner-topright"></div>
              <div id="curved-corner-topleft"></div>
            </div>
          </div>

          <div id="star-5">
            <div class="curved-corner-star">
              <div id="curved-corner-bottomright"></div>
              <div id="curved-corner-bottomleft"></div>
            </div>
            <div class="curved-corner-star">
              <div id="curved-corner-topright"></div>
              <div id="curved-corner-topleft"></div>
            </div>
          </div>

          <div id="star-6">
            <div class="curved-corner-star">
              <div id="curved-corner-bottomright"></div>
              <div id="curved-corner-bottomleft"></div>
            </div>
            <div class="curved-corner-star">
              <div id="curved-corner-topright"></div>
              <div id="curved-corner-topleft"></div>
            </div>
          </div>

          <div id="star-7">
            <div class="curved-corner-star">
              <div id="curved-corner-bottomright"></div>
              <div id="curved-corner-bottomleft"></div>
            </div>
            <div class="curved-corner-star">
              <div id="curved-corner-topright"></div>
              <div id="curved-corner-topleft"></div>
            </div>
          </div>
        </div>

        </section>

        <section>

    <h1>Test des données</h1>
    <p>Sélectionnez une région sur la carte :</p>

    <!-- Conteneur centré -->
    <figure class="map-container">
    <img src="./images/Carte_France.png" alt="Carte de France" usemap="#image-map">
    <figcaption>Carte de la France avec une image cliquable</figcaption>
    </figure>

    <map name="image-map">
        <?php foreach ($data as $region => $deps): ?>
            <?php
            $coords = $region_coords[$region] ?? "0,0,0,0"; // Coordonnées par défaut
            ?>
            <area target="" alt="<?php echo htmlspecialchars($region); ?>" 
                  title="<?php echo htmlspecialchars($region); ?>" 
                  href="./regions.php?region=<?php echo urlencode($region); ?>&<?php echo $styleParam; ?>" 
                  coords="<?php echo $coords; ?>" shape="rect">
        <?php endforeach; ?>
    </map>
</section>
    <!-- Bouton retour en haut -->
  <a href="#" class="back-to-top">↑</a>
</main>

<?php
    require "./include/footer.inc.php";
?>
