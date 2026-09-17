<!-- post method -->
    <form method="post" action=<?php $_SERVER['PHP_SELF'] ?>>
        Name: <input type="text" name="fname">
        <input type="submit">

        <?php

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $name = htmlspecialchars($_POST['fname']);
            if (empty($name)) {
                echo "<br/>";
                echo "Name is EMPTY";
            } else {
                echo "<br/>";
                echo $name;
            }
        }
        echo "<br/>";
        echo "<br/>";

        ?>
    </form>

    <!-- //get method -->
    <form method="get" action="Welcome.php">
        Name: <input type="text" name=" name"><br />
        Email: <input type="text" name="email">
        <input type="submit">
    </form>