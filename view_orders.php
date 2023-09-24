<?php
$title = "View Orders";

// Include your database connection
require_once('./lib/db_login.php');

// Initialize variables for date filtering
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Validate and sanitize the dates (you may need to implement more robust validation)
$start_date = filter_var($start_date, FILTER_SANITIZE_STRING);
$end_date = filter_var($end_date, FILTER_SANITIZE_STRING);

// Check if both start_date and end_date are set
if (!empty($start_date) && !empty($end_date)) {
    // Fetch orders between the specified date range
    $query = "SELECT o.orderid AS OrderID, c.name AS CustomerName, o.amount AS Amount, o.date AS OrderDate
              FROM orders o
              JOIN customers c ON o.customerid = c.customerid
              WHERE o.date BETWEEN '$start_date' AND '$end_date'
              ORDER BY o.orderid";
} elseif (!empty($start_date)) {
    // Fetch orders after the specified start_date
    $query = "SELECT o.orderid AS OrderID, c.name AS CustomerName, o.amount AS Amount, o.date AS OrderDate
              FROM orders o
              JOIN customers c ON o.customerid = c.customerid
              WHERE o.date >= '$start_date'
              ORDER BY o.orderid";
} elseif (!empty($end_date)) {
    // Fetch orders before the specified end_date
    $query = "SELECT o.orderid AS OrderID, c.name AS CustomerName, o.amount AS Amount, o.date AS OrderDate
              FROM orders o
              JOIN customers c ON o.customerid = c.customerid
              WHERE o.date <= '$end_date'
              ORDER BY o.orderid";
} else {
    // No date filter, fetch all orders
    $query = "SELECT o.orderid AS OrderID, c.name AS CustomerName, o.amount AS Amount, o.date AS OrderDate
              FROM orders o
              JOIN customers c ON o.customerid = c.customerid
              ORDER BY o.orderid";
}

// Execute the query
$result = $db->query($query);

if (!$result) {
    die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
}

// Fetch and display the results
$data = '';
while ($row = $result->fetch_object()) {
    $data .= '<tr>';
    $data .= '<td>' . $row->OrderID . '</td>';
    $data .= '<td>' . $row->CustomerName . '</td>';
    $data .= '<td>' . $row->Amount . '</td>';
    $data .= '<td>' . $row->OrderDate . '</td>';
    $data .= '</tr>';
}

$result->free();

// Close the database connection
$db->close();
?>

<?php include('./layout.php') ?>

<div class="card mt-5">
    <form class="card-header d-flex justify-content-between align-items-center" method="GET" action="view_orders.php">
        <h4>Orders</h4>
        <div class="d-flex mx-5 gap-1">
            <input name="start_date" type="date" class="form-control" placeholder="Start Date" aria-label="Start Date" value="<?= isset($_GET['start_date']) ? htmlentities($_GET['start_date']) : '' ?>">
            <input name="end_date" type="date" class="form-control" placeholder="End Date" aria-label="End Date" value="<?= isset($_GET['end_date']) ? htmlentities($_GET['end_date']) : '' ?>">
        </div>
        <button type="submit" class="btn btn-outline-secondary">Filter</button>
    </form>
    <div class="card-header">
        <h4>Filtered Orders</h4>
    </div>
    <table class="table table-striped">
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Amount</th>
            <th>Order Date</th>
        </tr>
        <?= isset($data) ? $data : '' ?>
    </table>
</div>
