<!DOCTYPE html>
<html>

<head>
	<title>sender</title>
	<meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<h1>我是投资人</h1>
	
	<?php
        $link = mysqli_connect("localhost","root","","mydb");
        //get uploaded photo image and display
        $select_path="select * from stats where id = (SELECT MAX(id) FROM stats)";
        $var=mysqli_query($link, $select_path);
        if($row=mysqli_fetch_array($var)){
            $image_name=$row["imagename"];
            $image_path=$row["imagepath"];
            $playerid=$row["ID"];
            echo '<p>投资人"'.$playerid.'"你好！你现在有10分初始金额</p>';
            echo '<p><img src="'.$image_path.$image_name.'" alt="sender" style="width:200px;height:200px;"></p>';
        } else{
            echo "nothing";
        }
    ?>
    
	<?php include ("randimg.php"); ?>

	<form action="sending.php" method="post" >
		请输入你的投资金额：<input type="number" name="invest" min="0" max="10" step="0.1"> 金额范围为0-10，保留一位小数<br>
		<br>
		<input type="submit" value="提交"> 
	</form>
</body>

</html>