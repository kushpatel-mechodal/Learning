<?php

class myClass
{
    public static function welcome()
    {
        echo "This is PHP Static method";
    }
}

myClass::welcome();
?>

<?php

class Mycalc
{
    public static function sqare($a, $b)
    {
        return $a * $b;
    }
}

$result = Mycalc::sqare(10, 20);
echo "<br/>";
echo $result;
?>

<?php

class myclass2
{

    public static function welcome2()
    {
        echo "<br/>";
        echo "This is PHP self static method";
    }

    public function __construct()
    {
        self::welcome2();
    }
}

new myclass2();
?>

<?php

class A
{
    public static function welcome()
    {
        echo "Hello World";
    }
}
echo "<br/>";
class B
{
    public function message()
    {
        echo "<br/>";
        A::welcome();
    }
}

$obj = new B();
$obj->message();

?>