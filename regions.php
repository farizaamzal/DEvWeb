<?php
$title = "Régions ET Départements";
$description = "régions et départements de France";

require "./include/header.inc.php";
require "./include/functions.inc.php";

// Définir $selected_region et $styleParam
$selected_region = $_GET['region'] ?? null;
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');

// Charger les données
$data = construire_regions_departements_villes("v_region_2024.csv", "v_departement_2024.csv", "cities.csv");

// Vérifier si la région existe dans les données
if (!$selected_region || !isset($data[$selected_region])) {
    // Rediriger vers index.php si la région est invalide
    header("Location: index.php?" . $styleParam);
    exit;
}

// Récupérer le département sélectionné (s'il y en a un)
$selected_departement = $_GET['departement'] ?? null;

$tab = organizeData('v_region_2024.csv', 'v_departement_2024.csv', 'cities.csv');
$regions = $tab['regions'];


// Détermine dynamiquement une ancre HTML (#departement ou #ville)
// pour revenir automatiquement à la bonne section après soumission du formulaire
$ancre = '';
if (!empty($selectedRegion) && empty($selectedDepartement)) {
    // Si une région est sélectionnée mais pas de département → revenir à la section départements
    $ancre = '#departement';
} elseif (!empty($selectedDepartement) && empty($selectedVille)) {
    // Si un département est sélectionné mais pas de ville → revenir à la section villes
    $ancre = '#ville';
}

?>

<main>
    <h1>Sélection du département et de la ville</h1>
    <section>
        <h2>Région sélectionnée : <?php echo htmlspecialchars($selected_region); ?></h2>
        <p><a href="previsions.php?<?php echo $styleParam; ?>">Retour à la carte</a></p>

        <!-- Sélection du département -->
        <h3 id="departement">Choisissez un département :</h3>
        <ul class="list">
            <?php
            // Trier les départements par nom
            $departements = $data[$selected_region];
            uasort($departements, function($a, $b) {
            return strcmp($a['nom'], $b['nom']);
            });
            foreach ($departements as $dep_code => $dep_data): ?>
                <li>
                    <a href="regions.php?region=<?php echo urlencode($selected_region); ?>&amp;departement=<?php echo urlencode($dep_code); ?>&amp;<?php echo $styleParam; ?>#ville">
                        <?php echo htmlspecialchars($dep_data['nom']) . " ($dep_code)"; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Afficher les villes si un département est sélectionné -->
        <?php if ($selected_departement && isset($data[$selected_region][$selected_departement])): ?>
            <h3 id="ville">Villes dans le département <?php echo htmlspecialchars($data[$selected_region][$selected_departement]['nom']) . " ($selected_departement)"; ?> :</h3>
            <ul class="list">
                <?php 
                // Trier les villes par nom
                $villes = $data[$selected_region][$selected_departement]['villes'];
                usort($villes, function($a, $b):int {
                    return strcmp($a['nom'], $b['nom']);
                });
                $villes_affichees = [];//pour stocker les noms déja affichés

                foreach ($villes as $ville): 
                    if (in_array($ville['nom'], $villes_affichees)) continue;
                        $villes_affichees[] = $ville['nom'];
                    ?>
                    <li>
                    <!--Lien vers la page météo avec tous les paramètres nécessaires (région, département, ville, coordonnées GPS, et style)-->

                        <a href="meteo.php?region=<?php echo urlencode($selected_region); ?>&amp;departement=<?php echo urlencode($selected_departement); ?>&amp;ville=<?php echo urlencode($ville['nom']); ?>&amp;lat=<?php echo urlencode($ville['lat']); ?>&amp;lon=<?php echo urlencode($ville['lon']); ?>&amp;<?php echo $styleParam; ?>">
                            <?php echo htmlspecialchars($ville['nom']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
    <!-- Bouton retour en haut -->
    <a href="#" class="back-to-top">↑</a>
</main>

<?php
require "./include/footer.inc.php";
?>