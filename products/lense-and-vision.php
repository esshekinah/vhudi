<?php
session_start();
require "conn.php";


$access='';
if(isset($_SESSION['access'])){
  $access=$_SESSION['access'];
}else{
    echo '<script> location.replace("../index.php"); </script>';
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



        <table class="art-article" style="width: 100%; "><tbody>
		<tr style="background-color: black; color: white;">
		<td style="width: 30%; ">Item Type</td>
		<td style="width: 30%; ">Item Vision</td>
		<td style="width: 20%; ">Price</td>
		<td style="width: 20%; ">Action</td></tr>
		<?php
				if (isset($_REQUEST['submit']))  {

	$item_type= $_REQUEST['item_type'];
	$item_vision= $_REQUEST['item_vision'];
	$price= $_REQUEST['price'];


	if($item_type=='' || $item_vision=="" || $price==""){
		echo '
		<center><p style="background-color:red">Fill all fields</p></center>
		<form name="form" method="post" style="margin-left: 5%">
		<tr><td style="width: 30%; "><input value="'.$item_type.'" name="item_type" type="text"><br></td>
		<td style="width: 30%; "><input value="'.$item_vision.'" name="item_vision" type="text"><br></td>
		<td style="width: 20%; "><input value="'.$price.'" name="price" type="number"><br></td>
		<td style="width: 20%; "><input type="submit" width="100px" name="submit" value="Add" class="art-button">
				</td></tr>
		</form>';
	}else{
		//insert


		$sql = "
	INSERT INTO `price`(`Item_Type`, `Item_Vision`, `Item_Price`)
	VALUES ('".$item_type."','".$item_vision."','".$price."')
	";


        if ($conn->query($sql) === TRUE) {
			echo '<center><p style="background-color: green">Added Successfully</p></center>';
		echo '<form name="form" method="post" style="margin-left: 5%">
		<tr><td style="width: 30%; "><input name="item_type" type="text"><br></td>
		<td style="width: 30%; "><input name="item_vision" type="text"><br></td>
		<td style="width: 20%; "><input name="price" type="number"><br></td>
		<td style="width: 20%; "><input type="submit" width="100px" name="submit" value="Add" class="art-button">
				</td></tr>
		</form>';

		}else{
			echo mysql_errno($conn) . ": " . mysql_error($conn) . "\n";
		}

	}
				}else{
					echo '<form name="form" method="post" style="margin-left: 5%">
		<tr><td style="width: 30%; "><input name="item_type" type="text"><br></td>
		<td style="width: 30%; "><input name="item_vision" type="text"><br></td>
		<td style="width: 20%; "><input name="price" type="number"><br></td>
		<td style="width: 20%; "><input type="submit" width="100px" name="submit" value="Add" class="art-button">
				</td></tr>
		</form>';
				}



		$sql = "SELECT * FROM `price` WHERE 1";
 $res = mysqli_query($conn,$sql);



				while($row = mysqli_fetch_array($res)){

					echo '<tr style="background-color: black; color: white; opacity: 0.7;">

		<td style="width: 50%; ">'.$row['Item_Type'].'</td>
		<td style="width: 25%; ">'.$row['Item_Vision'].'</td>
		<td style="width: 25%; ">'.$row['Item_Price'].'</td>
		<td style="width: 25%; ">

		<select class="combo" onchange="location=this.value">
				<option value="#">Action</option>
				<option value="lense-and-vision_update.php?id='.$row['Item_No'].'&action=update">Edit</option>
				<option value="lense-and-vision_update.php?id='.$row['Item_No'].'&action=remove">Remove</option>




				</select>
		</td>
		</tr>';


				}


		?>



		</tbody></table>
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
