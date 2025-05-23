<?php
include 'header.php';

$user_id = $_SESSION['user_id'];
$result = $conn->query("
    SELECT b.*, s.slot_number, l.name AS location_name, l.address, c.name AS category_name
    FROM bookings b
    JOIN slots s ON b.slot_id = s.id
    JOIN locations l ON s.location_id = l.id
    JOIN categories c ON l.category_id = c.id
    WHERE b.user_id = $user_id
    ORDER BY b.booking_date DESC
");

$conn->query("UPDATE bookings SET status='Expired' 
              WHERE booking_date < CURDATE() AND status='Confirmed'");

?>

<h3>My Bookings</h3>
<table class="table table-bordered">
    <thead class="table-primary">
        <tr>
            <th>Ticket ID</th>
            <th>Date</th>
            <th>Location</th>
            <th>Slot</th>
            <th>Vehicle</th>
            <th>Time</th>
            <th>Hours</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['ticket_id'] ?></td>
            <td><?= $row['booking_date'] ?></td>
            <td><?= $row['location_name'] ?>, <?= $row['address'] ?> (<?= $row['category_name'] ?>)</td>
            <td><?= $row['slot_number'] ?></td>
            <td><?= $row['vehicle_number'] ?></td>
            <td><?= $row['time_in'] ?> - <?= $row['time_out'] ?></td>
            <td><?= $row['total_hours'] ?> hrs</td>
            <td>₹<?= $row['amount_paid'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>
