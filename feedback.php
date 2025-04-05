<?php
session_start();
include 'connection.php';

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['feedback'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $msg = $_POST['message'];

    // Sanitize input to prevent SQL Injection
    $sanitized_emailid = mysqli_real_escape_string($connection, $email);
    $sanitized_name = mysqli_real_escape_string($connection, $name);
    $sanitized_msg = mysqli_real_escape_string($connection, $msg);

    // Insert feedback into the database
    $query = "INSERT INTO user_feedback(name, email, message) VALUES('$sanitized_name', '$sanitized_emailid', '$sanitized_msg')";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        // Email notification setup
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'varunmcchinthu@gmail.com'; // Your Gmail ID
            $mail->Password = 'uacrivcemeqtrtez'; // Your Gmail App Password
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;

            // Sender & recipient details
            $mail->setFrom('varunmcchinthu@gmail.com', 'Food Donate Support');
            $mail->addReplyTo($sanitized_emailid, $sanitized_name); // Reply to user email
            $mail->addAddress('v9481601768@gmail.com'); // Admin email to receive the feedback

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "New Feedback from $sanitized_name";
            $mail->Body = "
                <h3>New Feedback Received</h3>
                <p><strong>Name:</strong> $sanitized_name</p>
                <p><strong>Email:</strong> $sanitized_emailid</p>
                <p><strong>Message:</strong> $sanitized_msg</p>
            ";

            $mail->send();
            header("Location: contact.html?success=Message sent successfully!");
        } catch (Exception $e) {
            header("Location: contact.html?error=Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    } else {
        header("Location: contact.html?error=Feedback not saved. Please try again.");
    }
}
?>
