<?php
// Shows one order using its ID (GET)
include "functions.php";

// Searches the order with this ID. Returns null if it does not exist.
function searchOrder($orders, $id)
{
    foreach ($orders as $order) {
        if ($order["id"] == $id) {
            return $order;
        }
    }
    return null;
}

$orders = readJsonFile($ordersFile);
if ($orders === null) {
    showError("The file orders.json cannot be found or opened.");
}

$order = searchOrder($orders, $_GET["id"]);
if ($order === null) {
    showError("There is no order with the ID " . $_GET["id"] . ".");
}

showPage(200, "Order " . $order["id"], createTable([$order]));
