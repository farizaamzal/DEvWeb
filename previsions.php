<?php
    $title="Prévisions - Météo&amp;Climat";
    require "./include/header.inc.php";

    include_once"./include/functions.inc.php";

    $data = construire_regions_departements_villes("v_region_2024.csv", "v_departement_2024.csv", "cities.csv");

    $selected_region = $_GET['region'] ?? null;
    $styleParam = isset($_GET['style']) ? 'style=' . $_GET['style'] : (isset($_COOKIE['theme']) ? 'style=' . $_COOKIE['theme'] : 'style=normal');


    // Associer chaque région à ses coordonnées
    $region_coords = [
        "Île-de-France" => "339,143,361,143,371,154,372,179,356,196,318,182,291,148,310,136",
        "Hauts-de-France" => "307,29,340,29,404,82,385,141,369,147,309,132,294,82",
        "Grand Est" => "446,227,425,204,393,206,377,158,405,99,429,82,434,103,493,122,513,138,547,143,565,156,540,191,544,232,511,217,479,204",
        "Bourgogne-Franche-Comté" => "436,291,406,312,385,282,354,271,367,183,400,206,428,207,456,225,479,208,514,213,527,240,502,275,467,303,450,299",
        "Auvergne-Rhône-Alpes" => "467,397,458,425,406,420,381,386,321,396,329,365,343,344,332,295,352,283,377,283,402,311,439,296,471,309,508,294,524,361",
        "Provence-Alpes-Côte d'Azur" => "196,171,210,163,217,176,227,196,210,219,168,204,190,188",
        "Corse" => "247,203,247,239,235,235,231,217",
        "Occitanie" => "414,458,365,483,339,533,307,519,223,507,242,445,271,436,298,392,336,399,384,390",
        "Nouvelle-Aquitaine" => "186,493,179,319,202,296,240,257,299,299,339,319,296,381,229,440,221,509",
        "Pays de la Loire" => "142,252,159,294,200,303,226,256,267,202,251,186,229,182,213,177,198,188,180,221,147,235",
        "Bretagne" => "41,179,49,202,61,210,127,232,183,214,191,179,170,169,130,172,105,158,86,164,58,173",
        "Normandie" => "161,106,170,138,179,169,273,193,275,164,301,127,302,101,242,105,239,132,186,122",
        "Centre-Val de Loire" => "339,286,308,294,267,272,238,247,265,211,276,189,272,168,288,164,314,180,351,199,357,268",
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
<img src="./images/CarteDeFrance.jpeg" alt="Carte de France" usemap="#image-map"/>
<figcaption>Carte de la France avec une image cliquable</figcaption>
</figure>

<!-- Une image map permet de rendre certaines zones d'une image interactives (cliquables) -->
<map name="image-map">
    <?php foreach ($data as $region => $deps): ?> <!-- Boucle à travers le tableau $data qui contient les régions et leurs départements -->
        <?php
        // Vérifie si la région a des coordonnées définies dans le tableau $region_coords
        // Si oui, on les utilise ; sinon, on met une valeur par défaut "0,0,0,0"
        // (Les coordonnées définissent où se trouve la région sur l'image map)
        $coords = $region_coords[$region] ?? "0,0,0,0,0,0"; // Coordonnées par défaut
        ?>
        <area alt="<?php echo htmlspecialchars($region); ?>" 
              title="<?php echo htmlspecialchars($region); ?>"
              href="./regions.php?region=<?php echo urlencode($region); ?>&amp;<?php echo $styleParam; ?>" 
              coords="<?php echo $coords; ?>" shape="poly"/>
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
    <input type="hidden" name="style" value="<?php echo isset($_GET['style']) ? htmlspecialchars($_GET['style']) : 'default'; ?>"/>
    
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
            <?php echo empty($selectedRegion) ? 'disabled="disabled"' : ''; ?> 
            onchange="this.form.submit()" required=''>
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
    <?php echo empty($selectedDepartement) ? 'disabled="disabled"' : ''; ?> 
    onchange="if(this.value) { 
        document.getElementById('lat').value = this.options[this.selectedIndex].getAttribute('data-lat');
        document.getElementById('lon').value = this.options[this.selectedIndex].getAttribute('data-lon');
        this.form.submit(); 
    }" required=''>
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
    : ''; ?>"/>
<input type="hidden" name="lon" id="lon" value="<?php 
    echo !empty($selectedVille) && isset($villes[$selectedVille]['longitude']) 
    ? htmlspecialchars($villes[$selectedVille]['longitude']) 
    : ''; ?>"/>
        <input type="submit" name="submitBtn" value="Afficher la météo" 
            <?php echo empty($selectedVille) ? 'disabled="disabled"' : ''; ?>/>
    </form>
<!-- Bouton retour en haut -->
<a href="#" class="back-to-top">↑</a>
</main>

<?php
    require "./include/footer.inc.php";
?>
