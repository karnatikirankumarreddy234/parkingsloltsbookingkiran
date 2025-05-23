<?php
include 'header.php';
if (!isset($_GET['slot_id']) || !isset($_GET['booking_date'])) {
    echo "Invalid access.";
    exit;
}
$slot_id = intval($_GET['slot_id']);
$booking_date = $_GET['booking_date'];
?>
<h4>Booking Slot Details</h4>
<form method="post" action="confirm_booking.php">
    <input type="hidden" name="slot_id" value="<?= $slot_id ?>">
    <input type="hidden" name="booking_date" value="<?= $booking_date ?>">


    <div class="mb-3">
        <label>Vehicle Number</label>
        <input type="text" name="vehicle_number" class="form-control" required pattern="[A-Z]{2}\d{2}[A-Z]{2}\d{4}" placeholder="e.g., GJ01AB1234">
    </div>
    <div class="mb-3">
        <label>User Name</label>
        <input type="text" name="user_name" class="form-control" required placeholder="Enter your name">
    </div>
    <div class="mb-3">
        <label>Time In</label>
        <input type="time" name="time_in" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Time Out</label>
        <input type="time" name="time_out" class="form-control" required>
    </div>
    <button class="btn btn-primary">Confirm Booking</button>
</form>
<?php include 'footer.php'; ?>
