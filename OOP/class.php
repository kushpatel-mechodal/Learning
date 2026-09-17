<?php

class student
{

    public $name;
    public $age;

    function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;

    }

    function __destruct()
    {
        echo "Student name is: " . $this->name . "<br/>" . "Age is: " . $this->age . "<br/>";

    }
}

$student1 = new student("kush patel", "23");
$student2 = new student("Jay patel", "27");

// var_dump($student1 instanceof student);

?>