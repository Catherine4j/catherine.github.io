<?php
$link = mysqli_connect("localhost","root","","mydb");
//select record
$sql_select="SELECT * from stats where id=(select max(id) from stats)";
$var1=mysqli_query($link, $sql_select);
$row1=mysqli_fetch_array($var1);
// get round number
$rounding = $row1["roundnum"];
//select info from outimage table and randomly display one picture 
$select_image="SELECT * FROM outimage AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(imageID) FROM outimage)-(SELECT MIN(imageID) FROM outimage))+(SELECT MIN(imageID) FROM outimage)) AS imageID) AS t2 WHERE t1.imageID >= t2.imageID ORDER BY t1.imageID LIMIT 1";
$var=mysqli_query($link,$select_image);
if($row=mysqli_fetch_array($var)){
    $image_name=$row["imagename"];
    $image_path=$row["imagepath"]; //等于pics
    echo '<p>下面是你面对的第"'.$rounding.'"位公司代表</p>';
    echo '<p><img src="'.$image_path.$image_name.'" alt="'.$image_name.'" style="width:200px;height:200px;"></p>';
} else{
    echo "nothing";
}
//往数据表中纪录刚刚随机生成图片的信息, of this round
$update_info="UPDATE stats set upath_".$rounding."='".$image_path."', uname_".$rounding."='".$image_name."' where id = '".$row1["ID"]."' ";
mysqli_query($link, $update_info);
mysqli_close($link);
?>
