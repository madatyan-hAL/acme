<?php
include 'src/Basket.php';
include 'src/db/db.php';

use PHPUnit\Framework\TestCase;


class BasketTest extends TestCase
{
    private $basket;

    protected function setUp(): void
    {   global $pdo;
        $this->basket = new Basket($pdo);
    }

    /**
     * Test the productCatalogue method.
     *
     * This test verifies that the productCatalogue method of the Basket class
     * correctly retrieves and returns a list of all products
     *
     */
    public function testProductCatalogue()
    {
        $result = $this->basket->productCatalogue();
        $this->assertEquals(count($result), 3);
    }

    /**
     * Test the add method and total method.
     *
     * This test verifies that the add method correctly adds items to the basket
     * and that the total method accurately calculates the total price of the items
     * in the basket, including delivery charge rules and special offers.
     *
     */
    public function testAddAndTotalPrice()
    {
        //Add in basket B01, G01
        $this->basket->clearBasket();
        $this->basket->add('B01', 1);
        $this->basket->add('G01', 1);
        $result = $this->basket->total();
        $this->assertEquals($result['total'], 37.85);

        //Add in basket R01, R01
        $this->basket->clearBasket();
        $this->basket->add('R01', 1);
        $this->basket->add('R01', 1);
        $result = $this->basket->total();
        $this->assertEquals($result['total'], 54.37);

        //Add in basket R01, G01
        $this->basket->clearBasket();
        $this->basket->add('R01', 1);
        $this->basket->add('G01', 1);
        $result = $this->basket->total();
        $this->assertEquals($result['total'], 60.85);

        //Add in basket B01, B01, R01, R01, R01
        $this->basket->clearBasket();
        $this->basket->add('B01', 2);
        $this->basket->add('R01', 3);
        $result = $this->basket->total();
        $this->assertEquals($result['total'], 98.27);

    }
}