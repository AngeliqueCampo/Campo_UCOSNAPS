<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// check for album_id
if (!isset($_GET['album_id'])) {
    die("Album ID is required!");
}

$album_id = $_GET['album_id'];

// get album
$album = getAlbumByID($pdo, $album_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $album_name = $_POST['album_name'];

    // update
    $updateStatus = updateAlbum($pdo, $album_id, $album_name);

    if ($updateStatus) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating album!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        /* Styling for the Edit Album page */
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
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Album</h1>
        <form action="" method="POST">
            <label for="album_name">Album Name:</label>
            <input type="text" name="album_name" value="<?php echo htmlspecialchars($album['album_name']); ?>" required>
            <button type="submit">Update Album</button>
        </form>
    </div>
</body>
</html>
