<?php
include 'db.php';

$q = trim($_GET['q'] ?? '');
if ($q === '') {
    header('Location: index.php');
    exit;
}

$sql = "SELECT m.nom AS medicament, m.description, p.nom AS pharmacie, p.adresse, p.latitude, p.longitude
        FROM stock_pharmacie s
        JOIN pharmacies p ON s.id_pharmacie = p.id_pharma
        JOIN medicaments m ON s.id_medicament = m.id_medoc
        WHERE m.nom LIKE ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Erreur de préparation : ' . $conn->error);
}

$like = "%{$q}%";
$stmt->bind_param("s", $like);
$stmt->execute();
$stmt->bind_result($medicament, $description, $pharmacie, $adresse, $latitude, $longitude);

$results = [];
while ($stmt->fetch()) {
    $results[] = [
        'medicament' => $medicament,
        'description' => $description,
        'pharmacie' => $pharmacie,
        'adresse' => $adresse,
        'latitude' => $latitude,
        'longitude' => $longitude
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats - Pharma Connect</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4">
        <a href="index.php" class="text-blue-500 hover:underline">← Retour</a>

        <h2 class="text-3xl font-bold mt-4 mb-8 text-center">
            Résultats pour "<span class="text-blue-600"><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?></span>"
        </h2>

        <div class="grid md:grid-cols-2 gap-6">
            <?php if (count($results) > 0): ?>
                <?php foreach ($results as $row): ?>
                    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between hover:shadow-lg transition">
                        <!-- Nom du médicament -->
                        <div class="mb-3">
                            <h3 class="text-green-700 font-bold text-lg">Nom du médicament</h3>
                            <p class="text-gray-800"><?= htmlspecialchars($row['medicament'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <h3 class="text-green-700 font-bold text-lg">Description</h3>
                            <p class="text-gray-700"><?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>

                        <!-- Pharmacie -->
                        <div class="mb-3">
                            <h3 class="text-green-700 font-bold text-lg">Pharmacie</h3>
                            <p class="text-gray-800"><?= htmlspecialchars($row['pharmacie'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>

                        <!-- Adresse -->
                        <div class="mb-3">
                            <h3 class="text-green-700 font-bold text-lg">Adresse</h3>
                            <p class="text-gray-600"><?= htmlspecialchars($row['adresse'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>

                        <!-- Coordonnées -->
                        <div class="mb-3">
                            <h3 class="text-green-700 font-bold text-lg">Coordonnées</h3>
                            <p class="text-gray-500 text-sm">Latitude: <?= $row['latitude'] ?> | Longitude: <?= $row['longitude'] ?></p>
                        </div>

                        <!-- Bouton Google Maps -->
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= $row['latitude'] ?>,<?= $row['longitude'] ?>" 
                           target="_blank" 
                           class="mt-2 inline-block bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 text-center">
                            Voir sur Google Maps
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col-span-2 text-gray-600 text-center text-lg">Aucune pharmacie ne propose ce médicament pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
