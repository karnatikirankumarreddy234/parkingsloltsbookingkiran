<?php
include 'header.php';
include '../includes/db.php';

 // Ensure session is started before using $_SESSION

$user_id = $_SESSION['user_id'];

$slot_id = intval($_POST['slot_id']);
$booking_date = $_POST['booking_date'];
$vehicle_number = $_POST['vehicle_number'];
$user_name = $_POST['user_name'];
$time_in = $_POST['time_in'];
$time_out = $_POST['time_out'];

$rate_per_hour = 20;

// Calculate total hours and amount
$in_time = new DateTime($time_in);
$out_time = new DateTime($time_out);
$hours = $out_time->diff($in_time)->h;
$hours = ($hours == 0) ? 1 : $hours;
$amount = $hours * $rate_per_hour;

$ticket_id = "PKT" . rand(100000, 999999);
$status = 'Confirmed';

// Insert booking into database
$stmt = $conn->prepare("INSERT INTO bookings (user_id, slot_id, booking_date, vehicle_number, time_in, time_out, total_hours, amount_paid, status, ticket_id, user_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissssiisss", $user_id, $slot_id, $booking_date, $vehicle_number, $time_in, $time_out, $hours, $amount, $status, $ticket_id, $user_name);
$stmt->execute();
?>

<div class="card mt-4 p-4">
    <h4 class="text-success">Booking Confirmed!</h4>
    <hr>
    <p><strong>Ticket ID:</strong> <?= htmlspecialchars($ticket_id) ?></p>
    <p><strong>Vehicle Number:</strong> <?= htmlspecialchars($vehicle_number) ?></p>
    <p><strong>User:</strong> <?= htmlspecialchars($user_name) ?></p>
    <p><strong>Booking Date:</strong> <?= htmlspecialchars($booking_date) ?></p>
    <p><strong>Slot Time:</strong> <?= htmlspecialchars($time_in) ?> to <?= htmlspecialchars($time_out) ?></p>
    <p><strong>Total Hours:</strong> <?= $hours ?> hrs</p>
    <p><strong>Rate per Hour:</strong> ₹<?= $rate_per_hour ?></p>
    <p><strong>Amount Paid:</strong> ₹<?= $amount ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($status) ?></p>

    <button onclick="window.print()" class="btn btn-secondary mt-2">🖨️ Print Ticket</button>
</div>

<?php include 'footer.php'; ?>
