<?php

$a[] = "Kush";
$a[] = "Jay";
$a[] = "Vicky";
$a[] = "Jenil";
$a[] = "Jeel";
$a[] = "Henil";
$a[] = "Vivek";
$a[] = "Ajay";
$a[] = "Hiten";
$a[] = "Harsh";
$a[] = "Harshil";

$q = $_REQUEST["q"];

$hint = "";

if($q!== ""){
    $q = strtolower($q);
    $len = strlen($q);

    foreach($a as $name){
        if(stristr($q,substr($name,0,$len))){
            if($hint === ""){
                $hint = $name;
            }else{
                $hint .= ",$name";
            }
        }
    }
}

echo $hint === ""? "no suggestion": $hint;
?>
