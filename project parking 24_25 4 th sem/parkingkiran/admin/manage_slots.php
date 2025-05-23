<?php
include 'header.php';

// Handle Add Slot
if (isset($_POST['add'])) {
    $location_id = $_POST['location_id'];
    $slot_number = $_POST['slot_number'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("INSERT INTO slots (location_id, slot_number, type) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $location_id, $slot_number, $type);
    $stmt->execute();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Check for existing bookings
    $check = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE slot_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        echo "<script>alert('Cannot delete: This slot has associated bookings.');</script>";
    } else {
        $stmt = $conn->prepare("DELETE FROM slots WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

// Fetch all locations
$locations = $conn->query("SELECT locations.*, cities.name AS city_name, categories.name AS category_name 
                            FROM locations 
                            JOIN cities ON locations.city_id = cities.id 
                            JOIN categories ON locations.category_id = categories.id");

// Fetch all slots with location info
$result = $conn->query("SELECT slots.*, locations.name AS location_name, cities.name AS city_name, categories.name AS category_name 
                        FROM slots 
                        JOIN locations ON slots.location_id = locations.id 
                        JOIN cities ON locations.city_id = cities.id 
                        JOIN categories ON locations.category_id = categories.id");
?>
<STYle>/* Container enhancements */
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Heading style */
h3 {
    font-weight: 600;
    color: #343a40;
    margin-bottom: 1.5rem;
}

/* Form container */
form.mb-4 {
    background: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px 25px;
    box-shadow: 0 4px 8px rgb(0 0 0 / 0.05);
}

/* Form labels */
form label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 6px;
}

/* Input and select styling */
form .form-control {
    border-radius: 5px;
    border: 1px solid #ced4da;
    padding: 10px 12px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    font-size: 1rem;
}

form .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 8px rgba(40, 167, 69, 0.25);
    outline: none;
}

/* Button style */
form button.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    font-weight: 600;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

form button.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

/* Table styling */
.table {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgb(0 0 0 / 0.07);
    overflow: hidden;
}

.table thead.thead-dark {
    background-color: #343a40;
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
}

.table th,
.table td {
    vertical-align: middle !important;
    padding: 12px 15px;
    font-size: 0.95rem;
    color: #495057;
}

/* Hover effect for table rows */
.table tbody tr:hover {
    background-color: #e9f5ef;
}

/* Delete button */
.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    font-weight: 600;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

/* Responsive fixes */
@media (max-width: 576px) {
    form .form-row {
        flex-direction: column;
    }

    form .form-group {
        width: 100% !important;
        margin-bottom: 1rem;
    }

    form button.btn-block {
        width: 100%;
    }
}
</STYle>
<h3 class="mb-4">🚗 Manage Parking Slots</h3>
<a href="dashboard.php" class="btn btn-primary mb-3">← Back to Dashboard</a>

<!-- Add Slot Form -->
<form method="post" class="mb-4 p-3 border rounded bg-light">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="location_id">📍 Location</label>
            <select name="location_id" id="location_id" class="form-control" required>
                <option value="">Select Location</option>
                <?php while ($loc = $locations->fetch_assoc()): ?>
                    <option value="<?= $loc['id'] ?>">
                        <?= $loc['name'] ?> (<?= $loc['city_name'] ?> - <?= $loc['category_name'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="slot_number">🔢 Slot Number</label>
            <input type="text" name="slot_number" id="slot_number" class="form-control" placeholder="Eg: A1, B12" required>
        </div>
        <div class="form-group col-md-3">
            <label for="type">🚘 Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="2-wheeler">2-Wheeler</option>
                <option value="4-wheeler">4-Wheeler</option>
            </select>
        </div>
        <div class="form-group col-md-2 d-flex align-items-end">
            <button type="submit" name="add" class="btn btn-success btn-block">➕ Add Slot</button>
        </div>
    </div>
</form>

<!-- Slot Table -->
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
            <tr>
                <th>Slot Number</th>
                <th>Type</th>
                <th>Location</th>
                <th>City</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['slot_number']) ?></td>
                <td><?= htmlspecialchars(ucfirst($row['type'])) ?></td>
                <td><?= htmlspecialchars($row['location_name']) ?></td>
                <td><?= htmlspecialchars($row['city_name']) ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this slot?')">🗑️ Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
