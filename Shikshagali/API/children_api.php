<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-with");

require("./connection.php");

// $_POST = json_decode(file_get_contents("php://input"), true);

$response = [];
$action = $_POST["action"] ?? '';

if ($action === "create_child") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only Post can allowed", "status" => false];
    }

    $parent_id = $_POST["parent_id"] ?? '';
    $child_name = $_POST["child_name"] ?? '';
    $gender = $_POST["gender"] ?? '';
    $state_id = $_POST["state_id"] ?? '';
    $district_id = $_POST["district_id"] ?? '';
    $taluka_id = $_POST["taluka_id"] ?? '';
    $school_name = $_POST["school_name"] ?? '';
    $school_id = $_POST["school_id"] ?? '';
    $standard = $_POST["standard"] ?? '';
    $image = $_FILES["image"] ?? '';

    $upload_dir = "./upload/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $orignal_name = pathinfo($image["name"], PATHINFO_FILENAME);
    $orignal_name = preg_replace("/[^A-Za-z0-9_-]/", "_", $orignal_name);

    $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
    $file_name = "image_" . $orignal_name . "_" . time() . "." . $ext;
    $upload_path = $upload_dir . $file_name;

    if (!move_uploaded_file($image["tmp_name"], $upload_path)) {
        http_response_code(500);

        $response = ["message" => "Failed to upload photo", "status" => false];
        exit;
    }

    $query = "INSERT INTO children (parent_id,child_name,gender,state_id,district_id,taluka_id,school_name,school_id,standard,image) VALUES(?,?,?,?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed To Prepare" . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "issiiisiss", $parent_id, $child_name, $gender, $state_id, $district_id, $taluka_id, $school_name, $school_id, $standard, $upload_path);

    if (mysqli_stmt_execute($stmt)) {

        http_response_code(201);
        $response = ["message" => "Child create successfully", "status" => true];
    } else {
        http_response_code(500);
        $response = ["message" => "Child not create successfully", "status" => false];
    }
} else if ($action === "read_child") {
    header("Access-Control-Allow-Methods: GET,OPTIONS");

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {

        $reponse = ["message" => "Only get method can allowed", "status" => false];
    }

    $id = $_POST["id"];

    if (!empty($id)) {
        $sql = "SELECT c.id, c.parent_id,p.name,c.child_name,c.gender,c.state_id,s.state_name,c.district_id,d.district_name,c.taluka_id,t.taluka_name,c.school_id,sc.school_name,c.standard FROM children c
        LEFT JOIN parents p ON c.parent_id = p.id
        LEFT JOIN states s ON c.state_id = s.id
        LEFT JOIN districts d ON c.district_id = d.id
        LEFT JOIN talukas t ON c.taluka_id = t.id
        LEFT JOIN schools sc ON c.school_id = sc.id WHERE c.id = '$id'";

    } else {
        $sql = "SELECT c.id, c.parent_id,p.name,c.child_name,c.gender,c.state_id,s.state_name,c.district_id,d.district_name,c.taluka_id,t.taluka_name,c.school_id,sc.school_name,c.standard FROM children c
        LEFT JOIN parents p ON c.parent_id = p.id
        LEFT JOIN states s ON c.state_id = s.id
        LEFT JOIN districts d ON c.district_id = d.id
        LEFT JOIN talukas t ON c.taluka_id = t.id
        LEFT JOIN schools sc ON c.school_id = sc.id";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Failed to Prepare" . mysqli_error($conn));
    }

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            http_response_code(200);
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response = ["message" => "Data Fetch Successfully", "status" => true, "data" => $output];
        } else {
            http_response_code(404);
            $response = ["message" => "Data Not Found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to Fetch the Data", "status" => false];
    }

} else if ($action === "update_child") {

    header("Access-Control-Allow-Methods: PUT,OPTIONS");

    if ($_SERVER["REQUEST_METHOD"] !== "PUT") {

        $response = ["message" => "Only Put can allowed", "status" => false];
    }

    $id = $_POST["id"];
    $parent_id = $_POST["parent_id"];
    $child_name = $_POST["child_name"];
    $gender = $_POST["gender"];
    $state_id = $_POST["state_id"];
    $district_id = $_POST["district_id"];
    $taluka_id = $_POST["taluka_id"];
    $school_name = $_POST["school_name"];
    $school_id = $_POST["school_id"];
    $standard = $_POST["standard"];
    $image = $_FILES["image"] ?? '';

    $upload_dir = "./upload/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $orignal_name = pathinfo($image["name"], PATHINFO_FILENAME);
    $orignal_name = preg_replace("/[^A-Za-z0-9_-]/", "_", $orignal_name);

    $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
    $file_name = "image_" . $orignal_name . "_" . time() . "." . $ext;
    $upload_path = $upload_dir . $file_name;

    if (!move_uploaded_file($image["tmp_name"], $upload_path)) {
        http_response_code(500);

        $response = ["message" => "Failed to upload photo", "status" => false];
        exit;
    }

    $sql = "UPDATE children SET parent_id =?, child_name = ?, gender = ?,state_id=?,district_id=?,taluka_id=?,school_name=?,school_id=?,standard=?,image=? where id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Failed to Prepare" . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "issiiisisis", $parent_id, $child_name, $gender, $state_id, $district_id, $taluka_id, $school_name, $school_id, $standard, $id, $upload_path);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "Children Data Update Successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data Not Found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to update children data", "status" => false];
    }
} else if ($action === "delete_child") {
    header("Access-Control-Allow-Methods: DELETE,OPTIONS");

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {

        $response = ["message" => "Only Delete can allowed", "status" => false];
    }

    $id = $_POST["id"];

    $sql = "DELETE FROM children WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Failed to Prepare" . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_stmt_affected_rows($stmt);

        if ($affected_row > 0) {

            http_response_code(200);
            $response = ["message" => "Children Data Deleted Successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data Not Found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to delete children data", "status" => false];
    }
} else {
    http_response_code(400);
    $response = ["message" => "Invalid Input", "status" => false];
}

echo json_encode($response);
?>