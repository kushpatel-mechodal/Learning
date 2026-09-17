<?php

$student = array("name" => "kush", "age" => "23", "year" => 2004);
echo json_encode($student);
echo "<br/>";

$cars = array("BMW", "Mercedes", "Audi", "Volvo");
echo json_encode($cars);


$student1 = '{"name":"kush", "age": 23, "year": 2004}';
echo "<br/>";
var_dump(json_decode($student1, true));

$json = '{"name":"kush", "age":23, "city":"Bhavnagar"}';
echo "<br/>";
$obj = json_decode($json, true);
echo "<br/>";
echo $obj["name"];
echo "<br/>";
echo $obj["age"];
echo "<br/>";
echo $obj["city"];
?>