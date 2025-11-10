<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['id_pharma'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$conn->prepare("DELETE FROM stock_pharmacie WHERE id = ?")->execute([$id]);

header("Location: dashboard_pharma.php");
exit();
?>
