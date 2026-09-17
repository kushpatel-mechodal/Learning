<?php


//Default date function
echo "Today Date is: " . date("d/m/Y") . "<br/>";
echo "Today Date is: " . date("d.m.Y") . "<br/>";
echo "Today Date is: " . date("d-m-Y") . "<br/>";
echo "Today is: " . date("l") . "<br/>";

echo date("l, F j,Y") . "<br/>";
echo "<br/>";

//local time date function

echo "The current time is: " . date("H:i:s A") . "<br/>"; //24 hour format
echo "The current time is: " . date("h.i.s a") . "<br/>"; //12 hour format
echo "<br/>";


//Default local date and time 

date_default_timezone_set("Asia/Kolkata");
echo "The current Timezone of india is " . date("d/m/Y h:i:s a");
echo "<br/>";
echo date_default_timezone_get();
echo "<br/>";

//mktime
date_default_timezone_set("UTC");
$d = mktime(0, 0, 0, 8, 6, 2026);
echo "The current 6 aug day is: " . date("l", $d);
echo "<br/>";


$d = strtotime("10:30pm August 1 2026");
echo "Date is: " . date("d-m-Y h:i:s a", $d);
echo "<br/>";
$d = strtotime("now");
echo "Date is: " . date("d-m-Y h:i:s a", $d);
echo "<br/>";
$d = strtotime("+10 days");
echo "Date is: " . date("d-m-Y h:i:s a", $d);
echo "<br/>";
$d = strtotime("last monday");
echo "Date is: " . date("d-m-Y h:i:s a", $d);
?>