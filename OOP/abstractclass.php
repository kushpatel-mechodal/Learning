<?php

abstract class car
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    abstract public function intro();
}

class BMW extends car
{
    public function intro()
    {
        echo "This car name is: $this->name<br/>";
    }
}

class RangeRover extends car
{
    public function intro()
    {
        echo "This car name is: $this->name";
    }
}

$BMW = new BMW("BMW M5");
$BMW->intro();

$RangeRover = new RangeRover("Range Rover Sport");
$RangeRover->intro();
?>

<?php
abstract class Details
{
    abstract protected function prefixName($name);
}

class child extends Details
{
    public function prefixName($name, $separator = ".")
    {
        if ($name == "kush patel") {
            $prefix = "Mr";
        } else if ($name == "pooja patel") {
            $prefix = "Mrs";
        } else {
            $prefix = "";
        }
        return "$prefix$separator $name";
    }
}
echo "<br/>";
$display = new child;
echo $display->prefixName("kush patel");
echo "<br/>";
echo $display->prefixName("pooja patel");
echo "<br/>";

?>