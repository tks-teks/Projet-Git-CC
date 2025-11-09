<?php
include 'db.php';

$q = trim($_GET['q'] ?? '');
if ($q === '') {
    header('Location: index.php');
    exit;
}

$sql = "SELECT p.nom AS pharmacie, p.adresse, p.latitude, p.longitude, p.contact, s.quantite, m.nom AS medicament
    FROM stock_pharmacie s
    JOIN pharmacies p ON s.id_pharmacie = p.id
    JOIN medicaments m ON s.id_medicament = m.id
    WHERE m.nom LIKE ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Erreur de préparation : ' . $conn->error);
}

$like = "%{$q}%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats - Pharma Connect</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto mt-10">
        <a href="index.php" class="text-blue-500 hover:underline">← Retour</a>

        <h2 class="text-2xl font-bold mt-4 mb-6">
            Résultats pour "<span class="text-blue-600"><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?></span>"
        </h2>

        <div class="grid gap-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bg-white shadow rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-lg text-gray-800"><?= htmlspecialchars($row['pharmacie'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="text-gray-600 text-sm"><?= htmlspecialchars($row['adresse'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-gray-500 text-sm">📞 <?= htmlspecialchars($row['contact'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="text-right">
                            <?php if ((int)$row['quantite'] > 0): ?>
                                <span class="text-green-600 font-semibold">Disponible (<?= (int)$row['quantite'] ?>)</span>
                            <?php else: ?>
                                <span class="text-red-500 font-semibold">Rupture</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-600">Aucune pharmacie ne propose ce médicament pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>