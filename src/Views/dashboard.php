<?php
include '../../inc/db.php';
session_start();

if (!isset($_SESSION['pharmacie_id'])) {
    die('<p>Vous devez vous connecter pour accéder au dashboard. <a href="login.php">Se connecter</a></p>');
}

$id = (int) $_SESSION['pharmacie_id'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'add_medicament') {
        $med_name = trim($_POST['med_name'] ?? '');
        if ($med_name === '') {
            $msg = "Nom du médicament requis.";
        } else {
            $ins = $conn->prepare("INSERT INTO medicaments (nom) VALUES (?)");
            if ($ins === false) {
                $msg = "Erreur lors de l'ajout du médicament.";
            } else {
                $ins->bind_param("s", $med_name);
                if ($ins->execute()) {
                    header("Location: dashboard.php?added=1");
                    exit;
                } else {
                    $msg = "Erreur lors de l'ajout du médicament.";
                }
                $ins->close();
            }
        }
    } else {
        $id_medicament = isset($_POST['id_medicament']) ? (int)$_POST['id_medicament'] : 0;
        $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 0;
        if ($id_medicament <= 0 || $quantite < 0) {
            $msg = "Données invalides.";
        } else {
            $check = $conn->prepare("SELECT id FROM stock_pharmacie WHERE id_pharmacie = ? AND id_medicament = ?");
            $check->bind_param("ii", $id, $id_medicament);
            $check->execute();
            $res = $check->get_result();

            if ($res && $res->num_rows > 0) {
                $upd = $conn->prepare("UPDATE stock_pharmacie SET quantite = ? WHERE id_pharmacie = ? AND id_medicament = ?");
                $upd->bind_param("iii", $quantite, $id, $id_medicament);
                if ($upd->execute()) {
                    $msg = "Stock mis à jour.";
                } else {
                    $msg = "Erreur lors de la mise à jour.";
                }
                $upd->close();
            } else {
                $ins = $conn->prepare("INSERT INTO stock_pharmacie (id_pharmacie, id_medicament, quantite) VALUES (?, ?, ?)");
                $ins->bind_param("iii", $id, $id_medicament, $quantite);
                if ($ins->execute()) {
                    $msg = "Stock ajouté.";
                } else {
                    $msg = "Erreur lors de l'insertion.";
                }
                $ins->close();
            }
            $check->close();
        }
    }
}

$meds_res = $conn->query("SELECT * FROM medicaments");
$meds_rows = [];
if ($meds_res) {
    while ($r = $meds_res->fetch_assoc()) {
        $meds_rows[] = $r;
    }
    $meds_res->close();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pharmacie</title>
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body>
    <div class="container">
        <h2>Gestion du stock</h2>
        <?php if ($msg): ?>
            <p><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="med_name" placeholder="Nom du médicament" required>
            <button type="submit" name="action" value="add_medicament">Ajouter Médicament</button>
        </form>

        <h3>Stock actuel :</h3>
        <table>
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $stocks_res = $conn->query("SELECT s.quantite, m.nom FROM stock_pharmacie s JOIN medicaments m ON s.id_medicament = m.id WHERE s.id_pharmacie = $id");
            if ($stocks_res) {
                while ($row = $stocks_res->fetch_assoc()) {
                    echo "<tr><td>" . htmlspecialchars($row['nom'], ENT_QUOTES, 'UTF-8') . "</td><td>" . (int)$row['quantite'] . "</td></tr>";
                }
                $stocks_res->close();
            } else {
                echo "<tr><td colspan='2'>Aucun stock enregistré.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>