<?php
$title = "Statistiques des consultations";
require "./include/header.inc.php";

$styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');

// Lire les données du CSV
$consultations = [];
if (file_exists('consultations.csv')) {
    $file = fopen('consultations.csv', 'r');//ouvrir le fichier avec droit de lecture
    fgetcsv($file); // Ignorer l'en-tête
    
    while (($line = fgetcsv($file)) !== false) {
        $ville = $line[1]; // La ville est en 2ème colonne
        if (!empty($ville)) {
            $consultations[$ville] = ($consultations[$ville] ?? 0) + 1;
        }
    }
    fclose($file);
    
    // Trier par nombre de consultations (ordre décroissant)
    arsort($consultations);
    $top_villes = array_slice($consultations, 0, 10); // Top 10
}

?>

<main>
    <section>
    <h1>Statistiques des consultations</h1>
    
    <?php if (!empty($top_villes)): ?>
        <div class="histogramme">
            <h2>Top 10 des villes consultées</h2>
            
            <?php 
            $max_visites = max($top_villes);
            foreach ($top_villes as $ville => $visites): 
                $width = ($visites / $max_visites) * 100;
            ?>
                <div class="barre-container">
                    <span class="ville"><?= htmlspecialchars($ville) ?></span>
                    <div class="barre" style="width: <?= $width ?>%">
                        <span class="compteur"><?= $visites ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune donnée de consultation disponible.</p>
    <?php endif; ?>
    </section>
</main>


<?php require "./include/footer.inc.php"; ?>