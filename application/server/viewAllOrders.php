<?php
// Shows all the orders (GET)
include "functions.php";

$orders = readJsonFile($ordersFile);
if ($orders === null) {
    showError("The file orders.json cannot be found or opened.");
}

showPage(200, "All orders", createTable($orders));
