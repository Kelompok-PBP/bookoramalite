<?php
$title = "Book Details";

// Include your database connection
require_once('./lib/db_login.php');

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $isbn = $db->real_escape_string($_GET['id']);

    // Fetch book details from the 'books' table
    $bookQuery = "SELECT b.isbn AS ID, b.author AS Author, b.title AS Title, b.price AS Price, c.title AS Category
                  FROM books b
                  JOIN category c ON b.category_id = c.id
                  WHERE b.isbn = '$isbn'";

    $bookResult = $db->query($bookQuery);

    if ($bookResult && $bookResult->num_rows > 0) {
        $bookData = $bookResult->fetch_object();

        // Fetch book reviews from the 'book_reviews' table
        $reviewQuery = "SELECT review FROM book_reviews WHERE isbn = '$isbn'";
        $reviewResult = $db->query($reviewQuery);

        if ($reviewResult) {
            $reviews = [];
            while ($reviewRow = $reviewResult->fetch_assoc()) {
                $reviews[] = $reviewRow['review'];
            }

            // Close the review result set
            $reviewResult->free();
        }
    } else {
        die("Book not found.");
    }
} else {
    die("ISBN not provided.");
}

// Close the book result set
$bookResult->free();

// Process form submission to add a new review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    $newReview = $db->real_escape_string($_POST['new_review']);

    // Insert the new review into the 'book_reviews' table with the associated ISBN
    $insertReviewQuery = "INSERT INTO book_reviews (isbn, review) VALUES ('$isbn', '$newReview')";

    if ($db->query($insertReviewQuery)) {
        // Refresh the page after adding the review
        header("Location: detail_book.php?id=$isbn");
        exit;
    } else {
        die("Error adding review: " . $db->error);
    }
}


// Close the database connection
$db->close();
?>

<?php include('./layout.php') ?>

<div class="card mt-5">
    <div class="card-header">
        <h4>Book Details</h4>
    </div>
    <div class="card-body">
        <h5>ISBN: <?= $bookData->ID ?></h5>
        <p><strong>Author:</strong> <?= $bookData->Author ?></p>
        <p><strong>Title:</strong> <?= $bookData->Title ?></p>
        <p><strong>Category:</strong> <?= $bookData->Category ?></p>
        <p><strong>Price:</strong> $<?= $bookData->Price ?></p>

        <h5>Reviews:</h5>
        <?php if (!empty($reviews)) : ?>
            <ul>
                <?php foreach ($reviews as $review) : ?>
                    <li><?= $review ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>No reviews available.</p>
        <?php endif; ?>

        <hr>

        <!-- Add a review form -->
        <form method="POST">
            <div class="form-group">
                <label for="new_review">Add a Review:</label>
                <textarea class="form-control" name="new_review" id="new_review" rows="3" required></textarea>
            </div>
            <button type="submit" name="add_review" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
</div>
