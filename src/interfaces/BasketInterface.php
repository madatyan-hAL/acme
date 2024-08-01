<?php
interface BasketInterface {
    /**
     * Get the list of products.
     */
    public function productCatalogue();
    /**
     * Get the delivery charge rules.
     */
    public function deliveryChargeRules();
    /**
     * Get the list of offers.
     */
    public function offers();
    /**
     * Add a product to the basket.
     *
     * @param  $code  string
     * @param  $quantities  integer
     */
    public function add(string $code, int $quantities);
    /**
     * Calculate the total price of the basket.
     */
    public function total();
    /**
     * Get product id.
     * @param  $code  string
     */
    public function getProductIdByCode(string $code);

}