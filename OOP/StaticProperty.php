<?php

class pi
{
    public static $value = 3.14;
}

class ans extends pi
{
    public function staticvalue()
    {
        return parent::$value;
    }
}

$ans = new ans();
echo $ans->staticvalue();
?>