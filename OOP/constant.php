<?php

class myName
{

    const name = "kush patel";
    const pi = 3.14;
    
}

echo myName::name;
echo "<br/>";
echo myName::pi;
echo "<br/>";
echo "<br/>";
?>

<?php

class MyDetails
{
    const Name = "Kush Patel";
    const Age = "23";
    const profession = "PHP Developer";

    public function data()
    {
        echo self::Name;
        echo "<br/>";
        echo self::Age;
        echo "<br/>";
        echo self::profession;
        echo "<br/>";
    }
}

$myDetails = new Mydetails();
$myDetails->data();
?>