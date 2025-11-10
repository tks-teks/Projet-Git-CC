<?php
namespace App\Controllers;

use App\Models\Medicament;
use App\Models\Stock;
use App\Models\Pharmacie;

class DashboardController
{
    private $medicamentModel;
    private $stockModel;
    private $pharmacieModel;

    public function __construct()
    {
        $this->medicamentModel = new Medicament();
        $this->stockModel = new Stock();
        $this->pharmacieModel = new Pharmacie();
    }

    public function index()
    {
        $medicaments = $this->medicamentModel->getAll();
        $stocks = $this->stockModel->getAllByPharmacie($_SESSION['pharmacie_id']);
        
        // Render the dashboard view
        require_once __DIR__ . '/../Views/dashboard.php';
    }

    public function addMedicament($data)
    {
        if (isset($data['med_name']) && !empty($data['med_name'])) {
            $this->medicamentModel->add($data['med_name']);
            return "Médicament ajouté avec succès.";
        }
        return "Le nom du médicament est requis.";
    }

    public function updateStock($data)
    {
        if (isset($data['id_medicament'], $data['quantite'])) {
            $this->stockModel->update($data['id_medicament'], $data['quantite'], $_SESSION['pharmacie_id']);
            return "Stock mis à jour.";
        }
        return "Données invalides.";
    }

    public function handleStockShortage()
    {
        $shortages = $this->stockModel->checkShortages($_SESSION['pharmacie_id']);
        // Handle notifications or alerts for stock shortages
        return $shortages;
    }
}
?>