<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="Styles.css">
</head>

<body>
    <!-- <h1>Employee Registration</h1> -->

    <?php

    echo "<h2>Hello World</h2>";
    echo "Hello", "World";

    echo "<br/>";
    echo "<br/>";

    // scalar data types
    $name = "Kush Patel";
    $age = 23;
    $height = 5.915;
    $isActive = true;
    $a = 10;
    $b = 20;


    //var_dump() is return the data type and the value;
    var_dump($name);
    echo "<br/>";
    var_dump($age);
    echo "<br/>";
    var_dump($height);
    echo "<br/>";
    var_dump($isActive);
    echo "<br/>";
    var_dump([2, 3, 45]);
    echo "<br/>";
    echo "<br/>";

    //Global and local scope using function
    
    // Global Scope
    $marks = 85;
    function result()
    {
        global $marks;
        if ($marks >= 85) {
            echo "Pass";
        } else {
            echo "fail";
        }
    }
    result();
    echo "<br/>";
    echo "<br/>";

    //local scope
    
    function result2()
    {
        $number = 90;
        if ($number % 2 == 0) {
            echo "$number even number";
        } else {
            echo "$number odd number";
        }
    }
    result2();
    echo "<br/>";
    echo "<br/>";

    echo "My name is: $name";
    echo "<br/>";

    echo "My age is: " . $age;
    echo "<br/>";

    echo "My height is: " . $height;
    echo "<br/>";
    echo "<br/>";

    // operators
    
    // arithmetic operators
    echo "Addition is: " . $a + $b;
    echo "<br/>";
    echo "Subsctration is: " . $a - $b;
    echo "<br/>";
    echo "Multiplication is: " . $a * $b;
    echo "<br/>";
    echo "Division is: " . $a / $b;
    echo "Modulus is: " . $a % $b;

    echo "";

    echo "<br/>";
    echo "<br/>";

    // Assignment operator
    
    echo "Add the assign: " . $a = $a + $b;
    echo "<br/>";
    echo "Substract the assign: " . $a = $a - $b;
    echo "<br/>";
    echo "Multiplication the assign: " . $a = $a * $b;
    echo "<br/>";
    echo "Division the assign: " . $a = $a / $b;
    echo "<br/>";
    echo "Modulus the assign: " . $a = $a % $b;

    echo "<br/>";
    echo "<br/>";

    // Comparison operator
    
    var_dump($a == $b);
    echo "<br/>";
    var_dump($a === $b);
    echo "<br/>";
    var_dump($a != $b);
    echo "<br/>";
    var_dump($a !== $b);
    echo "<br/>";
    var_dump($a > $b);
    echo "<br/>";
    var_dump($a < $b);
    echo "<br/>";
    var_dump($a >= $b);
    echo "<br/>";
    var_dump($a <= $b);
    echo "<br/>";
    echo "<br/>";


    //logical operators
    
    if ($a == 10 and $b == 20) {
        echo "Hello world";
    }

    echo "<br/>";
    if ($a == 10 or $b == 50) {
        echo "Hello world";
    }
    echo "<br/>";
    if ($a == 10 xor $b == 120) {
        echo "Hello world";
    }
    echo "<br/>";
    if ($a == 10 && $b == 20) {
        echo "Hello world";
    }
    echo "<br/>";
    if (!($a == 100)) {
        echo "Hello world";
    }
    echo "<br/>";
    echo "<br/>";


    //Array operators
    
    $x = array("a" => "red", "b" => "green");
    $y = array("c" => "blue", "d" => "black");

    print_r($x + $y);

    echo "<br/>";

    var_dump($x == $y);
    echo "<br/>";

    var_dump($x === $y);
    echo "<br/>";

    var_dump($x != $y);
    echo "<br/>";

    var_dump($x !== $y);
    echo "<br/>";
    echo "<br/>";



    //Increment and decerement operators
    
    echo ++$a;
    echo "<br/>";
    echo $a++;
    echo "<br/>";
    echo --$b;
    echo "<br/>";
    echo $b--;
    echo "<br/>";
    echo "<br/>";

    for ($x = 0; $x < 10; ++$x) {

        echo "The number is: $x <br>";
    }
    echo "<br/>";

    $x = 0;

    while ($x < 10) {

        echo "The Number is: $x <br>";
        ++$x;
    }
    echo "<br/>";

    $x = 0;

    do {
        echo "The number is $x <br>";
        ++$x;
    } while ($x < 20);
    echo "<br/>";

    //Conditional operators
    
    echo $status = (empty($user)) ? "Unknown" : "Logged in";

    echo "<br/>";

    echo $user = "Kush patel";
    echo "<br/>";
    echo $status = (empty($user)) ? "Unknown" : "Logged in";
    echo "<br/>";


    echo $user = $_GET["user"] ?? "Unknown";
    echo "<br/>";

    echo $color = $color ?? "red";
    echo "<br/>";
    echo "<br/>";

    // Conditional Statement
    if ($isActive === true) {
        echo "Active";
    } else {
        echo "Inactive";
    }

    echo "<br/>";

    $a = 200;
    $b = 150;
    $c = 500;

    if ($a > $b) {
        echo "A is Grether";
    } elseif ($b < $c) {
        echo "B is smaller";
    } else {
        echo "C is Grether";
    }

    echo "<br/>";
    echo "<br/>";

    //shortend if
    $a = 50;

    if ($a < 100)
        $b = "Hello";

    echo $b;

    echo "<br/>";

    //nested if else
    
    $a = 130;

    if ($a > 100) {
        echo "Above 10 ";
        if ($a > 20) {
            echo " and also above 20";
        } else {
            echo " but not above 20";
        }
    }

    echo "<br/>";
    // speical data type
    
    $data = null;
    echo $data;

    // compound data types
    //Object
    
    class student
    {
        public $name = "kush";
    }
    $student = new student();
    echo $student->name;
    echo "<br/>";
    var_dump($student);
    echo "<br/>";

    //Array
    
    $companies = ["Microsoft", "Google", "TCS", "Infosys", "ola", "Uber"];
    echo "Company is: " . $companies[1];
    echo "<br/>";
    echo "<br/>";


    //String Functions
    
    echo strlen("My name is kush patel"); //count total length of string
    echo "<br/>";
    echo str_word_count("I have done"); //count total word of string
    echo "<br/>";

    $txt = "My name is kush";
    echo str_contains($txt, "kush"); //search for the text
    echo "<br/>";

    echo strpos("Kush patel", "patel"); //search for text patel
    echo "<br/>";

    var_dump(str_starts_with($txt, "My name"));//  checks if a string start with a specific substring.
    echo "<br/>";
    var_dump(str_ends_with($txt, "kush"));  //checks if a string ends with a specific substring.
    echo "<br/>";
    //Modify the string
    echo strtoupper($txt); //uppercase string
    echo "<br/>";
    echo strtolower($txt); //lowercase string
    echo "<br/>";
    echo str_replace("kush", "Jay", $txt); //replace string
    echo "<br/>";
    echo strrev($txt);
    echo "<br/>";
    echo trim($txt); //remove the space
    echo "<br/>";

    $x = explode(" ", $txt);
    print_r($x);
    echo "<br/>";

    //Concate the string
    
    $str1 = "PHP";
    $str2 = "Backend";

    echo $str1 . " " . $str2;
    echo "<br/>";

    //Slice the string
    
    echo substr($txt, 3); //specific index to return the string
    echo "<br/>";


    var_dump(is_int($age)); //check the interger
    echo "<br/>";
    var_dump(is_float($height)); //check the float
    echo "<br/>";

    $f = 1.9e4564;
    var_dump(is_infinite($f)); //check the value is infinite or not
    echo "<br/>";

    $x = 8;
    echo $x;
    echo "<br/>";
    echo acos(8);
    // Check if value is not a number (NaN)
    echo is_nan($x);
    echo "<br/>";

    $s1 = 130;
    var_dump(is_numeric($s1)); //return the number
    echo "<br/>";
    $s2 = "12345"; //return the numeric string
    var_dump(is_numeric($s2));
    echo "<br/>";
    $s3 = 89.98;
    echo intval($s3); //convert float into interger
    echo "<br/>";
    $s4 = "56789"; //convert string into interger
    echo intval($s4);
    echo "<br/>";
    echo "<br/>";

    //Type Casting
    // convert data type into one data type to other
    
    //String casting
    
    $name = (string) $name;
    $age = (string) $age;
    $height = (string) $height;

    var_dump($name);
    echo "<br/>";
    var_dump($age);
    echo "<br/>";
    var_dump($height);
    echo "<br/>";
    //integer casting
    
    echo "<br/>";
    $name = (int) $name;
    $age = (int) $age;
    $height = (int) $height;

    var_dump($name);
    echo "<br/>";
    var_dump($age);
    echo "<br/>";
    var_dump($height);
    echo "<br/>";
    //float casting
    
    echo "<br/>";
    $name = (float) $name;
    $age = (float) $age;
    $height = (float) $height;

    var_dump($name);
    echo "<br/>";
    var_dump($age);
    echo "<br/>";
    var_dump($height);
    echo "<br/>";
    //boolean casting
    
    echo "<br/>";
    $name = (bool) $name;
    $age = (bool) $age;
    $height = (bool) $height;

    var_dump($name);
    echo "<br/>";
    var_dump($age);
    echo "<br/>";
    var_dump($height);

    echo "<br/>";

    //Array Casting
    $name = (array) $name;
    $age = (array) $age;
    $height = (array) $height;
    echo "<br/>";

    var_dump($name);
    echo "<br/>";
    var_dump($age);
    echo "<br/>";
    var_dump($height);

    echo "<br/>";
    echo "<br/>";

    //Switch case Statement
    
    $color = "Blue";

    switch ($color) {
        case "Blue":
            echo "Your color is Blue";
            break;

        case "Red":
            echo "Your color is red";
            break;

        case "Green":
            echo "Your color is Greeen";
            break;

        default:
            echo "Your Favorite color is blue and neither the blue,red and green";

    }
    echo "<br/>";
    echo "<br/>";

    // Looping Statement
    
    for ($x = 0; $x <= 10; $x++) {
        echo "The number is: $x";
        echo "<br/>";
    }

    echo "<br/>";

    $x = 15;
    while ($x < 25) {
        echo "The Number is $x";
        $x++;
        echo "<br/>";
    }

    echo "<br/>";

    $y = 5;

    do {
        echo "The number is: $y";
        $y++;
        echo "<br/>";
    } while ($y < 10);
    echo "<br/>";

    $color = array("Red", "Green", "Blue");

    $marks = array("kush" => "100", "Jay" => "150");

    foreach ($color as $value) {
        echo $value;
        echo "<br/>";
    }

    foreach ($marks as $key => $value) {
        echo "$key :$value";
        echo "<br/>";
    }

    echo "<br/>";

    //break statement
    
    for ($x = 0; $x < 10; $x++) {
        if ($x == 4) {
            break;
        }
        echo "The number is $x";
        echo "<br/>";
    }
    echo "<br/>";

    $x = 0;

    while ($x < 10) {
        if ($x == 4) {
            break;
        }
        echo "The number is: $x <br>";
        $x++;
    }

    echo "<br/>";

    $x = 1;
    do {
        if ($x == 4) {
            break;
        }
        echo "The number is: $x <br>";
        $x++;

    } while ($x < 10);

    echo "<br/>";

    foreach ($color as $value) {
        if ($value == "Blue") {
            break;
        }
        echo "$value<br>";
    }
    echo "<br/>";

    // continue Statement
    
    for ($x = 0; $x < 20; $x++) {
        if ($x == 10) {
            break;
        }
        echo "The number is: $x";
        echo "<br/>";

    }
    echo "<br/>";

    $x = 0;

    while ($x < 10) {
        if ($x == 10) {
            continue;
        }
        echo "The number is: $x <br>";
        $x++;
    }
    echo "<br/>";

    $x = 0;
    do {
        if ($x == 10) {
            continue;
        }
        echo "The number is: $x <br>";
        $x++;
    } while ($x < 10);

    echo "<br/>";
    //math 
    echo (pi());
    echo "<br/>";

    echo (min(0, 200, 0, -300)); //return min value
    echo "<br/>";
    echo (max(0, 200, 0, -300)); //return max value
    echo "<br/>";
    echo (abs(-99.88)); //convert to postive value
    echo "<br/>";
    echo (sqrt(144)); //return sqare root
    echo "<br/>";
    echo (round(-100.50)); //round off the value
    echo "<br/>";
    echo (round(99.76));
    echo "<br/>";
    echo (rand(1, 100)); //return random number
    echo "<br/>";


    //constant
    
    define("city", "my city is bhavangar");
    echo city;
    echo "<br/>";
    // constant in function
    
    function myname()
    {
        define("name", "my name is kush patel");
        echo name;
    }

    myname();

    echo "<br/>";
    //using const keyword
    
    const age = "My age is 23 year";
    echo age;
    echo "<br/>";
    define("cars", array("bmw", "mercedes", "Range Rover"));
    echo cars[2];
    echo "<br/>";


    //paraameter in functions
    
    function familyname($name, $year)
    {
        echo "My name is $name and my birthday year is $year";
    }
    familyname("kush", 2004);
    echo "<br/>";
    familyname("Jay", 1999);
    echo "<br/>";
    familyname("jenil", 2000);
    echo "<br/>";

    //Default parameter value
    function setlargerstnumber($number = 100)
    {
        echo "the largest number is a $number <br>";
    }
    setlargerstnumber(1000);
    setlargerstnumber();
    echo "<br/>";
    //returning value
    
    function calculation($a, $b)
    {
        $result = $a + $b;
        return $result;
    }

    echo "100 + 300 = " . calculation(100, 300);
    echo "<br/>";
    echo "340 + 500 = " . calculation(340, 500);
    echo "<br/>";

    //Passing Arguments by Reference
    function addFive(&$value)
    {
        $value += 5;
    }

    $num2 = 10;
    addFive($num2);

    echo "The number is: " . $num2;
    echo "<br/>";
    echo "<br/>";

    // Variable Number of Parameters
    function sumMyNumbers(...$y)
    {
        $sum = 0;
        $len = count($y);
        for ($i = 0; $i < $len; $i++) {
            $sum += $y[$i];
        }
        return $sum;
    }

    $a = sumMyNumbers(10, 5, 20, 45, 79, 90);
    echo $a;

    echo "<br/>";
    function myFamily($lastname, ...$firstname)
    {
        $txt = "";
        $len = count($firstname);
        for ($i = 0; $i < $len; $i++) {
            $txt = $txt . "Hi, $firstname[$i] $lastname.<br>";
        }
        return $txt;
    }

    $a = myFamily("Patel", "kush", "Jenil", "Rahul");
    echo $a;

    echo "<br/>";


    // SuperGlobal
    
    //global keyword
    $x = 100;

    function number()
    {
        echo $GLOBALS['x'];
    }
    number();

    echo "<br/>";
    //using global keyword
    
    $y = 250;

    function number2()
    {
        global $y;
        echo $y;
    }
    number2();

    echo "<br/>";
    echo "<br/>";

    //server keyword
    
    echo $_SERVER["PHP_SELF"];
    echo "<br/>";
    echo $_SERVER["SERVER_NAME"];
    echo "<br/>";
    echo $_SERVER["HTTP_HOST"];
    echo "<br/>";
    echo $_SERVER["HTTP_REFERER"];
    echo "<br/>";
    echo $_SERVER["HTTP_USER_AGENT"];
    echo "<br/>";
    echo $_SERVER["SCRIPT_NAME"];
    echo "<br/>";




    ?>



</body>

</html>