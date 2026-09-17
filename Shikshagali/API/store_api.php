<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Auhtorized, X-Requested-With");

require("./connection.php");

$response = [];
$action = $_POST["action"] ?? '';

if ($action === "create_store_item") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $category_id = $_POST["category_id"] ?? '';
    $item_name = $_POST["item_name"] ?? '';
    $point = $_POST["point"] ?? '';
    $image = $_FILES["image"] ?? '';
    $state_id = $_POST["state_id"] ?? '';
    $district_id = $_POST["district_id"] ?? '';
    $taluka_id = $_POST["taluka_id"] ?? '';
    $school_id = $_POST["school_id"] ?? '';

    if (empty($category_id) || empty($item_name) || empty($point) || empty($image) || empty($state_id) || empty($district_id) || empty($taluka_id) || empty($school_id)) {
        http_response_code(500);
        $response = ["message" => "All fields required", "status" => false];
    } else {

        $countquery = mysqli_query($conn, "SELECT COUNT(*) AS total_row FROM store_items");
        $countrow = mysqli_fetch_assoc($countquery);
        $uniqueid = $countrow["total_row"] + 1;

        $upload_dir = "./upload/store_items/";

        if (!file_exists($upload_dir)) {
            mkdir("./upload/store_items/");
        }

        $ext = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
        $file_name = "Item_" . $uniqueid . "." . $ext;
        $upload_path = $upload_dir . $file_name;

        if (!move_uploaded_file($image["tmp_name"], $upload_path)) {
            http_response_code(500);
            $response = ["message" => "Failed to Upload Image", "status" => false];
        }

        $insert = "INSERT INTO store_items (category_id,item_name,point,image,state_id,district_id,taluka_id,school_id,created_at) 
        VALUES(?,?,?,?,?,?,?,?,NOW())";

        $stmt = mysqli_prepare($conn, $insert);

        if (!$stmt) {
            $response = ["message" => "Failed to prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "isssiiii", $category_id, $item_name, $point, $upload_path, $state_id, $district_id, $taluka_id, $school_id);

        if (mysqli_stmt_execute($stmt)) {
            http_response_code(201);
            $response = ["message" => "Store item created successfully", "status" => true];
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to create store item", "status" => false];
        }
    }
} else if ($action === "update_store_item") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $category_id = $_POST["category_id"] ?? '';
    $item_name = $_POST["item_name"] ?? '';
    $point = $_POST["point"] ?? '';
    $image = $_FILES["image"] ?? '';
    $state_id = $_POST["state_id"] ?? '';
    $district_id = $_POST["district_id"] ?? '';
    $taluka_id = $_POST["taluka_id"] ?? '';
    $school_id = $_POST["school_id"] ?? '';

    if (empty($category_id) || empty($item_name) || empty($point) || empty($state_id) || empty($district_id) || empty($taluka_id) || empty($school_id)) {
        http_response_code(500);
        $response = ["message" => "All fields required", "status" => false];
    } else {

        $id = $_POST["id"];
        $upload_dir = "./upload/store_items/";

        if (!file_exists($upload_dir)) {
            mkdir("./upload/store_items/");
        }

        $ext = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
        $file_name = "Item_" . $id . "." . $ext;
        $upload_path = $upload_dir . $file_name;

        if (!move_uploaded_file($image["tmp_name"], $upload_path)) {
            http_response_code(500);
            $response = ["message" => "Failed to Upload Image", "status" => false];
        }

        $update = "UPDATE store_items SET category_id=?,item_name=?,point=?,image=?,state_id=?,district_id=?,taluka_id=?,school_id=?,created_at=NOW()
        WHERE id=?";

        $stmt = mysqli_prepare($conn, $update);

        mysqli_stmt_bind_param($stmt, "isssiiiii", $category_id, $item_name, $point, $upload_path, $state_id, $district_id, $taluka_id, $school_id, $id);

        if (mysqli_stmt_execute($stmt)) {

            $affected_row = mysqli_affected_rows($conn);

            if ($affected_row > 0) {

                http_response_code(200);
                $response = ["message" => "Store item data update successfully", "status" => true];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found", "status" => false];
            }
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to Update store item", "status" => false];
        }
    }
} else if ($action === "delete_store_item") {

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
        $response = ["message" => "Only delete method can allowed", "status" => false];
    }

    $id = $_POST["id"] ?? '';

    $delete = "DELETE FROM store_items WHERE id='$id'";

    $stmt = mysqli_prepare($conn, $delete);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "Store item data deleted", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to delete store item data", "status" => false];
    }
} else if ($action === "read_store_item") {

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        $response = ["message" => "Only get method can allowed", "status" => false];
    }

    $id = $_POST["id"] ?? '';
    $child_id = $_POST["child_id"] ?? '';

    if (!empty($id)) {

        $read = "SELECT si.id,si.category_id,sc.category_name,si.item_name,si.point,si.state_id,s.state_name,si.district_id,d.district_name,
        si.taluka_id,t.taluka_name,si.school_id,sch.school_name FROM store_items si
        LEFT JOIN store_category sc ON si.category_id = sc.id
        LEFT JOIN states s ON si.state_id = s.id
        LEFT JOIN districts d ON si.district_id = d.id
        LEFT JOIN talukas t ON si.taluka_id = t.id
        LEFT JOIN schools sch ON si.school_id = sch.id WHERE si.id = '$id'";

    } else {

        $read = "SELECT si.id,si.category_id,sc.category_name,si.item_name,si.point,si.state_id,s.state_name,si.district_id,d.district_name,
        si.taluka_id,t.taluka_name,si.school_id,sch.school_name FROM store_items si
        LEFT JOIN store_category sc ON si.category_id = sc.id
        LEFT JOIN states s ON si.state_id = s.id
        LEFT JOIN districts d ON si.district_id = d.id
        LEFT JOIN talukas t ON si.taluka_id = t.id
        LEFT JOIN schools sch ON si.school_id = sch.id";
    }

    if (!empty($child_id)) {
        $read = "SELECT si.id,si.item_name,c.reward AS child_reward,si.point,si.school_id,sch.school_name FROM children c 
        LEFT JOIN store_items si ON c.school_id = si.school_id
        LEFT JOIN schools sch ON si.school_id = sch.id WHERE c.id = '$child_id'";
    }

    $stmt = mysqli_prepare($conn, $read);

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            http_response_code(200);
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);

            foreach ($output as &$item) {
                if ($item["child_reward"] >= $item["point"]) {
                    $item["buy"] = true;
                } else {
                    $item["buy"] = false;
                }

            }
            $response = ["message" => "Store item data fetch successfully", "status" => true, "data" => $output];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch store item data", "status" => false];
    }
}

echo json_encode($response);
?>