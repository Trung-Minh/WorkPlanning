<?php

use Illuminate\Console\View\Components\Alert;

use function Laravel\Prompts\alert;

$user = session('user'); 
$target_dir = "public/";
$newnamefile = $user->ID_USER . $imageFileType; 
$target_file = $target_dir . $newnamefile;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    alert ("File is an image - " . $check["mime"] . ".");
    $uploadOk = 1;
  } else {
    alert( "File is not an image.");
    $uploadOk = 0;
  }
}


// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "ico") {
  alert ("Sorry, only JPG, JPEG, ICO, PNG & GIF files are allowed.");
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  alert("Sorry, your file was not uploaded.") ;
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    alert ("The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.");
  } else {
    alert("Sorry, there was an error uploading your file.");
  }
}
?>
