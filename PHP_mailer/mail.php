<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require("vendor/autoload.php");

if (isset($_POST["submit"])) {

    $to = $_POST["to"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $mail = new PHPMailer(true); //create phpmailer class object 

    try {

        //SMTP configuration
        $mail->isSMTP(); //use for gmail smtp server
        $mail->Host = "smtp.gmail.com"; //gmail smtp host 
        $mail->SMTPAuth = true; //use for gmail smtp username and password

        //mail username and password
        $mail->Username = "kushpatel3535@gmail.com";
        $mail->Password = "mkkllnhvhxboggmx";

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //sender

        $mail->setFrom(
            "kushpatel3535@gmail.com",
            "PHP mailer"
        );

        //receiver
        $mail->addAddress($to);

        //mail content

        $mail->Subject = $subject; //get form subject data
        $mail->Body  = $message; //get form body 

        //send mail
        $mail->send();

        echo "<script>
            alert('Email Sent Successfully');
            window.location.href = 'mail.php';
        </script>";
        exit;
    } catch (Exception $e) {

        echo "<script>
            alert('Failed to send Email');
            window.location.href = 'mail.php';
        </script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Mailer</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <div class="form-container">
            <h2>PHP Mail Form</h2>
            <form method="post">
                <label for="to">To</label>
                <input type="text" name="to" id="to" placeholder="To.." required>

                <label for="subject">subject</label>
                <input type="text" name="subject" id="subject" placeholder="subject.." required>

                <label for="message">message</label>
                <textarea name="message" id="message" placeholder="message.." required></textarea>

                <button type="submit" name="submit" id="submit">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>