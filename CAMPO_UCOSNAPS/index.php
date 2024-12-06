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
	<title>Photo Albums</title>
	<link rel="stylesheet" href="styles/styles.css">
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
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
			margin: 0 auto;
		}
		h2 {
			color: #333;
		}
		.form-container {
			background: #fff;
			padding: 20px;
			border: 1px solid #ddd;
			border-radius: 5px;
			margin-bottom: 20px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}
		.form-container form {
			display: flex;
			flex-direction: column;
			gap: 10px;
		}
		.form-container label {
			font-weight: bold;
			color: #555;
		}
		.form-container input, .form-container select, .form-container textarea {
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			width: 100%;
		}
		.form-container input[type="submit"] {
			background-color: #007BFF;
			color: white;
			cursor: pointer;
			border: none;
			font-weight: bold;
			transition: background-color 0.3s ease;
		}
		.form-container input[type="submit"]:hover {
			background-color: #0056b3;
		}
		.album {
			margin-bottom: 30px;
			text-align: center;
		}
		.album h3 {
			color: #007BFF;
		}
		.album img {
			max-width: 100%;
			height: auto;
			border-radius: 5px;
		}
		.albums-container {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			gap: 20px;
		}
		.album-card {
		    display: flex;
		    flex-direction: column;
		    justify-content: space-between;
		    align-items: center;
		    padding: 15px;
		    background: #fff;
		    border: 1px solid #ddd;
		    border-radius: 5px;
		    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		    width: 250px;
		    text-align: center;
		}

		.album-card a {
			text-decoration: none;
			color: #007BFF;
			font-weight: bold;
		}
		.album-card a:hover {
			text-decoration: underline;
		}
		.album-images img {
		    width: 100%; 
		    height: auto;
		    max-height: 200px; 
		    object-fit: cover; 
		    border-radius: 5px; 
		    margin-bottom: 10px; 
		}
		.album-card {
		    display: flex;
		    flex-direction: column;
		    justify-content: space-between;
		    align-items: center;
		    height: 400px; 
		    overflow: hidden; 
		}
		.album-images {
		    width: 100%;
		    max-height: 200px; 
		    overflow-y: auto; 
		    margin-bottom: 10px; 
		}

	</style>
</head>
<body>
	<?php include 'navbar.php'; ?>

	<div class="container">
		<!-- create album -->
		<div class="form-container">
			<h2>Create New Album</h2>
			<form action="core/handleForms.php" method="POST">
				<label for="albumName">Album Name</label>
				<input type="text" name="albumName" required>
				<input type="submit" name="createAlbumBtn" value="Create Album">
			</form>
		</div>

		<!-- add image -->
		<div class="form-container">
			<h2>Add Images to Album</h2>
			<form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
				<label for="albumID">Select Album</label>
				<select name="albumID" required>
					<?php $albums = getAllAlbums($pdo); ?>
					<?php foreach ($albums as $album) { ?>
						<option value="<?php echo $album['album_id']; ?>"><?php echo $album['album_name']; ?></option>
					<?php } ?>
				</select>
				<label for="photoDescriptions[]">Descriptions</label>
				<textarea name="photoDescriptions[]" placeholder="Description for Image"></textarea>
				<label for="images">Upload Images</label>
				<input type="file" name="images[]" multiple accept="image/*" required>
				<input type="submit" name="addImagesBtn" value="Add Images to Album">
			</form>
		</div>

		<!-- view album -->
		<div class="albums-container">
			<?php foreach ($albums as $album) { ?>
				<div class="album-card">
					<h3><?php echo htmlspecialchars($album['album_name']); ?></h3>
					<p>Created by: <a href="profile.php?username=<?php echo htmlspecialchars($album['username']); ?>"><?php echo htmlspecialchars($album['username']); ?></a></p>
					<p><i><?php echo htmlspecialchars($album['date_created']); ?></i></p>
					<div class="album-images">
					    <?php foreach ($album['photos'] as $image) { ?>
					        <img src="images/<?php echo htmlspecialchars($image['photo_name']); ?>" alt="Album Image">
					        <p><?php echo htmlspecialchars($image['description']); ?></p>
					    <?php } ?>
					</div>
					<?php if ($_SESSION['username'] == $album['username']) { ?>
						<a href="editalbum.php?album_id=<?php echo $album['album_id']; ?>">Edit</a> | 
						<a href="deletealbum.php?album_id=<?php echo $album['album_id']; ?>">Delete</a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
</body>
</html>
