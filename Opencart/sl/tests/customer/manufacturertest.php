<?php 
namespace Tests;

class ModelCatalogManufacturerTest extends OpenCartTest
{
    public function testASpecificManufacturer()
    {
        // load the manufacturer model
        $model = $this->loadModel("catalog/manufacturer");
        $manufacturer = $model->getManufacturer(6);

        // test a specific assertion
        $this->assertEquals('Palm', $manufacturer['name']);

    }
}
?>