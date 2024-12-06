<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// check for album_id
if (!isset($_GET['album_id'])) {
    die("Album ID is required!");
}

$album_id = $_GET['album_id'];

// delete album
if (isset($_POST['confirmDelete'])) {
    $deleteStatus = deleteAlbum($pdo, $album_id);

    if ($deleteStatus) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting album.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Album</title>
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
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            justify-content: space-between;
        }
        button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        button.cancel {
            background-color: #6c757d;
        }
        button.cancel:hover {
            background-color: #565e64;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Are you sure to delete this album?</h1>
        <form method="POST">
            <button type="submit" name="confirmDelete">Delete</button>
            <button type="button" class="cancel" onclick="window.location.href='index.php';">Cancel</button>
        </form>
    </div>
</body>
</html>
