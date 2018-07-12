<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Upload Form</title>
</head>
<body>
    <form action="ul.php" method="post" enctype="multipart/form-data">
        <h2>Upload File</h2>
        <label for="fileSelect">Filename:</label>
        <input type="file" name="photo" id="fileSelect">
        <input type="submit" name="submit" value="Upload">
        <p><strong>Note:</strong> Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 20 MB.</p>
    </form>

<?php
$link = mysqli_connect("localhost","root","","mydb");

// Check if the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if file was uploaded without errors
    if(isset($_FILES["photo"])){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
    
        // Verify file size - 20MB maximum
        $maxsize = 20 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("./pics/" . $_FILES["photo"]["name"])){
                echo $_FILES["photo"]["name"] . " is already exists.";
            } else{
                move_uploaded_file($_FILES["photo"]["tmp_name"], "./pics/" . basename($_FILES["photo"]["name"]));
                echo "Your file was uploaded successfully.";
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } else{
        echo "Error: " . $_FILES["photo"]["error"].".";
    } 

}
mysqli_close($link);
?>
</body>
</html>
<!--用来往pics文件夹里传照片，pics文件夹用来存非被试照片-->