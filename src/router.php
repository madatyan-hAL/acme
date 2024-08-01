<?php

require './Basket.php';
global $pdo;

$basket = new Basket($pdo);

// Get the requested path and method
$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string from path
$path = parse_url($path, PHP_URL_PATH);

// Route the request
switch ($path) {
    case '/':
        if ($method === 'GET') {
            echo 'Acme Widget Co Test';
        }
        break;
    case '/productCatalogue':
        if ($method === 'GET') {
            echo json_encode($basket->productCatalogue());
        }
        break;

    case '/deliveryChargeRules':
        if ($method === 'GET') {
            echo json_encode($basket->deliveryChargeRules());
        }
        break;

    case '/offers':
        if ($method === 'GET') {
            echo json_encode($basket->offers());
        }
        break;

    case '/add':
        if ($method === 'POST') {
            // Get input data from POST request
            $input = json_decode(file_get_contents('php://input'), true);
            $input = $input ?? $_POST ;

            $code = $input['code'] ?? null;
            $quantities = $input['quantities'] ?? null;

            if ($code && $quantities) {
                $basket->add($code, $quantities);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            }
        }
        break;

    case '/total':
        if ($method === 'GET') {
            echo json_encode($basket->total());
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Route not found']);
        break;
}