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
        //display player photo
        $select_path="SELECT * from stats where id = (SELECT MAX(id) FROM stats)";
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
    	
    <?php 
        $link = mysqli_connect("localhost","root","","mydb");
        //display last generated random image, of this round
        $select_path="SELECT * from stats where id = (SELECT MAX(id) FROM stats)";
        $var=mysqli_query($link, $select_path);
        $row=mysqli_fetch_array($var);
        $rounding = $row["roundnum"];

        if($row){
            $u_name=$row["uname_".$rounding];
            $u_path=$row["upath_".$rounding];
            echo '<p>下面是你面对的第"'.$rounding.'"位公司代表</p>';
            echo '<p><img src="'.$u_path.$u_name.'" alt="'.$u_name.'" style="width:200px;height:200px;"></p>';
        } else{
            echo "nothing";
        }
    ?>
	
    <p><a href="endsend.php">下一步</a></p>
</body>

</html>

<?php
    $link = mysqli_connect("localhost","root","","mydb");

    // Check connection
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
            //get the input invest number
            $invest = mysqli_real_escape_string($link, $_REQUEST['invest']);

            //select record info
            $sql_select="SELECT * from stats where id=(select max(id) from stats)";
            $var=mysqli_query($link, $sql_select);
            $row1=mysqli_fetch_array($var);

            $rounding = $row1["roundnum"];
            //insert invest amount
            $sql = "UPDATE stats set juese='0', invest_".$rounding."='".$invest."', received_".$rounding."='0' where id = '".$row1["ID"]."'";
            if(mysqli_query($link, $sql)){
                echo "Records added successfully.";
            } else{
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
            }   
            
            //display invest amount
            $sqlshow = "SELECT * FROM stats where id='".$row1["ID"]."'";
            if($result = mysqli_query($link, $sqlshow)){
                if(mysqli_num_rows($result) > 0){
                    echo "<table>";
                        echo "<tr>";
                            echo "<th>id</th>";
                            echo "<th>juese</th>";
                            echo "<th>invest</th>";
                        echo "</tr>";
                while($row = mysqli_fetch_array($result)){
                    echo "<tr>";
                        echo "<td>" . $row['ID'] . "</td>";
                        echo "<td>sender</td>";
                        echo "<td>" . $row['invest_'.$rounding] . "</td>";
                    echo "</tr>";
                }
                    echo "</table>";
                    // Free result set (i don't do this a lot...)
                    mysqli_free_result($result);
                } else{
                    echo "No records matching your query were found.";
                }
            } else{
                echo "ERROR: Could not able to execute $sqlshow. " . mysqli_error($link);
            }
        
    // Close connection
    mysqli_close($link);
?>