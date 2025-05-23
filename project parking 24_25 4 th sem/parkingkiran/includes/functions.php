<?php
function redirectDashboard($role) {
    if ($role === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit();
}
function getCount($conn, $table) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM $table");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}

?>
