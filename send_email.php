<?php
include("login.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $to_email = $_POST['email'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'varunmcchinthu@gmail.com';
        $mail->Password = 'uacrivcemeqtrtez'; // Use App password
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        $mail->setFrom('v9481601768@gmail.com', 'Food Donate');
        $mail->addAddress($to_email); // Send to user

        $mail->isHTML(true);
        $mail->Subject = "Regarding Your Food Donation";
        $mail->Body = "<p>New Donation Request.</p>";

        $mail->send();
        echo "Email sent successfully.";
    } catch (Exception $e) {
        echo "Failed to send email. Error: {$mail->ErrorInfo}";
    }
}
?>
