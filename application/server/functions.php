<?php
// Functions used by all the PHP files of the server

$ordersFile = __DIR__ . "/../../orders/orders.json";
$usersFile = __DIR__ . "/../../users/users.json";

// The 4 products of the toy shop (prices without VAT)
function getProducts()
{
    return [
        "product1" => ["name" => "Building blocks set", "price" => 34.99],
        "product2" => ["name" => "Teddy bear", "price" => 19.95],
        "product3" => ["name" => "Remote control car", "price" => 45.00],
        "product4" => ["name" => "Family board game", "price" => 24.90]
    ];
}

// Reads a JSON file and returns an array. Returns null if it cannot be opened.
function readJsonFile($file)
{
    if (!file_exists($file)) {
        return null;
    }
    $text = file_get_contents($file);
    if ($text === false) {
        return null;
    }
    return json_decode($text, true);
}

// Saves an array in a JSON file. Returns true if it works.
function saveJsonFile($file, $data)
{
    $result = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    return $result !== false;
}

// Checks if the user and the password are correct
function checkUser($user, $password)
{
    global $usersFile;
    $users = readJsonFile($usersFile);
    if ($users === null) {
        return false;
    }
    foreach ($users as $u) {
        if ($u["user"] == $user && password_verify($password, $u["password"])) {
            return true;
        }
    }
    return false;
}

// Calculates the price of the order with 21% VAT
function calculatePriceWithVat($products)
{
    $total = 0;
    foreach ($products as $p) {
        $total = $total + $p["price"] * $p["quantity"];
    }
    return round($total * 1.21, 2);
}

// Creates an HTML table with the orders
function createTable($orders)
{
    $html = "<table>";
    $html .= "<tr><th>Order ID</th><th>Customer name</th><th>Address</th><th>Email</th>";
    $html .= "<th>Phone</th><th>Products</th><th>Price with VAT</th></tr>";

    foreach ($orders as $order) {
        $productsText = "";
        foreach ($order["products"] as $p) {
            $productsText .= $p["name"] . " (" . $p["quantity"] . " x " . $p["price"] . " €)<br>";
        }
        $html .= "<tr>";
        $html .= "<td>" . $order["id"] . "</td>";
        $html .= "<td>" . $order["customer"]["name"] . "</td>";
        $html .= "<td>" . $order["customer"]["address"] . "</td>";
        $html .= "<td>" . $order["customer"]["email"] . "</td>";
        $html .= "<td>" . $order["customer"]["phone"] . "</td>";
        $html .= "<td>" . $productsText . "</td>";
        $html .= "<td>" . number_format($order["priceWithVat"], 2) . " €</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}

// Shows an HTML page with a title, a content and the 3 return buttons
function showPage($code, $title, $content)
{
    http_response_code($code);
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>$title</title>
    <link rel='stylesheet' href='../client/styles.css'>
</head>
<body>
    <div class='card wide'>
        <h1>$title</h1>
        $content
        <div class='buttons'>
            <a class='button' href='../client/viewOrders.html'>Back to view orders menu</a>
            <a class='button' href='../client/menu.html'>Back to operations menu</a>
            <a class='button secondary' href='../client/index.html'>Back to home</a>
        </div>
    </div>
</body>
</html>";
}

// Shows an error page with the code 404 and stops the program
function showError($message)
{
    showPage(404, "Error", "<p class='error'>$message</p>");
    exit;
}
