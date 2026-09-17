<?php

//callback function
function mySqare($n)
{

    return ($n * $n);
}

$number = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

print_r(array_map("mySqare", $number));
echo "<br/>";

//Anonymous Function
$result = array_map(function ($n) {
    return ($n + $n);
}, $number);
print_r($result);

?>