<?php  
require_once 'dbConfig.php';
require_once 'models.php';


if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../login.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);
		$userIDFromDB = $loginQuery['userInfoArray']['user_id'];
		$usernameFromDB = $loginQuery['userInfoArray']['username'];
		$passwordFromDB = $loginQuery['userInfoArray']['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			header("Location: ../index.php");
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['user_id']);
	unset($_SESSION['username']);
	header("Location: ../login.php");
}


if (isset($_POST['insertPhotoBtn'])) {

	$description = $_POST['photoDescription'];

	$fileName = $_FILES['image']['name'];

	$tempFileName = $_FILES['image']['tmp_name'];

	$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

	$uniqueID = sha1(md5(rand(1,9999999)));

	$imageName = $uniqueID.".".$fileExtension;

	// edit photo
	if (isset($_POST['photo_id'])) {
		$photo_id = $_POST['photo_id'];
	}
	else {
		$photo_id = "";
	}

	// saving image 
	$saveImgToDb = insertPhoto($pdo, $imageName, $_SESSION['username'], $description, $photo_id);

	// store image
	if ($saveImgToDb) {

		$folder = "../images/".$imageName;

		if (move_uploaded_file($tempFileName, $folder)) {
			header("Location: ../index.php");
		}
	}

}

if (isset($_POST['deletePhotoBtn'])) {
	$photo_name = $_POST['photo_name'];
	$photo_id = $_POST['photo_id'];
	$deletePhoto = deletePhoto($pdo, $photo_id);

	if ($deletePhoto) {
		unlink("../images/".$photo_name);
		header("Location: ../index.php");
	}

}


if (isset($_POST['createAlbumBtn'])) {
    // getting album name
    $albumName = $_POST['albumName'] ?? '';
    $userName = $_SESSION['username'] ?? ''; 


    $errors = [];

    // validate album name
    if (empty($albumName)) {
        $errors[] = "Album name is required.";
    }
    if (empty($userName)) {
        $errors[] = "User must be logged in to create an album.";
    }

    // create album
    if (empty($errors)) {
        try {
            $albumCreated = insertAlbum($pdo, $albumName, $userName);

            if ($albumCreated) {
                $albumID = getAlbumbyID($pdo, $albumName, $userName);

                header("Location: index.php?success=1&album_id=" . $albumID);
                exit();
            } else {
                $errors[] = "Error creating the album. Please try again.";
            }
        } catch (Exception $e) {
            $errors[] = "An error occurred: " . $e->getMessage();
        }
    }

    // display error
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>" . $error . "</p>";
        }
    }
}

if (isset($_POST['addImagesBtn'])) {
    $albumID = $_POST['albumID'] ?? null;
    $photoDescriptions = $_POST['photoDescriptions'] ?? [];
    $errors = [];

    // check for album ID
    if (empty($albumID)) {
        $errors[] = "Album ID is required.";
    }

    // check for uploaded files 
    if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
        $uploadDir = '../images/'; // Adjust path if needed
        $imageCount = count($_FILES['images']['name']);

        for ($i = 0; $i < $imageCount; $i++) {
            $imageName = $_FILES['images']['name'][$i];
            $imageTmpName = $_FILES['images']['tmp_name'][$i];
            $imageError = $_FILES['images']['error'][$i];
            $newImageName = uniqid('', true) . '.' . pathinfo($imageName, PATHINFO_EXTENSION);
            $uploadPath = $uploadDir . $newImageName;

            // upload image
            if ($imageError === 0) {
                if (move_uploaded_file($imageTmpName, $uploadPath)) {
                    $description = $photoDescriptions[$i] ?? 'No description';
                    if (!insertPhotoToAlbum($pdo, $newImageName, $description, $albumID)) {
                        $errors[] = "Failed to insert image: $imageName.";
                    }
                } else {
                    $errors[] = "Failed to move uploaded file: $imageName.";
                }
            } else {
                $errors[] = "Error uploading file: $imageName.";
            }
        }
    } else {
        $errors[] = "No files uploaded.";
    }

    // show error 
    if (empty($errors)) {
        header("Location: ../index.php?success=1&album_id=" . $albumID);
        exit();
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}

?>

