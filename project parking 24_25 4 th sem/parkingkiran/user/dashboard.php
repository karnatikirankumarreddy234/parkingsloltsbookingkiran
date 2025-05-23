<?php
include 'header.php';  // includes session, DB, HTML head + nav

// Fetch dropdown data safely
$cities = $conn->query("SELECT id, name FROM cities ORDER BY name ASC");
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");

$slots_result = null;
$booking_date = "";

// Handle search form submit
if (isset($_POST['search'])) {
    $city_id = intval($_POST['city_id']);
    $category_id = intval($_POST['category_id']);
    $booking_date = $_POST['booking_date'];

    // Validate date format (basic)
    if (DateTime::createFromFormat('Y-m-d', $booking_date) === false) {
        echo '<div class="alert alert-danger">Invalid date format.</div>';
    } else {
        $sql = "
            SELECT slots.id AS slot_id, slots.slot_number, slots.type, 
                   locations.name AS location_name, locations.address
            FROM slots 
            JOIN locations ON slots.location_id = locations.id
            WHERE locations.city_id = ? AND locations.category_id = ?
            AND slots.id NOT IN (
                SELECT slot_id FROM bookings WHERE booking_date = ?
            )
            ORDER BY locations.name, slots.slot_number
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $city_id, $category_id, $booking_date);
        $stmt->execute();
        $slots_result = $stmt->get_result();
    }
}

// Handle booking submission
if (isset($_POST['book_slot'])) {
    $slot_id = intval($_POST['slot_id']);
    $date = $_POST['booking_date'];
    $user_id = $_SESSION['user_id']; // assuming user_id stored in session

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, slot_id, booking_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $slot_id, $date);
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Slot booked successfully!</div>';
        // Redirect to avoid form resubmission
        header("Refresh:2; url=dashboard.php");
    } else {
        echo '<div class="alert alert-danger">Failed to book slot. Try again.</div>';
    }
}
?>

<h3>User Dashboard - Search and Book Slot</h3>

<form method="post" class="mb-4">
    <div class="row g-2">
        <div class="col-md-3">
            <select name="city_id" class="form-select" required>
                <option value="">Select City</option>
                <?php while ($city = $cities->fetch_assoc()): ?>
                    <option value="<?= $city['id'] ?>" <?= (isset($city_id) && $city_id == $city['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($city['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($category_id) && $category_id == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="booking_date" class="form-control" value="<?= htmlspecialchars($booking_date) ?>" required>
        </div>
        <div class="col-md-3 d-grid">
            <button name="search" class="btn btn-primary">Search Slots</button>
        </div>
    </div>

    <h2>Slot Availability Heatmap</h2>
    <div id="map" style="height: 500px; width: 100%; border-radius: 10px;"></div>

<?php
// ✅ Fixing SQL: Removed b.status condition if not present in bookings table
$sql = "
SELECT l.id, l.name, l.latitude, l.longitude,
       COUNT(s.id) AS total_slots,
       COUNT(b.id) AS booked_slots
FROM locations l
LEFT JOIN slots s ON l.id = s.location_id
LEFT JOIN bookings b ON s.id = b.slot_id
GROUP BY l.id;
";

$result = $conn->query($sql);
$locations = [];

while ($row = $result->fetch_assoc()) {
    $row['available'] = $row['total_slots'] - $row['booked_slots'];
    $locations[] = $row;
}
?>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<script>
const locations = <?= json_encode($locations); ?>;

const map = L.map('map').setView([22.3, 70.8], 12); // Default view: Rajkot
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

locations.forEach(loc => {
    const total = loc.total_slots;
    const booked = loc.booked_slots;
    const available = loc.available;
    const ratio = total > 0 ? available / total : 0;

    const color = ratio > 0.75 ? "#2ecc71" :
                  ratio > 0.5  ? "#f1c40f" :
                  ratio > 0.25 ? "#e67e22" :
                                 "#e74c3c";

    L.circleMarker([loc.latitude, loc.longitude], {
        radius: 10,
        fillColor: color,
        color: color,
        weight: 1,
        fillOpacity: 0.8
    }).addTo(map).bindPopup(`
        <b>${loc.name}</b><br>
        Slots: ${available} / ${total}
    `);
});
</script>
</form>

<?php if ($slots_result && $slots_result->num_rows > 0): ?>
    <h5>Available Slots on <?= htmlspecialchars($booking_date) ?>:</h5>
    <table class="table table-striped table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Location</th>
                <th>Address</th>
                <th>Slot Number</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($slot = $slots_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($slot['location_name']) ?></td>
                    <td><?= htmlspecialchars($slot['address']) ?></td>
                    <td><?= htmlspecialchars($slot['slot_number']) ?></td>
                    <td><?= htmlspecialchars($slot['type']) ?></td>
                    <td>

<form action="book_slot.php" method="get">
    <input type="hidden" name="slot_id" value="<?= $slot['slot_id'] ?>">
    <input type="hidden" name="booking_date" value="<?= htmlspecialchars($booking_date) ?>">
    <button class="btn btn-success btn-sm"><i class="fas fa-check-circle"></i> Book Now</button>
</form>


                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php elseif (isset($_POST['search'])): ?>
    <div class="alert alert-info">No slots available for the selected criteria.</div>
<?php endif; ?>

<?php include 'footer.php'; ?>
