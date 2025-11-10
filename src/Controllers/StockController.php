<?php
namespace App\Controllers;

use App\Models\Stock;
use App\Models\Medicament;

class StockController
{
    private $stockModel;
    private $medicamentModel;

    public function __construct()
    {
        $this->stockModel = new Stock();
        $this->medicamentModel = new Medicament();
    }

    public function viewStock()
    {
        $stocks = $this->stockModel->getAllStocks();
        return $this->render('dashboard', ['stocks' => $stocks]);
    }

    public function addStock($medicamentId, $quantity)
    {
        if ($this->medicamentModel->exists($medicamentId)) {
            $this->stockModel->addStock($medicamentId, $quantity);
            return ['status' => 'success', 'message' => 'Stock added successfully.'];
        }
        return ['status' => 'error', 'message' => 'Medicament not found.'];
    }

    public function updateStock($medicamentId, $quantity)
    {
        if ($this->stockModel->exists($medicamentId)) {
            $this->stockModel->updateStock($medicamentId, $quantity);
            return ['status' => 'success', 'message' => 'Stock updated successfully.'];
        }
        return ['status' => 'error', 'message' => 'Medicament not found in stock.'];
    }

    public function checkStockShortage()
    {
        $shortages = $this->stockModel->getStockShortages();
        return $this->render('dashboard', ['shortages' => $shortages]);
    }

    private function render($view, $data = [])
    {
        extract($data);
        include "../src/Views/{$view}.php";
    }
}
?>