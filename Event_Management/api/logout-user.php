<?php

session_start();
session_destroy();

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

$response = ["message" => "Logout successfully", "status" => true];

echo json_encode($response);
?>
