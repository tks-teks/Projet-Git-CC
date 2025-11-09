<?php
include 'db.php';
session_start();

if (!isset($_SESSION['pharmacie_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['pharmacie_id'];

// Ajouter un stock
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_medicament = $_POST['id_medicament'];
    $quantite = $_POST['quantite'];

    $check = $conn->prepare("SELECT id FROM stock_pharmacie WHERE id_pharmacie=? AND id_medicament=?");
    $check->bind_param("ii", $id, $id_medicament);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $conn->query("UPDATE stock_pharmacie SET quantite=$quantite WHERE id_pharmacie=$id AND id_medicament=$id_medicament");
    } else {
        $conn->query("INSERT INTO stock_pharmacie (id_pharmacie, id_medicament, quantite) VALUES ($id, $id_medicament, $quantite)");
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pharmacie</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 shadow-lg rounded-2xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-blue-600">📦 Gestion du stock</h2>
            <a href="logout.php" class="text-red-500 hover:underline">Déconnexion</a>
        </div>

        <form method="POST" class="flex gap-4 mb-6">
            <select name="id_medicament" class="border p-2 rounded-lg flex-1">
                <?php
                $meds = $conn->query("SELECT * FROM medicaments");
                while ($m = $meds->fetch_assoc()) {
                    echo "<option value='{$m['id']}'>{$m['nom']}</option>";
                }
                ?>
            </select>

            <input type="number" name="quantite" placeholder="Quantité" class="border p-2 rounded-lg w-32" required>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue- 700">Enregistrer</button>
        </form>

        <h3 class="text-lg font-semibold mb-3">Stock actuel :</h3>
        <table class="w-full border">
            <tr class="bg-gray-200">
                <th class="p-2">Médicament</th>
                <th>Quantité</th>
            </tr>
            <?php
            $stocks = $conn->query("SELECT m.nom, s.quantite FROM stock_pharmacie s JOIN medicaments m ON s.id_medicament=m.id WHERE s.id_pharmacie=$id");
            while ($row = $stocks->fetch_assoc()) {
                echo "<tr><td class='p-2'>{$row['nom']}</td><td>{$row['quantite']}</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>