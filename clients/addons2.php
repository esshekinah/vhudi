<?php
session_start();
require "conn.php";

$id=$_GET['id'];
$id5=$_GET['id_doc'];


require "conn.php";




$access='';
if(isset($_SESSION['access'])){
  $access=$_SESSION['access'];
}else{
    echo '<script> location.replace("../index.php"); </script>';
}

?>


<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.3.0.60745 -->
    <meta charset="utf-8">
    <title>All Clients</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="../style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="../style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="../style.responsive.css" media="all">

<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <script src="../jquery.js"></script>
    <script src="../script.js"></script>
    <script src="../script.responsive.js"></script>

<style>
.setlistmain{
 padding: 0;
 color:black;
  width:100%;
  margin-right: -4px;
  margin-bottom: -4px;
  list-style-type: none;
  font-size:22px;
  background: gray;
}
.setlist{


}
.setlist:hover{
background: brown;
}
.combo{
	background: black;
	width:100%;
	color:white;
	height:25px;
	font-size:14px;

}
.combo:hover{
	background: black;
}

</style>
</head>
<body>
<div id="art-main">
    <div id="art-hmenu-bg" class="art-bar art-nav">
    </div>
<header class="art-header">

    <div class="art-shapes">
        <div class="art-object747075395"></div>

            </div>

<h1 class="art-headline">
    <a href="/">Vhudi Optical Dispensing</a>
</h1>







</header>
<div class="art-sheet clearfix">
<nav class="art-nav">
    <div class="art-nav-inner">
    <ul class="art-hmenu"><li><a href="../invoice.php" class="">Invoice</a></li><li><a href="../clients.php" class="active">Clients</a><ul class="active"><li><a href="../clients/all-clients.php" class="active">All Clients</a></li><li><a href="../clients/new-client.php">New Client</a></li></ul></li><li><a href="../products.php">Products</a><ul><li><a href="../products/lense-and-vision.php">Lense and Vision</a></li><li><a href="../products/addons.php">Addons</a></li></ul></li><li><a href="../logout.php">Logout</a></li></ul>
        </div>
    </nav>
<div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content"><article class="art-post art-article">
                                <h2 class="art-postheader">Order No: <?php

								echo $id;
								?></h2>

                <div class="art-postcontent art-postcontent-0 clearfix">



				<table class="art-article" style="width: 100%; "><tbody>
				<tr><td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; "><br></td>

				<td style="width: 60%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

				<?php

					if (isset($_REQUEST['submit']))  {
	$addon=$_REQUEST['addon'];



	$sql = "SELECT * FROM `addon` WHERE `addon_No`='".$addon."'";
 $res = mysqli_query($conn,$sql);


		$despription='';
		$price='';

				while($row = mysqli_fetch_array($res)){
					$despription=$row['addon_Type'];
		$price=$row['addon_Price'];
				}


	//echo $despription.' '.$price;

	$sql = "

	INSERT INTO `addon_clients`(`order_id`, `description`, `price`) VALUES ('".$id."','".$despription."','".$price."')

	";


        if ($conn->query($sql) === TRUE) {
			echo '<br><br><center style="background-color:green">Addons successfully added</center>
			<center><br><br>






			</center>


			';
		}
					}


				if (isset($_REQUEST['submit2']))  {
	$remove_id=$_REQUEST['remove_id'];

	$sql = "

	DELETE FROM `addon_clients` WHERE `id`='".$remove_id."'

	";


        if ($conn->query($sql) === TRUE) {
			echo '<br><br><center style="background-color:green">Addons successfully Removed</center>
			<center><br><br>






			</center>


			';
		}


				}

				?>

				<table class="art-article" style="width: 100%; "><tbody>
				<tr style="background-color: black; color: white;">
				<td style="width: 70%; ">Addons</td><td style="width: 60%; ">Action</td></tr>

				<form name="form" method="post" style="margin-left: 5%">
				<tr style="background-color: green; color: white;">
				<td style="width: 70%; ">

				<?php
				echo '<select name="addon" class="combo">


				';

				 $sql = "SELECT * FROM `addon` WHERE 1";
               $res = mysqli_query($conn,$sql);

               while($row = mysqli_fetch_array($res)){
				  echo '<option value="'.$row['addon_No'].'" name="'.$row['addon_No'].'">'.$row['addon_Type'].'  R'.$row['addon_Price'].'</option>';
			   }



			echo '
				</select>';
				?>

				</td>
				<td style="width: 60%; ">&nbsp; <input type="submit" width="100px" name="submit" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;Add &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" class="art-button"></td>
				</tr>

				</form>
				<?php

				 $sql = "SELECT * FROM `addon_clients` WHERE `order_id`='".$id."'";
               $res = mysqli_query($conn,$sql);
			 $total=0;
               while($row = mysqli_fetch_array($res)){
				    $total= $total+$row['price'];
				  echo '
				  <tr style="background-color: black; color: white; opacity: 0.7">
			   <td style="width: 70%; ">'.$row['description'].' R'.$row['price'].'</td>
				<td style="width: 60%; ">
				<form name="form" method="post" style="margin-left: 5%">
				<input name="remove_id" value="'.$row['id'].'" type="hidden">


				&nbsp; <input type="submit" width="100px" name="submit2" value="Remove" class="art-button">
				</form>

				</td>
				</tr>
				  ';
			   }

				?>

				</tbody></table><br></td>
				<td style="width: 30%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; "><br></td></tr></tbody></table>

				<?php
				echo '<center><b>Total: R'.$total.'</b></center>';
				echo '<br><br><center><a class="art-button" href="view_order.php?id='.$id5.'">Goto Orders</a></center><br><br>';
				?>

				</div>


</article></div>
                    </div>
                </div>
            </div>
    </div>
<footer class="art-footer">
  <div class="art-footer-inner">

    <p class="art-page-footer">
        <span id="art-footnote-links">Designed by <a href="http://www.easycloudhosting.co.za" target="_blank">Easy Cloud Hosting</a>.</span>
    </p>
  </div>
</footer>

</div>


</body></html>
