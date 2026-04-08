<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_status_is_safe_when_stock_is_high()
    {
        $item = Inventory::create([
            'item_name' => 'Sabun',
            'stock' => 5,
            'unit' => 'Liter'
        ]);

        $this->assertEquals('safe', $item->status);
    }

    public function test_inventory_status_is_low_stock_when_stock_is_low()
    {
        $item = Inventory::create([
            'item_name' => 'Sabun',
            'stock' => 2,
            'unit' => 'Liter'
        ]);

        $this->assertEquals('low_stock', $item->status);
    }

    public function test_inventory_status_is_empty_when_stock_is_zero()
    {
        $item = Inventory::create([
            'item_name' => 'Sabun',
            'stock' => 0,
            'unit' => 'Liter'
        ]);

        $this->assertEquals('empty', $item->status);
    }

    public function test_inventory_status_updates_on_save()
    {
        $item = Inventory::create([
            'item_name' => 'Sabun',
            'stock' => 5,
            'unit' => 'Liter'
        ]);

        $this->assertEquals('safe', $item->status);

        $item->stock = 1;
        $item->save();

        $this->assertEquals('low_stock', $item->status);
    }
}
