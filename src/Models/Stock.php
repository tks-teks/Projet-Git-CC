<?php
namespace App\Models;

use App\Database\Database;

class Stock
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllStocks($pharmacieId)
    {
        $stmt = $this->db->prepare("SELECT * FROM stock_pharmacie WHERE id_pharmacie = ?");
        $stmt->bind_param("i", $pharmacieId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addStock($pharmacieId, $medicamentId, $quantity)
    {
        $stmt = $this->db->prepare("INSERT INTO stock_pharmacie (id_pharmacie, id_medicament, quantite) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $pharmacieId, $medicamentId, $quantity);
        return $stmt->execute();
    }

    public function updateStock($pharmacieId, $medicamentId, $quantity)
    {
        $stmt = $this->db->prepare("UPDATE stock_pharmacie SET quantite = ? WHERE id_pharmacie = ? AND id_medicament = ?");
        $stmt->bind_param("iii", $quantity, $pharmacieId, $medicamentId);
        return $stmt->execute();
    }

    public function checkStockShortage($pharmacieId, $medicamentId)
    {
        $stmt = $this->db->prepare("SELECT quantite FROM stock_pharmacie WHERE id_pharmacie = ? AND id_medicament = ?");
        $stmt->bind_param("ii", $pharmacieId, $medicamentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc()['quantite'] < 5 : true; // Assuming 5 is the threshold for shortage
    }
}
?>