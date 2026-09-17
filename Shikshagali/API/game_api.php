<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorized,X-requested-With");

require("./connection.php");

$response = [];
$action = $_POST["action"] ?? '';

if ($action === "create_game") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only Post method can allowed", "status" => false];
    }

    $category_id = $_POST["category_id"] ?? '';
    $game_name = $_POST["game_name"] ?? '';
    $link = $_POST["link"] ?? '';
    $tags = $_POST["tags"] ?? '';
    $image = $_FILES["image"] ?? null;

    if (empty($category_id) || empty($game_name) || empty($link) || empty($tags) || empty($image)) {
        $response = ["message" => "All fields are Required", "status" => false];

    } else {
        $upload_dir = "./upload/games/";

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $countquery = mysqli_query($conn, "SELECT COUNT(*) AS total_row FROM games");
        $countrow = mysqli_fetch_assoc($countquery);
        $uniqueid = $countrow["total_row"] + 1;

        $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
        $file_name = "game_" . $uniqueid . "." . $ext;
        $upload_path = $upload_dir . $file_name;

        if (!move_uploaded_file($image["tmp_name"], $upload_path)) {

            http_response_code(500);
            $response = ["message" => "Failed to upload photo", "status" => false];
        }

        $sql = "INSERT INTO games (category_id,game_name,redirection_link,tags,image) VALUES(?,?,?,?,?)";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $response = ["message" => "Failed to prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "issss", $category_id, $game_name, $link, $tags, $upload_path);

        if (mysqli_stmt_execute($stmt)) {

            http_response_code(201);
            $response = ["message" => "Game Create Successsfully", "status" => true];

        } else {
            http_response_code(500);
            $response = ["message" => "Failed to create game", "status" => false];
        }
    }
} else if ($action === "update_game") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only put method can allowed", "status" => false];
    }

    $id = $_POST["id"];
    $category_id = $_POST["category_id"] ?? '';
    $game_name = $_POST["game_name"] ?? '';
    $link = $_POST["link"] ?? '';
    $tags = $_POST["tags"] ?? '';
    $image = $_FILES["image"] ?? null;

    if (empty($category_id) || empty($game_name) || empty($link) || empty($tags)) {
        $response = ["message" => "All fields are Required", "status" => false];

    } else {
        $upload_dir = "./upload/children/";

        $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
        $file_name = "game_" . $id . "." . $ext;
        $upload_path = $upload_dir . $file_name;

        $sql = "UPDATE games SET category_id=?,game_name=?,redirection_link=?,tags=?,image=? WHERE id=?";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $reponse = ["message" => "Failed to prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "issssi", $category_id, $game_name, $link, $tags, $upload_path, $id);

        if (mysqli_stmt_execute($stmt)) {

            $affected_row = mysqli_stmt_affected_rows($stmt);

            if ($affected_row > 0) {
                http_response_code(200);
                $response = ["message" => "Game date update successfully", "status" => true];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found", "status" => false];
            }
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to update game", "status" => false];
        }
    }
} else if ($action === "read_game") {

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $response = ["message" => "Only get method can allowed", "status" => false];
    }

    $id = $_POST["id"] ?? '';

    if (!empty($id)) {
        $sql = "SELECT * From games WHERE id='$id'";
    } else {
        $sql = "SELECT * From games";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $reponse = ["message" => "Failed to prepare", "status" => false];
    }

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            http_response_code(200);
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response = ["message" => "Game data fetch successfully", "status" => true, "game_data" => $output];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch the game data", "status" => false];
    }
} else if ($action === "delete_game") {

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
        $response = ["message" => "Only delete method can allowed", "status" => false];
    }

    $id = $_POST["id"];

    $sql = "DELETE FROM games WHERE id = '$id'";

    $stmt = mysqli_prepare($conn, $sql);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_stmt_affected_rows($stmt);

        if ($affected_row > 0) {

            http_response_code(200);
            $response = ["message" => "Game data deleted successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to delete the game data", "status" => false];
    }


} else {
    http_response_code(400);
    $response = ["message" => "Invalid Inputs", "status" => false];
}

echo json_encode($response);

?>