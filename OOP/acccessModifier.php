<?php

class fruit
{
    protected $name;

    public function set_details($name)
    {
        $this->name = $name;
    }
}

class Apple extends fruit
{
    public function get_details()
    {
        echo "The name is: " . $this->name;
    }
}

$apple = new Apple();
$apple->set_details("Apple");
$apple->get_details();

?>