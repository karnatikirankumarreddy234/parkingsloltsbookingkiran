<?php
include 'header.php';

// Handle Add Location
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city_id = $_POST['city_id'];
    $category_id = $_POST['category_id'];
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];

    $stmt = $conn->prepare("INSERT INTO locations (name, address, city_id, category_id, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiss", $name, $address, $city_id, $category_id, $lat, $lng);
    $stmt->execute();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM locations WHERE id = $id");
}

// Fetch dropdown data
$cities = $conn->query("SELECT * FROM cities");
$categories = $conn->query("SELECT * FROM categories");

// Fetch all locations
$result = $conn->query("SELECT locations.*, cities.name AS city_name, categories.name AS category_name 
                        FROM locations 
                        JOIN cities ON locations.city_id = cities.id 
                        JOIN categories ON locations.category_id = categories.id");
?>
<STYle>
    .card {
  border-radius: 12px;
  border: none;
}

.form-control-lg {
  font-size: 1rem;
  padding: 0.6rem 0.75rem;
  border-radius: 10px;
}

.btn-lg {
  padding: 0.6rem 1.25rem;
  font-size: 1.1rem;
}

.table thead th {
  vertical-align: middle;
  text-align: center;
}

.table td, .table th {
  vertical-align: middle;
}

</STYle>
<div class="container mt-5">
    <h3 class="mb-4">📍 Manage Locations</h3>
    <a href="dashboard.php" class="btn btn-outline-primary mb-4">← Back to Dashboard</a>

    <!-- Add Location Form -->
    <div class="card shadow-sm p-4 mb-4">
        <form method="post">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input name="name" class="form-control form-control-lg" placeholder="Location Name" required>
                </div>
                <div class="col-md-6">
                    <input name="address" class="form-control form-control-lg" placeholder="Address" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <select name="city_id" class="form-control form-control-lg" required>
                        <option value="">Select City</option>
                        <?php while ($c = $cities->fetch_assoc()): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="category_id" class="form-control form-control-lg" required>
                        <option value="">Select Category</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <input name="latitude" class="form-control form-control-lg" placeholder="Latitude" required>
                </div>
                <div class="col-md-4">
                    <input name="longitude" class="form-control form-control-lg" placeholder="Longitude" required>
                </div>
                <div class="col-md-4">
                    <button name="add" class="btn btn-success btn-lg w-100">➕ Add Location</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Locations Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Category</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['city_name']) ?></td>
                            <td><?= htmlspecialchars($row['category_name']) ?></td>
                            <td><?= $row['latitude'] ?></td>
                            <td><?= $row['longitude'] ?></td>
                            <td>
                                <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete location?')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
