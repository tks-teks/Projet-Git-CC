<?php
include 'db.php';
session_start();

if (!isset($_SESSION['pharmacie_id'])) {
    header("Location: login.php");
    exit;
}

$id = (int) $_SESSION['pharmacie_id'];
$msg = "";

// CSRF token simple
if (!isset($_SESSION['token'])) {
    try {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

// Ajouter / mettre à jour un stock
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérification du token CSRF
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        $msg = "Requête invalide (token).";
    } else {
        // Validation des entrées
        $id_medicament = isset($_POST['id_medicament']) ? (int)$_POST['id_medicament'] : 0;
        $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 0;
        if ($id_medicament <= 0 || $quantite < 0) {
            $msg = "Données invalides.";
        } else {
            // Vérifier si la ligne existe
            $check = $conn->prepare("SELECT id FROM stock_pharmacie WHERE id_pharmacie = ? AND id_medicament = ?");
            if ($check === false) {
                $msg = "Erreur serveur.";
            } else {
                $check->bind_param("ii", $id, $id_medicament);
                $check->execute();
                $res = $check->get_result();

                if ($res && $res->num_rows > 0) {
                    $upd = $conn->prepare("UPDATE stock_pharmacie SET quantite = ? WHERE id_pharmacie = ? AND id_medicament = ?");
                    if ($upd === false) {
                        $msg = "Erreur serveur (update).";
                    } else {
                        $upd->bind_param("iii", $quantite, $id, $id_medicament);
                        if ($upd->execute()) {
                            $msg = "Stock mis à jour.";
                        } else {
                            $msg = "Erreur lors de la mise à jour.";
                        }
                        $upd->close();
                    }
                } else {
                    $ins = $conn->prepare("INSERT INTO stock_pharmacie (id_pharmacie, id_medicament, quantite) VALUES (?, ?, ?)");
                    if ($ins === false) {
                        $msg = "Erreur serveur (insert).";
                    } else {
                        $ins->bind_param("iii", $id, $id_medicament, $quantite);
                        if ($ins->execute()) {
                            $msg = "Stock ajouté.";
                        } else {
                            $msg = "Erreur lors de l'insertion.";
                        }
                        $ins->close();
                    }
                }

                $check->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pharmacie</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 shadow-lg rounded-2xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-blue-600">📦 Gestion du stock</h2>
            <a href="logout.php" class="text-red-500 hover:underline">Déconnexion</a>
        </div>

        <?php if ($msg): ?>
            <p class="text-sm text-gray-700 mb-4"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="POST" class="flex gap-4 mb-6" novalidate>
            <select name="id_medicament" class="border p-2 rounded-lg flex-1" required>
                <?php
                $meds = $conn->query("SELECT id, nom FROM medicaments ORDER BY nom ASC");
                if ($meds) {
                    while ($m = $meds->fetch_assoc()) {
                        $mid = (int)$m['id'];
                        $mnom = htmlspecialchars($m['nom'], ENT_QUOTES, 'UTF-8');
                        echo "<option value='{$mid}'>{$mnom}</option>";
                    }
                    $meds->close();
                } else {
                    echo "<option value=''>Aucun médicament</option>";
                }
                ?>
            </select>

            <input type="number" name="quantite" placeholder="Quantité" class="border p-2 rounded-lg w-32" required min="0" step="1">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8') ?>">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Enregistrer</button>
        </form>

        <h3 class="text-lg font-semibold mb-3">Stock actuel :</h3>
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">Médicament</th>
                    <th class="p-2 text-left">Quantité</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $stocks_stmt = $conn->prepare("SELECT m.nom, s.quantite FROM stock_pharmacie s JOIN medicaments m ON s.id_medicament = m.id WHERE s.id_pharmacie = ?");
            if ($stocks_stmt) {
                $stocks_stmt->bind_param("i", $id);
                $stocks_stmt->execute();
                $stocks = $stocks_stmt->get_result();
                if ($stocks && $stocks->num_rows > 0) {
                    while ($row = $stocks->fetch_assoc()) {
                        $nom = htmlspecialchars($row['nom'], ENT_QUOTES, 'UTF-8');
                        $qte = (int)$row['quantite'];
                        echo "<tr><td class='p-2'>{$nom}</td><td class='p-2'>{$qte}</td></tr>";
                    }
                } else {
                    echo "<tr><td class='p-2' colspan='2'>Aucun stock enregistré.</td></tr>";
                }
                $stocks_stmt->close();
            } else {
                echo "<tr><td class='p-2' colspan='2'>Impossible de récupérer le stock.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>