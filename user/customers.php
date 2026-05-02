<?php
// CSC 235 - Special Assignment: Customer Entity Management
// Developed by: Conrad Powell
require_once('../php/db_connection.php'); 

$message = "";

// Phase 2: Form Handling & Validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic server-side validation to ensure fields aren't just whitespace
    $fName = trim($_POST['firstname']);
    $lName = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $city  = trim($_POST['city']);

    if (!empty($fName) && !empty($lName) && !empty($email)) {
        // Prepared statement for security
        $stmt = $db_connection->prepare("INSERT INTO customers (FirstName, LastName, Email, City) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fName, $lName, $email, $city);
        
        if($stmt->execute()) {
            $message = "<div class='alert alert-success'>New customer record created successfully.</div>";
        } else {
            // Error handling for unique email constraint
            $message = "<div class='alert alert-danger'>Error: Could not add customer. (Check if email already exists)</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning'>Please fill in all required fields.</div>";
    }
}

// Phase 2: Data Retrieval
$query = "SELECT * FROM customers ORDER BY CreatedAt DESC";
$result = $db_connection->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Management - CSC 235</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 20px; }
        .form-section { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body class="container">

    <header class="mb-4">
        <h1>Customer Entity Management</h1>
        <p class="text-muted">Student: Conrad Powell | Course: CSC 235</p>
    </header>

    <?php echo $message; ?>

    <section class="form-section">
        <h3>Add New Customer</h3>
        <form method="POST" action="customers.php" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">First Name *</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Last Name *</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="col-md-8">
                <label class="form-label">Email Address * (Must be unique)</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Save Customer Tuple</button>
            </div>
        </form>
    </section>

    <section class="mt-5">
        <h3>Customer List</h3>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['CustomerID'] . "</td>";
                    echo "<td>" . $row['FirstName'] . " " . $row['LastName'] . "</td>";
                    echo "<td>" . $row['Email'] . "</td>";
                    echo "<td>" . $row['City'] . "</td>";
                    echo "<td>" . $row['Country'] . "</td>";
                    echo "<td>" . $row['CreatedAt'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No customers found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
