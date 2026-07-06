<?php
session_start();
require "conn.php";
$id=$_GET['id'];
$action=$_GET['action'];


$access='';
if(isset($_SESSION['access'])){
  $access=$_SESSION['access'];
}else{
    echo '<script> location.replace("../index.php"); </script>';
}


if($action=='remove'){
    $sql = "DELETE FROM `price` WHERE `Item_No`='".$id."'";


        if ($conn->query($sql) === TRUE){
            echo '<script> location.replace("lense-and-vision.php"); </script>';

        }
}else{



}

?><!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.3.0.60745 -->
    <meta charset="utf-8">
    <title>Lense and Vision</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="../style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="../style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="../style.responsive.css" media="all">

<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <script src="../jquery.js"></script>
    <script src="../script.js"></script>
    <script src="../script.responsive.js"></script>


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
    <ul class="art-hmenu"><li><a href="../invoice.php" class="">Invoice</a></li><li><a href="../clients.php" class="">Clients</a><ul class=""><li><a href="../clients/all-clients.php" class="">All Clients</a></li><li><a href="../clients/new-client.php" class="">New Client</a></li></ul></li><li><a href="../products.php" class="active">Products</a><ul class="active"><li><a href="../products/lense-and-vision.php" class="active">Lense and Vision</a></li><li><a href="../products/addons.php">Addons</a></li></ul></li><li><a href="../logout.php">Logout</a></li></ul>
        </div>
    </nav>
<div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content"><article class="art-post art-article">
                                <h2 class="art-postheader">Lense and Vision</h2>
                 <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-content-layout">
    <div class="art-content-layout-row">
    <div class="art-layout-cell" style="width: 15%" >
        <p><br></p>
    </div><div class="art-layout-cell" style="width: 70%" >




		<?php
				if (isset($_REQUEST['submit']))  {

	$item_type= $_REQUEST['item_type'];
	$item_vision= $_REQUEST['item_vision'];
	$price= $_REQUEST['price'];


	if($item_type=='' || $item_vision=="" || $price==""){
		echo '<table class="art-article" style="width: 100%; "><tbody>
		<tr style="background-color: black; color: white;">
		<td style="width: 30%; ">Item Type</td>
		<td style="width: 30%; ">Item Vision</td>
		<td style="width: 20%; ">Price</td>
		<td style="width: 20%; ">Action</td></tr>
		<center><p style="background-color:red">Fill all fields</p></center>
		<form name="form" method="post" style="margin-left: 5%">
		<tr><td style="width: 30%; "><input value="'.$item_type.'" name="item_type" type="text"><br></td>
		<td style="width: 30%; "><input value="'.$item_vision.'" name="item_vision" type="text"><br></td>
		<td style="width: 20%; "><input value="'.$price.'" name="price" type="number"><br></td>
		<td style="width: 20%; "><input type="submit" width="100px" name="submit" value="Update" class="art-button">
				</td></tr>
		</form> </tbody></table>';
	}else{
		//insert


		$sql = "UPDATE `price` SET `Item_Type`='".$item_type."',`Item_Vision`='".$item_vision."',`Item_Price`='".$price."' WHERE `Item_No`='".$id."'


	";


        if ($conn->query($sql) === TRUE) {

		echo '<center><p style="background-color:green">Successfully Updated</p>
		<a href="lense-and-vision.php? id='.$id.'"  class="art-button" >Done</a></center>';

		}else{
			echo mysql_errno($conn) . ": " . mysql_error($conn) . "\n";
		}

	}
				}else{
					$sql = "SELECT * FROM `price` WHERE `Item_No`='".$id."'";
 $res = mysqli_query($conn,$sql);



				while($row = mysqli_fetch_array($res)){
					echo '
					<table class="art-article" style="width: 100%; "><tbody>
		<tr style="background-color: black; color: white;">
		<td style="width: 30%; ">Item Type</td>
		<td style="width: 30%; ">Item Vision</td>
		<td style="width: 20%; ">Price</td>
		<td style="width: 20%; ">Action</td></tr>
					<form name="form" method="post" style="margin-left: 5%">
		<tr><td style="width: 30%; "><input name="item_type" value="'.$row['Item_Type'].'" type="text"><br></td>
		<td style="width: 30%; "><input name="item_vision" value="'.$row['Item_Vision'].'" type="text"><br></td>
		<td style="width: 20%; "><input name="price" value="'.$row['Item_Price'].'" type="number"><br></td>
		<td style="width: 20%; "><input type="submit" width="100px" name="submit" value="Update" class="art-button">
				</td></tr>
		</form>
		</tbody></table>
		';
				}

				}







		?>




    </div><div class="art-layout-cell" style="width: 15%" >
        <p><br></p>
    </div>
    </div>
</div>
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
