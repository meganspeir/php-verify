<?php
    require("database.php");

    // require POST request
    if ($_SERVER['REQUEST_METHOD'] != "POST") die;

    // assuming US country code for example
    $json["status"] = statusIs('1' . $_POST["phone_number"]);

    header('Content-type: application/json');
    echo(json_encode($json));
?>