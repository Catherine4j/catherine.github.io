<?php
$link = mysqli_connect("localhost","root","","mydb");
//select record
$sql_select="SELECT * from stats where id=(select max(id) from stats)";
$var1=mysqli_query($link, $sql_select);
$row1=mysqli_fetch_array($var1);
// get round number
$rounding = $row1["roundnum"];

//select sender info from stats and randomly display one picture 
$select_image="SELECT * FROM stats AS r1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(ID) FROM stats)-(SELECT MIN(ID) FROM stats))+(SELECT MIN(ID) FROM stats)) AS id) AS r2 WHERE r1.id >= r2.id and juese='0' ORDER BY r1.id ASC LIMIT 1";
//$select_image="SELECT * FROM stats WHERE id >= (SELECT floor( RAND() * ((SELECT MAX(ID) FROM stats)-(SELECT MIN(ID) FROM stats)) + (SELECT MIN(ID) FROM stats))) and juese='0' ORDER BY ID LIMIT 1";
//$select_image="SELECT * FROM stats AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(ID) FROM stats)-(SELECT MIN(ID) FROM stats))+(SELECT MIN(ID) FROM stats)) AS ID) AS t2 WHERE t1.ID >= t2.ID and juese='0' ORDER BY t1.ID LIMIT 1";
$varimg=mysqli_query($link,$select_image);
$rowimg=mysqli_fetch_array($varimg);
if($rowimg){
    $image_name=$rowimg["imagename"];
    $image_path=$rowimg["imagepath"]; //等于upload吧
    echo '<p>下面是你面对的第"'.$rounding.'"位投资人</p>';
    echo '<p><img src="'.$image_path.$image_name.'" alt="'.$image_name.'" style="width:200px;height:200px;"></p>';
} else{
    echo "nothing";
}
//往数据表中纪录刚刚随机生成图片的信息, of this round
$update_info="UPDATE stats set juese='1', invest_".$rounding."='0', upath_".$rounding."='".$image_path."', uname_".$rounding."='".$image_name."' where id = '".$row1["ID"]."' ";
mysqli_query($link, $update_info);

//function to generate random number (i for invest_i)
$num = rand(1,10);
//btw, random investment and received number here, update db
$receivednum = $rowimg['invest_'.$num];
$receivemore = 3 * $receivednum;

echo $rowimg["ID"];
echo "-".$num;
echo '<p>投资人给你的投资金额为：'.$receivednum.'（X）</p>';
echo '<p>你现在的资金总额为：'.$receivemore.'（X*3）</p>';
//insert data into db
$update_info="UPDATE stats set received_".$rounding."='".$receivednum."' where id = '".$row1["ID"]."' ";
mysqli_query($link, $update_info);

echo '<form action="returning.php" method="post">';
echo '请输入你的返还给投资者的金额：<input type="number" name="returned" min="0" max="'.$receivemore.'" step="0.1"> 金额范围为0-3X，保留一位小数<br>';
echo '<br>';
echo '<input type="submit" value="提交"> ';
echo '</form>';

mysqli_close($link);
?>
