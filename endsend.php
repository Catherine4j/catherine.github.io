<!DOCTYPE html>
<html>

<head>
	<title>gameover</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<h1>游戏完成</h1>

    <?php
        $link = mysqli_connect("localhost","root","","mydb");
        //display player own photo
        $select_path="select * from stats where id = (SELECT MAX(id) FROM stats)";
        $var=mysqli_query($link, $select_path);
        if($row=mysqli_fetch_array($var)){
            $image_name=$row["imagename"];
            $image_path=$row["imagepath"];
            $playerid=$row["ID"];
            echo '<p>投资人"'.$playerid.'"你好！恭喜你完成游戏！</p>';
            echo '<p><img src="'.$image_path.$image_name.'" alt="sender" style="width:200px;height:200px;"></p>';
        } else{
            echo "nothing";
        }
    ?>
	<p></p>
	
<?php
    $link = mysqli_connect("localhost","root","","mydb");

    // Check connection
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    //check record selection
    $sql = "SELECT * FROM stats WHERE id = (SELECT MAX(id) FROM stats)";
    if($result = mysqli_query($link, $sql)){

    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    //function to generate random number (returned)
    function randFloat($min = 0, $max = 10) {
      $rand = $min + mt_rand() / mt_getrandmax() * ($max - $min);
      return floatval(number_format($rand,1));
    }
    $row = mysqli_fetch_array($result);
    $rounding = $row["roundnum"];

    echo "玩家(ID=".$row['ID']."):";
    echo "你投资了".$row['invest_'.$rounding]."。";
    //using the random number function
    $ret = randFloat(0,3*$row['invest_'.$rounding]);
    //calculate total revenue
    $tot = 10-$row['invest_'.$rounding]+$ret;
    echo "你收到的返还金额为".$ret."。";
    echo "你的总收益为".$tot."。";

    $tot = mysqli_real_escape_string($link,$tot);//probably unnecessary..?
    //update the database about returned amount and revenue, and round number!!
    $sqlupdate = "UPDATE stats set returned_".$rounding."='".$ret."',total_".$rounding."='".$tot."' where id='".$row['ID']."'";
    mysqli_query($link,$sqlupdate);
    $sqlnewround = "UPDATE stats set roundnum=roundnum+1 where id='".$row['ID']."'";
    mysqli_query($link,$sqlnewround);
    
    //the round number is different, so select again.
    $sqlnewrd = "SELECT * from stats where id = (SELECT MAX(id) FROM stats)";
    $selecting = mysqli_query($link,$sqlnewrd);
    $r = mysqli_fetch_array($selecting);
    $rd = $r["roundnum"];
    //get the new round number, and see where to go
    if ($rd >= 11){
        echo "<p>请联系工作人员领取你的奖励！谢谢你的热心参与！</p>";
        echo '<p><a href="index.php">返回主页</a></p>';
    } else {
        echo '<p>请点击<a href="sender.php">这里</a>，继续下一个游戏</p>';
    }

    // Close connection
    mysqli_close($link);
?>
</body>

</html>