<?php
include 'header.php';

// Handle add city
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $stmt = $conn->prepare("INSERT INTO cities (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM cities WHERE id = $id");
}

// Fetch all cities
$result = $conn->query("SELECT * FROM cities");
?>
<style>
    .card {
  border-radius: 12px;
  border: none;
}

.btn-lg {
  font-size: 1.1rem;
  padding: 0.6rem 1.2rem;
}

input.form-control-lg {
  border-radius: 8px;
  padding: 0.6rem;
}
</style>
<div class="container mt-5">
    <h3 class="mb-4">🏙️ Manage Cities</h3>
    <a href="dashboard.php" class="btn btn-outline-primary mb-4">← Back to Dashboard</a>

    <!-- Add City Form -->
    <div class="card shadow-sm p-4 mb-4">
        <form method="post" class="row g-3 align-items-center">
            <div class="col-md-8">
                <input name="name" class="form-control form-control-lg" placeholder="Enter new city name..." required>
            </div>
            <div class="col-md-4">
                <button name="add" class="btn btn-success btn-lg w-100">➕ Add City</button>
            </div>
        </form>
    </div>

    <!-- City Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 70%;">City Name</th>
                        <th style="width: 20%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>
                                <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this city?')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
