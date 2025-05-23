<?php include 'header.php'; ?>

<h2>All Bookings</h2>

<form method="GET" class="filter-form">
    <input type="text" name="search" placeholder="Search by username or location" value="<?= $_GET['search'] ?? '' ?>">
    <select name="status">
        <option value="">All Statuses</option>
        <option value="booked" <?= ($_GET['status'] ?? '') === 'booked' ? 'selected' : '' ?>>Booked</option>
        <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>
    <button type="submit">Filter</button>
</form>

<table>
    <tr>
        <th>#</th><th>User</th><th>Location</th><th>Date</th><th>Slot</th><th>Status</th><th>Action</th>
    </tr>
    <?php
    include '../includes/db.php';
    $search = $_GET['search'] ?? '';
    $status = $_GET['status'] ?? '';
    $sql = "SELECT b.*, u.username, l.name AS location FROM bookings b 
            JOIN users u ON b.user_id = u.id 
            JOIN locations l ON b.location_id = l.id 
            WHERE (u.username LIKE ? OR l.name LIKE ?)";
    $params = ["%$search%", "%$search%"];
    if ($status) {
        $sql .= " AND b.status = ?";
        $params[] = $status;
    }

    $stmt = $conn->prepare($sql);
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    $i = 1;
    while ($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?= $i++ ?></td>
        <td><?= $row['username'] ?></td>
        <td><?= $row['location'] ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['slot'] ?></td>
        <td><?= ucfirst($row['status']) ?></td>
        <td><a href="cancel_booking.php?id=<?= $row['id'] ?>" onclick="return confirm('Cancel this booking?')">Cancel</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'footer.php'; ?>
