<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user bookings
$stmt = $conn->prepare("SELECT ticket_id, booking_date, status, amount_paid, vehicle_number, time_in, time_out, total_hours FROM bookings WHERE user_id = ? ORDER BY booking_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 CSS + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 1000px;
            margin-top: 40px;
            padding: 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        h2 {
            color: #0d6efd;
            margin-bottom: 25px;
            font-weight: bold;
        }
        table {
            border-radius: 12px;
            overflow: hidden;
        }
        th {
            background: #0d6efd;
            color: white;
        }
        .btn-back {
            margin-top: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-history"></i> Your Booking History</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Amount Paid</th>
                    <th>Vehicle Number</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['ticket_id']) ?></td>
                    <td><?= htmlspecialchars($row['booking_date']) ?></td>
                    <td><span class="badge bg-<?php echo $row['status'] == 'Confirmed' ? 'success' : 'secondary'; ?>">
                        <?= htmlspecialchars($row['status']) ?>
                    </span></td>
                    <td>₹<?= htmlspecialchars($row['amount_paid']) ?></td>
                    <td><?= htmlspecialchars($row['vehicle_number']) ?></td>
                    <td><?= htmlspecialchars($row['time_in']) ?></td>
                    <td><?= htmlspecialchars($row['time_out']) ?></td>
                    <td><?= htmlspecialchars($row['total_hours']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">You have no bookings yet.</div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-outline-primary btn-back">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
