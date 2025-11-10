<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['id_pharma'])) {
    header("Location: login.php");
    exit();
}

$id_pharma = $_SESSION['id_pharma'];

// Traitement du formulaire d'ajout de médicament et stock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_medoc = trim($_POST['nom_medoc'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $quantite = (int)($_POST['quantite'] ?? 0);

    if ($nom_medoc !== '' && $quantite >= 0) {
        // Ajouter le médicament s'il n'existe pas
        $stmt = $conn->prepare("SELECT id_medoc FROM medicaments WHERE nom = ?");
        $stmt->bind_param("s", $nom_medoc);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $med = $res->fetch_assoc();
            $id_medoc = $med['id_medoc'];
        } else {
            $insert_med = $conn->prepare("INSERT INTO medicaments (nom, description) VALUES (?, ?)");
            $insert_med->bind_param("ss", $nom_medoc, $description);
            $insert_med->execute();
            $id_medoc = $insert_med->insert_id;
            $insert_med->close();
        }
        $stmt->close();

        // Vérifier si le médicament est déjà dans le stock de la pharmacie
        $stmt2 = $conn->prepare("SELECT id, quantite FROM stock_pharmacie WHERE id_pharmacie = ? AND id_medicament = ?");
        $stmt2->bind_param("ii", $id_pharma, $id_medoc);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        if ($res2 && $res2->num_rows > 0) {
            // Mise à jour
            $stock = $res2->fetch_assoc();
            $update = $conn->prepare("UPDATE stock_pharmacie SET quantite = ? WHERE id = ?");
            $update->bind_param("ii", $quantite, $stock['id']);
            $update->execute();
            $update->close();
        } else {
            // Ajout
            $insert_stock = $conn->prepare("INSERT INTO stock_pharmacie (id_pharmacie, id_medicament, quantite) VALUES (?, ?, ?)");
            $insert_stock->bind_param("iii", $id_pharma, $id_medoc, $quantite);
            $insert_stock->execute();
            $insert_stock->close();
        }
        $stmt2->close();
    }
}

// Récupération du stock complet de la pharmacie
$sql = "SELECT s.id, m.nom, s.quantite 
        FROM stock_pharmacie s
        JOIN medicaments m ON s.id_medicament = m.id_medoc
        WHERE s.id_pharmacie = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pharma);
$stmt->execute();
$res = $stmt->get_result();
$stocks = [];
while ($row = $res->fetch_assoc()) {
    $stocks[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pharmacie</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">

<header class="bg-green-700 text-white p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">PharmaConnect</h1>
    <a href="logout.php" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">Déconnexion</a>
</header>

<div class="p-6 max-w-4xl mx-auto">

    <h2 class="text-2xl font-semibold mb-4 text-center">Gestion des Médicaments</h2>

    <form method="POST" class="bg-white p-4 rounded-lg shadow mb-8">
        <h3 class="text-lg font-semibold mb-2">Ajouter / Mettre à jour un médicament</h3>

        <label class="block mb-2 font-medium">Nom du médicament</label>
        <input type="text" name="nom_medoc" required class="border rounded p-2 w-full mb-3">

        <label class="block mb-2 font-medium">Description (optionnelle)</label>
        <textarea name="description" class="border rounded p-2 w-full mb-3"></textarea>

        <label class="block mb-2 font-medium">Quantité</label>
        <input type="number" name="quantite" required min="0" class="border rounded p-2 w-full mb-4">

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            ✅ Ajouter / Mettre à jour
        </button>
    </form>

    <h3 class="text-lg font-semibold mb-2">📦 Médicaments en stock</h3>
    <?php if (count($stocks) > 0): ?>
        <table class="w-full bg-white rounded shadow">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="p-2">Nom</th>
                    <th class="p-2">Quantité</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stocks as $s): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2"><?= htmlspecialchars($s['nom']) ?></td>
                        <td class="p-2"><?= $s['quantite'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-gray-600 mt-2">Aucun médicament ajouté pour le moment.</p>
    <?php endif; ?>

</div>

</body>
</html>
