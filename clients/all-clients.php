<?php
session_start();
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
                                <h2 class="art-postheader">All Clients</h2>

                <div class="art-postcontent art-postcontent-0 clearfix"><table class="art-article" style="width: 100%; "><tbody>

				<tr style="background-color: black; color: white;"><td style="width: 15%; "><span style="font-size: 18px; ">Surname</span><br></td><td style="width: 15%; ">
				<span style="font-size: 18px; ">Names</span><br></td><td style="width: 14%; "><span style="font-size: 18px; ">Initials</span>
				<br></td><td style="width: 14%; "><span style="font-size: 18px; ">Id Number</span><br></td><td style="width: 14%; ">
				<span style="font-size: 18px; ">Cell Number</span><br></td><td style="width: 14%; "><span style="font-size: 18px; ">Email</span>
				<br></td><td style="width: 14%; "><span style="font-size: 18px; ">Action</span></td></tr>




				<?php

				$sql = "SELECT * FROM `doctors` WHERE 1";
 $res = mysqli_query($conn,$sql);



				while($row = mysqli_fetch_array($res)){

					echo '<tr style="background-color: black; color: white; opacity: 0.7;">
				<td style="width: 15%; ">'.$row['surname'].'</td>
				<td style="width: 15%; ">'.$row['names'].'</td>
				<td style="width: 14%; "></td>
				<td style="width: 14%; ">'.$row['id'].'</td>
				<td style="width: 14%; ">'.$row['cell no'].'</td>
				<td style="width: 14%; ">'.$row['email'].'</td>
				<td style="width: 14%; ">

				<select class="combo" onchange="location=this.value">
				<option value="#">Action</option>
				<option value="new_order.php? id='.$row['pk'].'">New Order</option>
        <option value="view_order.php? id='.$row['pk'].'">View Orders</option>
        <option value="update.php? id='.$row['pk'].'">Update Details</option>

        <option value="remove.php">Remove Client</option>



				</select>

				</td>
				</tr>';


				}

				?>



				</tbody></table></div>


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
