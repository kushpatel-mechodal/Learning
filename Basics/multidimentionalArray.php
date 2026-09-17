<?php

$student = array(
    array("kush", 89, 76),
    array("jay", 77, 45),
    array("harsh", 56, 70),
    array("jenil", 55, 66),
    array("jeel", 55, 66),
);

echo "The Student name is: " . " " . $student[0][0] . " " . "The percentage is: " . " " . $student[0][1] . " " . "The percentail is: " . " " . $student[0][2] . "<br/>";
echo "The Student name is:" . " " . $student[1][0] . " " . "The percentage is: " . " " . $student[1][1] . " " . "The percentail is: " . " " . $student[1][2] . "<br/>";
echo "The Student name is: " . " " . $student[2][0] . " " . "The percentage is: " . " " . $student[2][1] . " " . "The percentail is: " . " " . $student[2][2] . "<br/>";
echo "The Student name is: " . " " . $student[3][0] . " " . "The percentage is: " . " " . $student[3][1] . " " . "The percentail is: " . " " . $student[3][2] . "<br/>";
echo "The Student name is: " . " " . $student[4][0] . " " . "The percentage is: " . " " . $student[4][1] . " " . "The percentail is: " . " " . $student[4][2] . "<br/>";
echo "<br/>";

for ($row = 0; $row < 5; $row++) {
    echo "The number is: $row";
    for ($col = 0; $col < 3; $col++) {
        echo "<li>" . $student[$row][$col] . "</li>";
    }
    echo "";
}
?>