<?php $title = "View Customer" ?>

<?php
// Include our login information
require_once('./lib/db_login.php');

// Initialize an empty array to store the conditions
$conditions = [];

// Check if 'category' is set
if (isset($_GET['category'])) {
    $category = $db->real_escape_string($_GET['category']);
    // Add category condition if it's not an empty string
    if ($category !== '') {
        $conditions[] = "c.id = '$category'";
    }
}

// Check if 'q' is set
if (isset($_GET['q'])) {
    $q = $db->real_escape_string($_GET['q']);
    // Add q condition if it's not an empty string
    if ($q !== '') {
        $conditions[] = "(b.author LIKE '%$q%' OR b.title LIKE '%$q%' OR b.isbn LIKE '%$q%' OR c.title LIKE '%$q%')";
    }
}

// Check if 'min_price' is set
if (isset($_GET['min_price'])) {
    $min_price = $db->real_escape_string($_GET['min_price']);
    // Add minimum price condition if it's not an empty string
    if ($min_price !== '') {
        $conditions[] = "b.price >= '$min_price'";
    }
}

// Check if 'max_price' is set
if (isset($_GET['max_price'])) {
    $max_price = $db->real_escape_string($_GET['max_price']);
    // Add maximum price condition if it's not an empty string
    if ($max_price !== '') {
        $conditions[] = "b.price <= '$max_price'";
    }
}

// Build the final query based on the conditions
if (!empty($conditions)) {
    $where_clause = implode(" AND ", $conditions);
    $query = "SELECT b.isbn AS ID, b.author AS Author, b.title AS Title, b.price as Price, c.title AS Category FROM books b JOIN category c ON b.category_id = c.id WHERE $where_clause ORDER BY b.isbn";
} else {
    // If no conditions are specified, retrieve all records
    $query = "SELECT b.isbn AS ID, b.author AS Author, b.title AS Title, b.price as Price, c.title AS Category FROM books b JOIN category c ON b.category_id = c.id ORDER BY b.isbn";
}


// Execute the query
$result = $db->query($query);

if (!$result) {
    die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
}

// Fetch and display the results
$i = 1;

$data = '';

while ($row = $result->fetch_object()) {
    $data .= '<tr>';
    $data .= '<td>' . $row->ID . '</td>';
    $data .= '<td>' . $row->Author . '</td>';
    $data .= '<td>' . $row->Title . '</td>';
    $data .= '<td>' . $row->Category . '</td>';
    $data .= '<td>' . $row->Price . '</td>';
    $data .= '<td><a class="btn btn-primary btn-sm" href="detail_book.php?id=' . $row->ID . '">Detail</a>&nbsp;<a class="btn btn-warning btn-sm" href="update_book.php?id=' . $row->ID . '">Edit</a>&nbsp;<a class="btn btn-danger btn-sm" href="delete_book.php?id=' . $row->ID . '">Delete</a></td>';
    $data .= '</tr>';
    $i++;
}
$data .= '</table>';
$data .= '<br />';
$data .= 'Total Rows = ' . $result->num_rows;

$result->free();

// Category
// Get the list of categories from the database
$categoryQuery = "SELECT id, title FROM category";
$categoryResult = $db->query($categoryQuery);

if (!$categoryResult) {
    die("Could not fetch categories: <br />" . $db->error);
}

// Create the category select box options
$categoryOptions = ''; // Initialize an empty string to store the category options

while ($categoryRow = $categoryResult->fetch_object()) {
    $categoryId = $categoryRow->id;
    $categoryTitle = $categoryRow->title;

    // Check if the current category matches the one in the URL
    $selected = isset($_GET['category']) && $_GET['category'] == $categoryId ? 'selected' : '';

    // Append the category option to the categoryOptions string
    $categoryOptions .= '<option value="' . $categoryId . '" ' . $selected . '>' . $categoryTitle . '</option>';
}

// Free the category result set
$categoryResult->free();




$db->close();
?>

<?php include('./layout.php') ?>



<div class="card mt-5">
    <form class="card-header d-flex justify-content-between align-items-center">
        <h4>Books</h4>
        <div class="d-flex mx-5 gap-1">
            <input name='min_price' type="number" class="form-control" placeholder="Min Price" aria-label="Min Price" value="<?= isset($_GET['min_price']) ? htmlentities($_GET['min_price']) : '' ?>">
            <input name='max_price' type="number" class="form-control" placeholder="Max Price" aria-label="Max Price" value="<?= isset($_GET['max_price']) ? htmlentities($_GET['max_price']) : '' ?>">
        </div>
        <select name="category" class="form-control w-25">
            <option value="">Select category</option>
            <?= $categoryOptions ?>
        </select>
        <div class="w-25 d-flex">
            <input name='q' type="text" class="form-control" placeholder="search" aria-label="enter keyword" aria-describedby="basic-addon2" value="<?= isset($_GET['q']) ? htmlentities($_GET['q']) : '' ?>">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </div>
        <a href='./create_book.php' class='btn btn-primary'>Tambah Buku</a>
    </form>
    <table class="table table-striped">
        <tr>
            <th>ISBN</th>
            <th>Author</th>
            <th>Title</th>
            <th>Category</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?= $data ?>
    </table>
</div>