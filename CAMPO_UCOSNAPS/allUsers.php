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
    <title>All Users</title>
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
            text-align: center;
        }
        .all-users {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .all-users h1 {
            color: #007BFF;
            margin-bottom: 20px;
        }
        .user-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .user-list li {
            margin: 10px 0;
        }
        .user-list a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .user-list a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="all-users">
            <h1>All Users</h1>
            <ul class="user-list">
                <?php $getAllUsers = getAllUsers($pdo); ?>
                <?php foreach ($getAllUsers as $row) { ?>
                    <li>
                        <a href="profile.php?username=<?php echo htmlspecialchars($row['username']); ?>">
                            <?php echo htmlspecialchars($row['username']); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</body>
</html>
