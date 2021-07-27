<?php 
namespace Tests;
class Firsttest extends OpenCartTest { 
    public function testOne() {
    	$this->assertEquals('2', '1');
    }   
    public function testTwo() {
    	$this->assertEquals('1', '1');
    }   
    public function testThree() {
    	$this->assertEquals('2', '2');
    }   
    public function testFour() {
    	$this->assertEquals('1', '2');
    }
}
?>