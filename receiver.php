<!DOCTYPE html>
<html>

<head>
	<title>sender</title>
	<meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<h1>我是公司代表</h1>

	<?php
		$link = mysqli_connect("localhost","root","","mydb");
		//display uploaded player photo
        $select_path="SELECT * from stats where id = (SELECT MAX(id) FROM stats)";
        $var=mysqli_query($link, $select_path);
        if($row=mysqli_fetch_array($var)){
            $image_name=$row["imagename"];
            $image_path=$row["imagepath"];
            $playerid=$row["ID"];
            echo '<p>公司代表"'.$playerid.'"你好！</p>';
            echo '<p><img src="'.$image_path.$image_name.'" alt="sender" style="width:200px;height:200px;"></p>';
        } else{
            echo "nothing";
        }
    ?>
	
	<?php include ("randrcv.php"); ?>

</body>

</html>