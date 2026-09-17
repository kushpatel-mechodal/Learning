<?php
function divide($a, $b)
{

    if ($b === 0) {
        throw new Exception("cannot divide by zero");
    }
    return $a / $b;
}

try {
    echo divide(10, 0);
} catch (Exception $e) {
    $file = $e->getFile();
    $line = $e->getLine();
    $code = $e->getCode();
    $message = $e->getmessage();
    echo $file;
    echo "<br/>";
    echo $line;
    echo "<br/>";
    echo $code;
    echo "<br/>";
    echo $message;
    echo "<br/>";
} finally {
    echo "Divide function is called";
}
?>