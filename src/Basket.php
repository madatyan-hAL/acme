<?php

include 'db/db.php';
require_once 'interfaces/BasketInterface.php';
global $pdo;

class Basket implements BasketInterface {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get the list of products.
     */
    public function productCatalogue() {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    /**
     * Get the delivery charge rules.
     */
    public function deliveryChargeRules() {
        $stmt = $this->db->query("SELECT * FROM delivery_roles");
        return $stmt->fetchAll();
    }

    /**
     * Get the list of offers.
     */
    public function offers() {
        $stmt = $this->db->query("SELECT * FROM offers");
        return $stmt->fetchAll();
    }

    /**
     * Add a product to the basket.
     *
     * @param  $code  string
     * @param  $quantities  numeric
     */
    public function add(string $code, int $quantities) {
        try {
            $product_id = $this->getProductIdByCode($code);
            if($product_id) {
                $stmt = $this->db->prepare("INSERT INTO basket (product_id, quantities) VALUES (?, ?)");
                $stmt->execute([$product_id, $quantities]);
                echo "Product added to basket successfully.".PHP_EOL;
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Calculate the total price of the basket.
     */
    public function total() {
        // Get items in the basket
        $stmt = $this->db->query("
            SELECT p.id, p.price, o.discount_percentage, SUM(b.quantities) AS total_quantities
            FROM basket b
            JOIN products p ON b.product_id = p.id
            LEFT JOIN offers o ON p.id = o.product_id
            GROUP BY p.id, p.price, o.discount_percentage
        ");
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalPrice = 0;

        foreach ($items as $item) {
            $price = $item['price'];
            $quantity = $item['total_quantities'];
            $discount = $item['discount_percentage'];

            if ($quantity > 0) {
                for($i=1; $i<=$quantity; $i++) {
                    if($i%2||!$discount) {
                        $totalPrice += $price;
                    } else {
                        $totalPrice += $price - $price*$discount/100;
                    }
                }
            }
        }

        // Determine the delivery cost
        $deliveryCost = 0;
        if ($totalPrice < 50) {
            $deliveryCost = 4.95;
        } elseif ($totalPrice < 90) {
            $deliveryCost = 2.95;
        }

        $total = $totalPrice + $deliveryCost;
        return [
            'total_price' => number_format($totalPrice, 2),
            'delivery_cost' => number_format($deliveryCost, 2),
            'total' => round($total, 2, PHP_ROUND_HALF_DOWN)
        ];
    }

    /**
     * Get product id.
     * @param  $code  string
     */
    public function getProductIdByCode(string $code) {
        $stmt = $this->db->prepare("SELECT id FROM products WHERE code = ?");
        $stmt->execute([$code]);
        $result = $stmt->fetchColumn();
        return $result !== false ? $result : null;
    }
    /**
     * Clears all rows from the basket table.
     */
    public function clearBasket() {
        $stmt = $this->db->prepare("DELETE FROM basket");
        $stmt->execute();
    }
}


?>