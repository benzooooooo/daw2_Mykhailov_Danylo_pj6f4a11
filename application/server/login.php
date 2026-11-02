<?php
// Authentication (POST). The answer is only "OK" or "ERROR".
include "functions.php";

if (checkUser($_POST["user"], $_POST["password"])) {
    echo "OK";
} else {
    echo "ERROR";
}
