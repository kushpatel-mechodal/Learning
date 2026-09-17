<?php

namespace html;

class Table
{
    public $title = "";
    public $numrow = 0;
    public function message()
    {
        echo "<p>Title is. '{$this->title}' has '{$this->numrow}' rows</p>";
    }
}

class Row
{
    public $numcells = 0;
    public function message()
    {
        echo "<p>The row has {$this->numcells} cells. </p>";
    }
}


?>