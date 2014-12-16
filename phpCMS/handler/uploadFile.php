<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

include($_SERVER['DOCUMENT_ROOT'] . '/phpCMS/library/library.php');
define("UPLOAD_DIR", $_SERVER['DOCUMENT_ROOT'] . "/phpCMS/uploads/");

echo "<pre>";
print_r($_FILES);
echo "<pre/>";

if (!empty($_FILES["myFile"])) {
	
	$myFile = $_FILES["myFile"];

	if ($myFile["error"] !== UPLOAD_ERR_OK) {

		echo "An error occurred.";
		exit;
	}

	// verify the file is a GIF, JPEG, or PNG
	$fileType = exif_imagetype($myFile["tmp_name"]);
	$allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
	// use to verify file is PDF
	$mime = "application/pdf; charset=binary";
	if (!in_array($fileType, $allowed)) {

		echo 'File type not permitted';
		exit;
	} elseif (condition) {
		# code...
	}

	// ensure a safe filename
	$name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

	// don't overwrite an existing file
	$i = 0;
	$parts = pathinfo($name);
	
	while (file_exists(UPLOAD_DIR . $name)) {
		$i++;
		$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
	}

	if ( !file_exists(UPLOAD_DIR) ) {
		
		mkdir(UPLOAD_DIR, 0775);
	}
	
	// preserve file from temporary directory
	$success = move_uploaded_file($myFile["tmp_name"], UPLOAD_DIR . $name);
	if (!$success) { 
		echo "Unable to save file.";
		exit;
	}

	// set proper permissions on the new file
	chmod(UPLOAD_DIR . $name, 0644);

	header('location: /phpCMS/');
}
?>