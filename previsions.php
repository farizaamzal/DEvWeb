<?php
    $title="Prévisions - Météo&Climat";
    require "./include/header.inc.php";

    include_once"./include/functions.inc.php";

    $data = construire_regions_departements_villes("v_region_2024.csv", "v_departement_2024.csv", "cities.csv");

    $selected_region = $_GET['region'] ?? null;
    $styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : 'style=default';


    // Associer chaque région à ses coordonnées
    $region_coords = [
        "Île-de-France" => "129,53,144,63,144,72,133,82,116,71,116,61",
        "Hauts-de-France" => "122,14,130,10,145,20,150,30,145,54,140,50,125,40",
        "Grand Est" => "66,35,183,42,213,53,225,72,221,86,182,92,163,83,147,65",
        "Bourgogne-Franche-Comté" => "146,77,144,111,163,132,197,127,212,88,173,92",
        "Auvergne-Rhône-Alpes" => "137,117,163,133,185,130,217,141,189,189,155,171,131,173,132,129",
        "Provence-Alpes-Côte d'Azur" => "196,171,210,163,217,176,227,196,210,219,168,204,190,188",
        "Corse" => "247,203,247,239,235,235,231,217",
        "Occitanie" => "85,223,141,233,171,183,136,171,114,174,80,201",
        "Nouvelle-Aquitaine" => "86,109,111,130,134,130,125,168,102,188,75,223,55,208,59,180,64,155,65,140,75,112,81,109",
        "Pays de la Loire" => "70,74,87,71,100,85,93,95,84,105,71,112,72,131,48,116,47,99,66,88",
        "Bretagne" => "44,98,68,83,54,69,36,62,12,66,12,81",
        "Normandie" => "61,40,63,62,100,77,119,45,114,32,89,51",
        "Centre-Val de Loire" => "107,66,119,78,141,83,142,119,116,127,90,107,103,81,104,81",
    ];


// Charger et organiser les données
$tab = organizeData("v_region_2024.csv", "v_departement_2024.csv", "cities.csv");

// Extraire les données organisées
$regions = $tab['regions'];
$departements = $tab['departements'];
$villes = $tab['villes'];

 //Récupérer les valeurs sélectionnées (si le formulaire a été soumis)
$selectedRegion = isset($_GET['region']) ? $_GET['region'] : '';
$selectedDepartement = isset($_GET['departement']) ? $_GET['departement'] : '';
$selectedVille = isset($_GET['ville']) ? $_GET['ville'] : '';
        
    ?>
<main>
<section>

<h1>Prévisions Météo </h1>
<p>Sélectionnez une région sur la carte :</p>

<!-- Conteneur centré -->
<figure class="map-container">
<img src="./images/CarteDeFrance.jpg" alt="Carte de France" usemap="#image-map">
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
              coords="<?php echo $coords; ?>" shape="poly">
    <?php endforeach; ?>
</map>
</section>

<section>
        <h2>Consultez la météo</h2>
        <p>Entrez les informations ci-dessous pour obtenir les prévisions.</p>
    </section>

    <!-- Formulaire -->
    <form action="<?php echo !empty($selectedVille) ? 'meteo.php' : htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
    <!-- Conserver le paramètre de style -->
    <input type="hidden" name="style" value="<?php echo isset($_GET['style']) ? htmlspecialchars($_GET['style']) : 'default'; ?>">
    
    <label for="region">Région :</label>
    <select id="region" name="region" onchange="this.form.submit()">
        <option value="">Choisir une région</option>
        <?php foreach ($regions as $code => $region): ?>
            <option value="<?php echo htmlspecialchars($region['name']); ?>" 
                    <?php echo $selectedRegion === $region['name'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($region['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

        <label for="departement">Département :</label>
        <select id="departement" name="departement" 
            <?php echo empty($selectedRegion) ? 'disabled' : ''; ?> 
            onchange="this.form.submit()" required>
            <option value="">-- <?php echo empty($selectedRegion) ? 'Sélectionnez d\'abord une région' : 'Choisir un département'; ?> --</option>
            <?php if (!empty($selectedRegion)): ?>
                <?php foreach ($departements as $dep): ?>
                    <?php 
                    $depRegionName = $regions[$dep['region_code']]['name'] ?? '';
                    if ($depRegionName === $selectedRegion): ?>
                        <option value="<?php echo htmlspecialchars($dep['code']); ?>"
                            <?php echo ($selectedDepartement === $dep['code']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dep['name'] . ' (' . $dep['code'] . ')'); ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <label for="ville">Ville :</label>
        <select id="ville" name="ville" 
    <?php echo empty($selectedDepartement) ? 'disabled' : ''; ?> 
    onchange="if(this.value) { 
        document.getElementById('lat').value = this.options[this.selectedIndex].getAttribute('data-lat');
        document.getElementById('lon').value = this.options[this.selectedIndex].getAttribute('data-lon');
        this.form.submit(); 
    }" required>
    <option value="">-- <?php echo empty($selectedDepartement) ? 'Sélectionnez d\'abord un département' : 'Choisir une ville'; ?> --</option>
    <?php if (!empty($selectedDepartement)): ?>
        <?php foreach ($villes as $label => $ville): ?>
            <?php if ($ville['departement_number'] === $selectedDepartement): ?>
                <option value="<?php echo htmlspecialchars($label); ?>"
                    data-lat="<?php echo htmlspecialchars($ville['latitude']); ?>"
                    data-lon="<?php echo htmlspecialchars($ville['longitude']); ?>"
                    <?php echo ($selectedVille === $label) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label); ?>
                </option>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</select>

<!-- Gardez ces champs cachés tels quels -->
<input type="hidden" name="lat" id="lat" value="<?php 
    echo !empty($selectedVille) && isset($villes[$selectedVille]['latitude']) 
    ? htmlspecialchars($villes[$selectedVille]['latitude']) 
    : ''; ?>">
<input type="hidden" name="lon" id="lon" value="<?php 
    echo !empty($selectedVille) && isset($villes[$selectedVille]['longitude']) 
    ? htmlspecialchars($villes[$selectedVille]['longitude']) 
    : ''; ?>">
        <input type="submit" name="submitBtn" value="Afficher la météo" 
            <?php echo empty($selectedVille) ? 'disabled' : ''; ?>>
    </form>
<!-- Bouton retour en haut -->
<a href="#" class="back-to-top">↑</a>
</main>

<?php
    require "./include/footer.inc.php";
?>
