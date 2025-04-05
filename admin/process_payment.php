<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $card_name = $_POST['card_name'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // For real payments, integrate a secure payment gateway API here (e.g., Stripe, PayPal).
    // Simulate payment success for now.
    $payment_success = true;

    if ($payment_success) {
        // Update the database to mark the payment as 'Paid'
        $query = "UPDATE food_donations SET payment_status = 'Paid' WHERE Fid = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $order_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Payment processed and marked as paid!";
        } else {
            $_SESSION['error'] = "Failed to update payment status.";
        }

        $stmt->close();
        $connection->close();
    } else {
        $_SESSION['error'] = "Payment failed. Please try again.";
    }

    // Redirect back to the admin view page
    header("Location: admin_delivery_view.php");
    exit();
} else {
    // Redirect if accessed without POST method
    header("Location: admin_delivery_view.php");
    exit();
}
?>
