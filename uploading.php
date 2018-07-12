<!DOCTYPE html>
<html>
<head>
    <title>upload image</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>个人页面</h1>
    <p>恭喜你顺利完成小测试！</p>
    <p>请在下方上传你的头像照片，照片将出现在另一位玩家的页面上。</p>
    <form action="uploading.php" method="post" enctype="multipart/form-data">
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
            
            // Verify type of the file
            if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
                if(file_exists("./upload/" . $_FILES["photo"]["name"])){
                    echo $_FILES["photo"]["name"] . " is already exists.";
                } else{ //move the file
                    move_uploaded_file($_FILES["photo"]["tmp_name"], "./upload/" . basename($_FILES["photo"]["name"]));
                    echo "Your file was uploaded successfully.";
                } 
            } else{
                echo "Error: There was a problem uploading your file. Please try again."; 
                }
        } else{
            echo "Error: " . $_FILES["photo"]["error"].".";
            }
    //insert image file information into db
    $folder="./upload/";
    $insert_path="INSERT into stats (imagepath,imagename) value('$folder','$filename')";
    mysqli_query($link, $insert_path);
    //get the image info to display it 
    $select_path="SELECT * from stats where id = (SELECT MAX(id) FROM stats)";
    $var=mysqli_query($link, $select_path);
    $row=mysqli_fetch_array($var);
    if($row)
    {
        $image_name=$row["imagename"];
        $image_path=$row["imagepath"];
        echo '<p><img src="'.$image_path.$image_name.'" alt="'.$image_name.'" style="width:200px;height:200px;"></p>';
        echo '<br>';
        echo '<p>你的游戏ID为“'.$row["ID"].'"</p>';
    } else{
        echo "nothing";
        }
    }
    mysqli_close($link);
?>

    <p>请等待对方玩家完成同样准备工作，随后工作人员将通知你的角色：</p>

    <p><a href="sender.php">我是投资人</a></p>
    <p><a href="receiver.php">我是公司代表</a></p>
</body>
</html>
