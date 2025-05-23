<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

include 'header.php';
include '../includes/db.php';
include '../includes/functions.php';

// Optional: define variables if needed later
$totalCities = getCount($conn, 'cities');
$totalCategories = getCount($conn, 'categories');
$totalLocations = getCount($conn, 'locations');
$totalSlots = getCount($conn, 'slots');
$totalBookings = getCount($conn, 'bookings');
?>

<div class="admin-dashboard">
    <h1>Welcome to Admin Dashboard</h1>

    <h2>Admin Dashboard</h2>
    <ul class="list-group">
        <li class="list-group-item"><a href="manage_cities.php">Manage Cities</a></li>
        <li class="list-group-item"><a href="manage_categories.php">Manage Categories</a></li>
        <li class="list-group-item"><a href="manage_locations.php">Manage Locations</a></li>
        <li class="list-group-item"><a href="manage_slots.php">Manage Slots</a></li>
    </ul>

    <div class="stats">
        <div class="card">Total Cities<br><span><?= $totalCities ?></span></div>
        <div class="card">Total Categories<br><span><?= $totalCategories ?></span></div>
        <div class="card">Total Locations<br><span><?= $totalLocations ?></span></div>
        <div class="card">Total Slots<br><span><?= $totalSlots ?></span></div>
        <div class="card">Total Bookings<br><span><?= $totalBookings ?></span></div>
    </div>

    <h2>Slot Availability Heatmap</h2>
    <div id="map" style="height: 500px; width: 100%; border-radius: 10px;"></div>
    <a href="../logout.php" class="btn btn-danger">Logout</a>

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

<?php include 'footer.php';?>
