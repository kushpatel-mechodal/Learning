<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"] ?? "");
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
if (isset($_POST["submit"])) {

    if ($_FILES["file"]["error"] == 0) {

        $check = getimagesize($_FILES["file"]["tmp_name"]);

        if ($check !== false) {
            echo "File is an image : " . $check["mime"];
        } else {
            echo "File is not an image";
        }

    } else {
        echo "No file uploaded.";
    }
}

if (file_exists($target_file)) {
    echo "File is already exist";
    $uploadOk = 0;
}

if ($_FILES["file"]["size"] > 5000000) {
    echo "File is too large";
    $uploadOk = 0;
}

if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed";
    $uploadOk = 0;
}

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
} else {
    echo "Sorry, there was an error uploading your file.";
}