<?php
$title = "Recap Table";

require_once('./lib/db_login.php');

$query = "SELECT c.title AS Category, COUNT(b.isbn) AS BookCount
          FROM category c
          LEFT JOIN books b ON c.id = b.category_id
          GROUP BY c.title
          ORDER BY c.title";

$result = $db->query($query);

if (!$result) {
    error_log($db->error);
    die('Query failed');
}

$categoryCounts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $categoryCounts[$row['Category']] = $row['BookCount'];
}

$bookQuery = "SELECT c.title AS category, b.isbn AS ISBN, b.title AS Title, b.author AS Author, b.price AS Price
        FROM books b
        JOIN category c ON b.category_id = c.id
        ORDER BY c.title
        ";

$result = $db->query($bookQuery);

if (!$result) {
    error_log($db->error);
    die('Could not query the database');
}

$data = '';
$category = '';

while ($row = $result->fetch_object()) {
    $data .= '<tr>';
    if ($category != $row->category) {
        $category = $row->category;
        $data .= '<td rowspan="' . $categoryCounts[$category] . '">' . htmlspecialchars($row->category) . '</td>';
    }
    $data .= '<td>' . htmlspecialchars($row->ISBN) . '</td>';
    $data .= '<td>' . htmlspecialchars($row->Title) . '</td>';
    $data .= '<td>' . htmlspecialchars($row->Author) . '</td>';
    $data .= '<td>' . htmlspecialchars($row->Price) . '</td>';
    $data .= '</tr>';
}

$db->close();

include('./layout.php');
?>

<div class="card mt-5">
    <div class="card-header">
        <h4>Recap Table</h4>
    </div>
    <table class="table table-striped">
        <tr>
            <th>Category</th>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
        </tr>
        <?= $data ?>
    </table>
</div>