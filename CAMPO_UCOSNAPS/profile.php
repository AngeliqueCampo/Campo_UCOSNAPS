<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>

<?php  
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
        }
        .user-info {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .user-info h3 {
            margin: 10px 0;
            color: #333;
        }
        .user-info span {
            color: #007BFF;
        }
        .albums-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .album-card {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 250px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .album-card h3 {
            color: #007BFF;
        }
        .album-card p {
            color: #555;
        }
        .album-card a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        .album-card a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <?php 
        $getUserByID = getUserByID($pdo, $_GET['username']); 
        ?>
        <div class="user-info">
            <h3>Username: <span><?php echo htmlspecialchars($getUserByID['username']); ?></span></h3>
            <h3>First Name: <span><?php echo htmlspecialchars($getUserByID['first_name']); ?></span></h3>
            <h3>Last Name: <span><?php echo htmlspecialchars($getUserByID['last_name']); ?></span></h3>
            <h3>Date Joined: <span><?php echo htmlspecialchars($getUserByID['date_added']); ?></span></h3>
        </div>

        <?php 
        $getAllAlbums = getAllAlbums($pdo, $_GET['username']); 
        if (empty($getAllAlbums)) {
            echo '<p style="text-align: center; margin-top: 20px;">No albums to display.</p>';
        } else {
        ?>
            <div class="albums-container">
                <?php foreach ($getAllAlbums as $album) { ?>
                    <div class="album-card">
                        <h3><?php echo htmlspecialchars($album['album_name']); ?></h3>
                        <p>Created by: <span><?php echo htmlspecialchars($album['username']); ?></span></p>
                        <p><i><?php echo htmlspecialchars($album['date_created']); ?></i></p>
                        <a href="viewalbum.php?album_id=<?php echo $album['album_id']; ?>">View Album</a>
                        <?php if ($_SESSION['username'] === $album['username']) { ?>
                            <a href="editalbum.php?album_id=<?php echo $album['album_id']; ?>">Edit</a>
                            <a href="deletealbum.php?album_id=<?php echo $album['album_id']; ?>">Delete</a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php 
        } 
        ?>
    </div>
</body>
</html>
