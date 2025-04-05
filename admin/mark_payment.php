<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $query = "SELECT Fid, name, payment_status FROM food_donations WHERE Fid = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if (!$order || $order['payment_status'] === 'Paid') {
        $_SESSION['error'] = "Invalid or already paid order.";
        header("Location: admin_delivery_view.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Process Payment for Order ID: <?php echo htmlspecialchars($order_id); ?></h1>
        <form method="POST" action="process_payment.php">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">

            <label for="card_name">Cardholder Name:</label>
            <input type="text" id="card_name" name="card_name" required>

            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" maxlength="16" required pattern="\d{16}" placeholder="1234 5678 9012 3456">

            <label for="expiry_date">Expiry Date (MM/YY):</label>
            <input type="text" id="expiry_date" name="expiry_date" maxlength="5" required pattern="\d{2}/\d{2}" placeholder="MM/YY">

            <label for="cvv">CVV:</label>
            <input type="password" id="cvv" name="cvv" maxlength="3" required pattern="\d{3}" placeholder="123">

            <button type="submit">Make Payment</button>
        </form>
    </div>
</body>
</html>
