<?php

class employee
{

    public $name;
    public $salary;
    public $profession;

    public function __construct($name, $salary, $profession)
    {
        $this->name = $name;
        $this->salary = $salary;
        $this->profession = $profession;
    }

    public function intro()
    {
        echo "Employee Name is: " . $this->name . "<br/>" . "Salary is: " . $this->salary . "<br/>" . "Employee profession is: " . $this->profession;
    }
}

class display extends employee
{

    public $role;

    public function __construct($name, $salary, $role)
    {
        $this->name = $name;
        $this->salary = $salary;
        $this->role = $role;
    }

    public function intro()
    {
        echo "Employee Name is: " . $this->name . "<br/>" . "Salary is: " . $this->salary . "<br/>" . "Employee profession is: " . $this->role;
    }

}

$display = new display("kush patel", 20000, "web Developer");
$display->intro();
?>