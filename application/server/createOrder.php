<?php
// Creates a new order (POST). The answer is only the price with VAT.
include "functions.php";

// Creates the array of the order with the data of the form
function createOrder()
{
    $products = [];
    foreach (getProducts() as $key => $product) {
        // Only the checked products send a quantity
        if (isset($_POST[$key])) {
            $products[] = [
                "name" => $product["name"],
                "price" => $product["price"],
                "quantity" => (int) $_POST[$key]
            ];
        }
    }

    return [
        "id" => $_POST["id"],
        "customer" => [
            "name" => $_POST["name"],
            "address" => $_POST["address"],
            "email" => $_POST["email"],
            "phone" => $_POST["phone"]
        ],
        "products" => $products,
        "priceWithVat" => calculatePriceWithVat($products)
    ];
}

$order = createOrder();

// The first time the file does not exist and it is created
$orders = [];
if (file_exists($ordersFile)) {
    $orders = readJsonFile($ordersFile);
    if ($orders === null) {
        showError("The file orders.json cannot be opened.");
    }
}

$orders[] = $order;
if (!saveJsonFile($ordersFile, $orders)) {
    showError("The file orders.json cannot be found or opened.");
}

echo number_format($order["priceWithVat"], 2);
