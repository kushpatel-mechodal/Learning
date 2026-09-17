<?php
//Array

$car_companies = array("BMW", "TATA", "Range Rover", "Toyota");
var_dump($car_companies);
echo "<br/>";
echo $car_companies[1];
echo "<br/>";
echo $car_companies[1] = "rolls royes";
echo "<br/>";
echo "<br/>";
foreach ($car_companies as $value) {
    echo $value;
    echo "<br/>";

}

//Associative array

$car = array("Brand" => "Range Rover", "Model" => "Velar", "year" => 2026);
var_dump($car);
echo "<br/>";
echo $car["Model"];
echo "<br/>";
echo $car["Model"] = "Sport sv";
echo "<br/>";

foreach ($car as $key => $value) {
    echo $key . "" . $value;
    echo " <br/>";
}

//Add the array item

$country = array("india", "russia", "canada");
array_push($country, "USA", "Germany"); // Add the data in last
var_dump($country);
echo "<br/>";
array_unshift($country, "Netherland", "Switzerland"); // Add the data in first
var_dump($country);
echo "<br/>";
array_splice($country, 2, 0, "Dubai"); //update the data in specific position
var_dump($country);
echo "<br/>";

$country1 = array("india", "russia");
$country2 = array("USA", "Dubai");
$result = array_merge($country1, $country2); //merge two array data
var_dump($result);
echo "<br/>";
echo "<br/>";


//remove the array items
$cars = array("TATA", "Toyota", "Hyundai");
array_splice($cars, 1, 1); //Delete the data in specific index
var_dump($cars);
echo "<br/>";


array_splice($cars, 1, 2); //Delete the data in specific index
var_dump($cars);
echo "<br/>";

unset($cars[1]);
var_dump($cars); //remove the specific index data
echo "<br/>";
echo "<br/>";

$companies = array("First" => "Microsoft", "Second" => "TCS", "Third" => "Accenture", "Fourth" => "Apple");
unset($companies["Second"]); //Delete the specfic index data 
var_dump($companies);
echo "<br/>";

// Remove the item in associative array
$result = array_diff($companies, ["TCS", "Apple"]);
var_dump($result);
echo "<br/>";

$food = array("Pizza", "Burger", "Chinese", "Punjabi");

array_pop($food); //POP is remove the last item
var_dump($food);
echo "<br>";

array_shift($food); //remove the item in first
var_dump($food);
echo "<br>";

//Sorting array

$languages = array("PHP", "React", "Python", "Java", "Flutter", "Swift");

sort($languages); //ascending order to set items
var_dump($languages);
print_r($languages);
echo "<br/>";

$marks = array(87, 65, 50, 98, 89, 40, 100);
sort($marks); //ascending order to set items
var_dump($marks);
print_r($marks);
echo "<br/>";

//Rsort

rsort($languages); //descending order to set items
var_dump($languages);
print_r($languages);
echo "<br/>";

rsort($marks); //descending order to set items
var_dump($marks);
print_r($marks);
echo "<br/>";

//Asort

$result = array("Kush" => "88", "Ajay" => "80", "Jay" => "90");
asort($result);
var_dump($result);
print_r($result);
echo "<br/>";

//Ar sort

arsort($result);
var_dump($result);
print_r($result);
echo "<br/>";

//Kssort

ksort($result);
var_dump($result);
print_r($result);
echo "<br/>";

krsort($result);
var_dump($result);
print_r($result);
echo "<br/>";
echo "<br/>";

//Multidimentional array

$student = array(
    array("Kush", 89, 75), //A
    array("Harsh", 75, 65),//B
    array("Jay", 80, 77),  //C
    array("Jenil", 60, 55), //D
);

print_r($student);
echo "<br/>";

echo "Student is:" . $student[0][0] . " , " . "percentage is: " . $student[0][1] . " , " . "Pecentail." . $student[0][2] . "<br>";
echo "Student is:" . $student[1][0] . " , " . "percentage is: " . $student[1][1] . " , " . "Pecentail." . $student[1][2] . "<br>";
echo "Student is:" . $student[2][0] . " , " . "percentage is: " . $student[2][1] . " , " . "Pecentail." . $student[2][2] . "<br>";
echo "Student is:" . $student[3][0] . " , " . "percentage is: " . $student[3][1] . " , " . "Pecentail." . $student[3][2] . "<br>";

echo "<br/>";
// - Looping Through Multidimensional Arrays

for ($row = 0; $row < 4; $row++) { // array
    //0
    //1
    echo "The Number is: $row";
    for ($col = 0; $col < 3; $col++) { // array of value

        // if ($student[1][0])
        //     continue;
        echo "<li>" . $student[$row][$col] . "</li>";
        //0 0 = kush 
        // 0 1 = 89
        // 0 2 = 75

        //1 0 = harsh
        // 1 1 = 75
        //1 2 = 65

        //2 0 = jay 
        // 2 1 = 80
        //2 2 = 77

        //3 0 = jenil
        // 3 1 = 60
        // 3 2 = 55
    }
    echo "<br/>";
}



for ($row = 0; $row < 3; $row++) {
    // array of value
    //0
    echo "The Number is: $row";
    for ($col = 0; $col < 4; $col++) { //array 
        //0
        echo "<li>" . $student[$col][$row] . "</li>";
        // 0 0 kush
        // 1 0 
    }
    echo "<br/>";
}

// array Funtions

// array_change_key_case()
$result = array("Kush" => "89", "Jay" => "90", "Harsh" => "75");
print_r(array_change_key_case($result, CASE_UPPER));
echo "<br/>";

print_r(array_chunk($food, 1));
echo "<br/>";

$arr = array(
    array(
        "id" => "101",
        "First_Name" => "Kush",
        "Last_Name" => "patel",
    ),
    array(
        "id" => "102",
        "First_Name" => "Jay",
        "Last_Name" => "patel",
    ),
    array(
        "id" => "101",
        "First_Name" => "Harsh",
        "Last_Name" => "patel",
    ),
);

$First_Name = array_column($arr, "First_Name");
print_r($First_Name);

//array_combine

$a = array("Kush", "Jay", "pratik");
$b = array("78", "90", "88");

$result = array_combine($a, $b);
print_r($result);
echo "<br/>";

$a1 = array("Kush", "Jay", "Jay", "pratik");
$c = array_count_values($a1);
print_r($c);


?>