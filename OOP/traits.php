<?php

trait message
{
    public function msg()
    {
        echo "PHP is Bakend programming language";
        echo "<br/>";
    }

    public function msg2()
    {
        echo "PHP to use on web development";
        echo "<br/>";
    }

    public function msg3()
    {
        echo "PHP to build the web applications";
        echo "<br/>";
    }
}


trait message2
{
    public function msg4()
    {
        echo "Many Real time applications to use";
        echo "<br/>";
    }
}
class data
{
    use message;
}

class data2
{
    use message, message2;
}

$data = new data();
$data->msg();

$data2 = new data2();
$data2->msg2();
$data2->msg3();
$data2->msg4();
?>