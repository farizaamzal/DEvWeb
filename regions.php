<?php
$title = "Régions ET Départements";
$description = "régions et départements de France";

require "./include/header.inc.php";
require "./include/functions.inc.php";

// Définir $selected_region et $styleParam
$selected_region = $_GET['region'] ?? null;
$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : 'style=default';

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
?>

<main>
    <h1>Sélection du département et de la ville</h1>
    <section>
        <h2>Région sélectionnée : <?php echo htmlspecialchars($selected_region); ?></h2>
        <p><a href="previsions.php?<?php echo $styleParam; ?>">Retour à la carte</a></p>

        <!-- Sélection du département -->
        <h3>Choisissez un département :</h3>
        <ul>
            <?php foreach ($data[$selected_region] as $dep_code => $dep_data): ?>
                <li>
                    <a href="regions.php?region=<?php echo urlencode($selected_region); ?>&departement=<?php echo urlencode($dep_code); ?>&<?php echo $styleParam; ?>">
                        <?php echo htmlspecialchars($dep_data['nom']) . " ($dep_code)"; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Afficher les villes si un département est sélectionné -->
        <?php if ($selected_departement && isset($data[$selected_region][$selected_departement])): ?>
            <h3>Villes dans le département <?php echo htmlspecialchars($data[$selected_region][$selected_departement]['nom']) . " ($selected_departement)"; ?> :</h3>
            <ul>
                <?php foreach ($data[$selected_region][$selected_departement]['villes'] as $ville): ?>
                    <li>
                        <a href="meteo.php?region=<?php echo urlencode($selected_region); ?>&departement=<?php echo urlencode($selected_departement); ?>&ville=<?php echo urlencode($ville['nom']); ?>&lat=<?php echo urlencode($ville['lat']); ?>&lon=<?php echo urlencode($ville['lon']); ?>&<?php echo $styleParam; ?>">
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