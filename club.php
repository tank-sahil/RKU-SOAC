<?php
include_once('header.php');


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all categories
$categoriesQuery = "SELECT * FROM categories";
$categoriesResult = $conn->query($categoriesQuery);

$categories = [];

if ($categoriesResult->num_rows > 0) {
    while ($category = $categoriesResult->fetch_assoc()) {
        $categoryId = $category['id'];

        // Fetch clubs for this category
        $clubsQuery = "SELECT * FROM club WHERE category_id = $categoryId";
        $clubsResult = $conn->query($clubsQuery);

        $clubs = [];
        if ($clubsResult->num_rows > 0) {
            while ($club = $clubsResult->fetch_assoc()) {
                $clubs[] = $club;
            }
        }

        // Add category and its clubs to the array
        $categories[] = [
            'name' => $category['name'],
            'clubs' => $clubs,
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOAC Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <section id="categories" class="py-3">
        <div class="container text-center">
            <?php foreach ($categories as $category) : ?>
                <h2 class="mb-4" id="<?php echo $category['name']; ?>">- <?php echo $category['name']; ?> -</h2>

                <!-- Check if there are any clubs in this category -->
                <?php if (!empty($category['clubs'])) : ?>
                    <div class="row">
                        <?php foreach ($category['clubs'] as $club) : ?>
                            <div class="col-md-4 my-3">
                                <a class="item" href="club_detail.php?id=<?php echo $club['id']; ?>&name=<?php echo $club['club_name']; ?>">
                                    <div class="card">
                                        <div class="card-img-top" style="height: 150px; background: url('club_images/<?= $club['club_img'] ?>') no-repeat center center; background-size: cover;">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $club['club_name']; ?></h5>
                                            <p class="card-text"><?php echo $club['club_des']; ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>No clubs found under this category.</p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>
</body>

</html>

<?php include_once('footer.php'); ?>