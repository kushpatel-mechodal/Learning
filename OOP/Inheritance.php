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

    protected function get_data()
    {
        echo "Employee Name is: " . $this->name . "<br/>" . "Salary is: " . $this->salary . "<br/>" . "Employee profession is: " . $this->profession;
    }
}

class display extends employee
{

    public function intro()
    {
        echo "<br/>";
        echo "Hello Everyone, How Are You";
        $this->get_data();
    }

}

$display = new display("kush patel", 20000, "PHP developer");
$display->intro();
?>