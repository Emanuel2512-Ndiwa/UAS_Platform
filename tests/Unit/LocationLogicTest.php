<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\KurirController;
use ReflectionClass;

class LocationLogicTest extends TestCase
{
    /**
     * Test Haversine distance calculation using reflection for private method
     */
    public function test_haversine_distance_calculation()
    {
        $controller = new KurirController();
        $reflection = new ReflectionClass(KurirController::class);
        $method = $reflection->getMethod('haversineDistance');
        $method->setAccessible(true);

        // Monas (Jakarta) to Bundaran HI (Jakarta) ~ 2.5km
        $monas = ['lat' => -6.175392, 'lon' => 106.827153];
        $bhi   = ['lat' => -6.192435, 'lon' => 106.822765];

        $distance = $method->invokeArgs($controller, [
            $monas['lat'], $monas['lon'],
            $bhi['lat'], $bhi['lon']
        ]);

        $this->assertGreaterThan(1.8, $distance);
        $this->assertLessThan(2.5, $distance);
    }
}
