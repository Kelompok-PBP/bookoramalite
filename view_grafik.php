<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<?php
$title = "View Grafik";

// Include your database connection
require_once('./lib/db_login.php');

// Query to get total book data in each category
$totalBookDataQuery = "SELECT c.title AS Category, COUNT(b.isbn) AS TotalBooks
                       FROM category c
                       LEFT JOIN books b ON c.id = b.category_id
                       GROUP BY c.title
                       ORDER BY c.title";

// Query to get total book data that has been ordered in each category
$totalOrderedDataQuery = "SELECT c.title AS Category, SUM(oi.quantity) AS TotalOrderedBooks
                          FROM category c
                          LEFT JOIN books b ON c.id = b.category_id
                          LEFT JOIN order_items oi ON b.isbn = oi.isbn
                          GROUP BY c.title
                          ORDER BY c.title";

// Execute the queries
$totalBookDataResult = $db->query($totalBookDataQuery);
$totalOrderedDataResult = $db->query($totalOrderedDataQuery);

if (!$totalBookDataResult || !$totalOrderedDataResult) {
    die("Could not query the database: <br />" . $db->error);
}

// Fetch the data for the bar graphs
$categories = [];
$totalBooks = [];
$totalOrderedBooks = [];

while ($bookData = $totalBookDataResult->fetch_object()) {
    $categories[] = $bookData->Category;
    $totalBooks[] = $bookData->TotalBooks;
}

while ($orderedData = $totalOrderedDataResult->fetch_object()) {
    $totalOrderedBooks[] = $orderedData->TotalOrderedBooks;
}

// Close the database connection
$db->close();
?>

<?php include('./layout.php') ?>

<div class="d-flex justify-content-between">
    <div class="card mt-5 mr-3" style="flex: 1; padding: 3px;">
        <div class="card-header">
            <h4>Total Book Data in Each Category</h4>
        </div>
        <div class="card-body">
            <canvas id="totalBooksChart" width="400" height="200"></canvas>
        </div>
    </div>

    <div class="card mt-5" style="flex: 1; padding: 3px;">
        <div class="card-header">
            <h4>Total Book Data That Has Been Ordered in Each Category</h4>
        </div>
        <div class="card-body">
            <canvas id="totalOrderedBooksChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<script>
    // Create the bar graph for Total Book Data in Each Category
    var totalBooksChartCanvas = document.getElementById('totalBooksChart').getContext('2d');
    var totalBooksChart = new Chart(totalBooksChartCanvas, {
        type: 'bar',
        data: {
            labels: <?= json_encode($categories) ?>,
            datasets: [{
                label: 'Total Books',
                data: <?= json_encode($totalBooks) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Create the bar graph for Total Book Data That Has Been Ordered in Each Category
    var totalOrderedBooksChartCanvas = document.getElementById('totalOrderedBooksChart').getContext('2d');
    var totalOrderedBooksChart = new Chart(totalOrderedBooksChartCanvas, {
        type: 'bar',
        data: {
            labels: <?= json_encode($categories) ?>,
            datasets: [{
                label: 'Total Ordered Books',
                data: <?= json_encode($totalOrderedBooks) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
