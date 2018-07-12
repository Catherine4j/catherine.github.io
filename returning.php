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
        //display player photo
        $select_path="SELECT * from stats where id = (SELECT MAX(id) FROM stats)";
        $var=mysqli_query($link, $select_path);
        if($row=mysqli_fetch_array($var)){
            $image_name=$row["imagename"];
            $image_path=$row["imagepath"];
            $playerid=$row["ID"];
            echo '<p>公司代表"'.$playerid.'"你好！</p>';
            //receiver photo
            echo '<p><img src="'.$image_path.$image_name.'" alt="'.$image_name.'" style="width:200px;height:200px;"></p>';
        } else{
            echo "nothing";
        }
    ?>

    <?php 
        $link = mysqli_connect("localhost","root","","mydb");
        //display last generated random image, of this round!!
        $select_path="SELECT * from stats where id = (SELECT MAX(id) FROM stats)";
        $var=mysqli_query($link, $select_path);
        $row=mysqli_fetch_array($var);
        $rounding = $row["roundnum"];

        if($row){
            $u_name=$row["uname_".$rounding];
            $u_path=$row["upath_".$rounding];
            echo '<p>下面是你面对的第"'.$rounding.'"位投资人</p>';
            echo '<p><img src="'.$u_path.$u_name.'" alt="'.$u_name.'" style="width:200px;height:200px;"></p>';
        } else{
            echo "nothing";
        }

        //$receivednum = $row['invest_'.$rounding];
        //$receivemore = 3 * $receivednum;

        //echo '<p>投资人给你的投资金额为：'.$receivednum.'（X）</p>';
        //echo '<p>你现在的资金总额为：'.$receivemore.'（X*3）</p>';
    ?>

	<form action="returning.php" method="post">
		<input type="hidden" name="returned"> <br>
		<br>
		<input type="hidden" value="提交"> 
	</form>
	<p><a href="endrcv.php">下一步</a></p>
</body>

</html>

<?php
    $link = mysqli_connect("localhost","root","","mydb");

    // Check connection
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    //get input returned amount
    $returned = mysqli_real_escape_string($link, $_REQUEST['returned']);
    
    //mainly to get the record id and round
    $sql_select="SELECT * from stats where id=(select max(id) from stats)";
    $var=mysqli_query($link, $sql_select);
    $row1=mysqli_fetch_array($var);

    $rounding = $row1['roundnum'];
    //so that we can update record
    $sql = "UPDATE stats set returned_".$rounding."='".$returned."' where id = '".$row1["ID"]."'";
    if(mysqli_query($link, $sql)){
        echo "Records added successfully.";
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

    //display updated record
    $sqlshow = "SELECT * FROM stats where id='".$row1["ID"]."'";
    if($result = mysqli_query($link, $sqlshow)){
        if(mysqli_num_rows($result) > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>id</th>";
                    echo "<th>juese</th>";
                    echo "<th>received</th>";
                    echo "<th>returned</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['ID'] . "</td>";
                    echo "<td>receiver</td>";
                    $have = 3 * $row['received_'.$rounding];
                    echo "<td>".$have."</td>"; //display the tripled amount seems better?
                    echo "<td>" . $row['returned_'.$rounding] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            // Free result set
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