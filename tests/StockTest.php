<?php
use PHPUnit\Framework\TestCase;
use App\Models\Stock;

class StockTest extends TestCase
{
    protected $stock;

    protected function setUp(): void
    {
        $this->stock = new Stock();
    }

    public function testAddStock()
    {
        $result = $this->stock->addStock(1, 10); // Assuming 1 is the medicament ID and 10 is the quantity
        $this->assertTrue($result);
    }

    public function testUpdateStock()
    {
        $result = $this->stock->updateStock(1, 20); // Update quantity for medicament ID 1
        $this->assertTrue($result);
    }

    public function testCheckStockLevel()
    {
        $level = $this->stock->checkStockLevel(1); // Check stock level for medicament ID 1
        $this->assertIsInt($level);
        $this->assertGreaterThanOrEqual(0, $level);
    }

    public function testHandleStockShortage()
    {
        $result = $this->stock->handleStockShortage(1); // Handle shortage for medicament ID 1
        $this->assertTrue($result);
    }
}
?>