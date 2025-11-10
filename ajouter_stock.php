<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['id_pharma'])) {
    header("Location: login.php");
    exit();
}

$id_pharma = $_SESSION['id_pharma'];
$nom_medoc = trim($_POST['nom_medoc'] ?? '');
$description = trim($_POST['description'] ?? '');
$quantite = (int)($_POST['quantite'] ?? 0);

if ($nom_medoc !== '' && $quantite >= 0) {
    // Vérifier si le médicament existe déjà
    $stmt = $conn->prepare("SELECT id_medoc FROM medicaments WHERE nom = ?");
    $stmt->bind_param("s", $nom_medoc);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $med = $res->fetch_assoc();
        $id_medicament = $med['id_medoc'];
    } else {
        // Ajouter le médicament
        $insert_med = $conn->prepare("INSERT INTO medicaments (nom, description) VALUES (?, ?)");
        $insert_med->bind_param("ss", $nom_medoc, $description);
        $insert_med->execute();
        $id_medicament = $insert_med->insert_id;
        $insert_med->close();
    }
    $stmt->close();

    // Vérifier si le médicament est déjà dans le stock de la pharmacie
    $stmt2 = $conn->prepare("SELECT id FROM stock_pharmacie WHERE id_pharmacie = ? AND id_medicament = ?");
    $stmt2->bind_param("ii", $id_pharma, $id_medicament);
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
        $insert_stock->bind_param("iii", $id_pharma, $id_medicament, $quantite);
        $insert_stock->execute();
        $insert_stock->close();
    }
    $stmt2->close();
}

header("Location: dashboard.php");
exit();
?>
