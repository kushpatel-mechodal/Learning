<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forms</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php

    $name = $email = $phone = $gender = $website = "";
    $nameErr = $emailErr = $phoneErr = $genderErr = $WebsiteErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameErr = "Name is Required";
        } else {
            $name = input($_POST["name"]);

            if (!preg_match("/^[a-z A-Z]*$/", $name)) {
                $nameErr = "Only letters can be allowed";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Email is Required";
        } else {
            $email = input($_POST["email"]);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $emailErr = "Invalid Email please Enter valid Email";
            }
        }

        if (empty($_POST["website"])) {
            $WebsiteErr = "Website Url is Required";
        } else {
            $website = input($_POST["website"]);

            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
                $WebsiteErr = "Please Enter the Valid Website url";
            }
        }

        if (empty($_POST["phone"])) {
            $phoneErr = "Phone Number is required";
        } else {
            $phone = input($_POST["phone"]);

            if (!preg_match("/^[6-9][0-9]{9}$/", $phone)) {
                $phoneErr = "Enter the valid phone number";
            }
        }

        if (empty($_POST["gender"])) {
            $genderErr = "Select your Gender";
        } else {
            $gender = input($_POST["gender"]);
        }
    }

    //No validation errors
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($nameErr == "" && $emailErr == "" && $WebsiteErr = "" && $phoneErr == "" && $genderErr == "") {
            include "Display.php";
            exit();
        }
    }

    function input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        return $data;
    }

    ?>
    <form method="post" action="">
        name <input type="text" name="name" value="<?php echo $name; ?>"><span class="error"> *
            <?php echo $nameErr; ?></span><br>
        email <input type="email" name="email" value="<?php echo $email; ?>"><span class="error">*
            <?php echo $emailErr; ?></span><br>
        Website <input type="text" name="website" value="<?php echo $website; ?>"><span
            class="error"><?php echo $WebsiteErr ?></span><br>
        phone <input type="text" name="phone" maxlength="10" value="<?php echo $phone; ?>"><span class="error"> *
            <?php echo $phoneErr; ?></span><br>
        Gender <input type="radio" name="gender" value="male" <?php if (isset($gender) && ($gender == "male"))
            echo "checked"; ?>>Male
        <input type="radio" name="gender" value="female" <?php if (isset($gender) && ($gender == "female"))
            echo "checked"; ?>>Female<span class="error">*<?php echo $genderErr; ?></span><br>
        <button type="submit" name="submit" value="submit">Submit</button>
    </form>


</body>

</html>