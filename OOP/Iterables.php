<?php

function printiterable(iterable $data)
{

    foreach ($data as $value) {
        echo $value;
    }
}

$result = array("volvo", "<br/>", "bmw", "<br/>", "Range Rover", "<br/>", "mercedecs", "<br/>", "BYD");
printiterable($result);

echo "<br/>";
echo "<br/>";
$iterator = new ArrayIterator(array("TATA", "<br/>", "Mahindra", "<br/>", "MG", "<br/>", "Skoda"));

printiterable($iterator);
?>

<?php

//iterable return type

echo "<br/>";
echo "<br/>";

function getdata(): iterable
{
    return ["TATA", "Mahindra", "Volvo", "BMW", "Jaguar", "Mercedes"];
}

foreach (getdata() as $value) {
    echo $value."<br/>";

}
?>

