<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
    <input type="text" name="naam" placeholder="naam">
    <input type="text" name="email" placeholder="email">
    <input type="text" name="klacht" placeholder="klacht">
    <input type="submit" name="submit" value="submit">
    </form>
</body>
<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$email = $_POST['email'];
$naam = $_POST['naam'];
$klacht = $_POST['klacht'];

// Load Monolog
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'example@.com';                     //SMTP username
    $mail->Password   = 'password';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('email sender', 'Mailer');
    $mail->addAddress($email, $naam);     //Add a recipient
    $mail->addCC('cc');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'uw klacht is in behandeling';
    $mail->Body    = $klacht;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    // Send the email
    $mail->send();
    echo 'Message has been sent';

    // Log form data to log.txt
    $log = new Logger('klacht');
    $log->pushHandler(new StreamHandler('info.log', Logger::INFO));
    $log->info("Form Data - Name: $naam, Email: $email, Klacht: $klacht");
} catch (Exception $e) {
        // Log the error message
    $log = new Logger('klacht');
    $log->pushHandler(new StreamHandler('info.log', Logger::ERROR));
    $log->error($e->getMessage());
}
?>
</html>
