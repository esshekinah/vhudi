<?php
session_start();
require "conn.php";
$access='';






if(isset($_SESSION['access'])){
    echo '<script> location.replace("clients/all-clients.php"); </script>';
}else{
    echo '<script> location.replace("index.php"); </script>';
}
//

?>


<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.3.0.60745 -->
    <meta charset="utf-8">
    <title>Invoices</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="style.responsive.css" media="all">

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>

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
    <ul class="art-hmenu"><li><a href="invoice.php" class="active">Invoice</a></li><li><a href="clients.php">Clients</a><ul><li><a href="clients/all-clients.php">All Clients</a></li><li><a href="clients/new-client.php">New Client</a></li></ul></li><li><a href="products.php">Products</a><ul><li><a href="products/lense-and-vision.php">Lense and Vision</a></li><li><a href="products/addons.php">Addons</a></li></ul></li><li><a href="logout.php">Logout</a></li></ul>
        </div>
    </nav>
<div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content"><article class="art-post art-article">
                                <h2 class="art-postheader">Invoices</h2>

              <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-content-layout">
    <div class="art-content-layout-row">
    <div class="art-layout-cell" style="width: 100%" >



        <table class="art-article" style="width: 100%; "><tbody><tr style="background-color: black; color: white;">

		<td style="width: 11%; ">Order No</td>
		<td style="width: 11%; ">Practice</td>
		<td style="width: 11%; ">Patient</td>
		<td style="width: 11%; ">Date</td>
		<td style="width: 11%; ">Status</td>
		<td style="width: 12%; ">Total</td>
		<td style="width: 11%; ">Paid</td>
		<td style="width: 11%; ">Due Balance</td>
		<td style="width: 11%; ">Action</td></tr>

		<?php
		function get_total_transaction($order){
			require "conn.php";
		 $sql = "SELECT * FROM `transaction` WHERE `order_id`='".$order."'";
 // OR `Item_No`'".$left."'
				$res = mysqli_query($conn,$sql);

				$total=0;
				while($row = mysqli_fetch_array($res)){

					$total=$total+$row['amount'];

					}



					return $total;
		}
		function get_doctor($pk){
			require "conn.php";
		 $sql = "SELECT * FROM `doctors` WHERE `pk`='".$pk."'";

				$res = mysqli_query($conn,$sql);

				$id='';
				while($row = mysqli_fetch_array($res)){

					$id=$row['surname_intial'];

					}


					return $id;
		}


				function get_total_cost($db_order){
			require "conn.php";
		 $sql = "SELECT * FROM `prescription` WHERE `db_order`='".$db_order."'";
 // OR `Item_No`'".$left."'
				$res = mysqli_query($conn,$sql);

				$total=0;
				while($row = mysqli_fetch_array($res)){

					$total=$total+$row['LEFT'];
					$total=$total+$row['RIGHT'];

					}

					$sql = "SELECT * FROM `addon_clients` WHERE `order_id`='".$db_order."'";
 // OR `Item_No`'".$left."'
				$res = mysqli_query($conn,$sql);

				//$total=0;
				while($row = mysqli_fetch_array($res)){

					$total=$total+$row['price'];


					}




					return $total;
		}

		$sql = "SELECT * FROM `prescription` WHERE 1";
 $res = mysqli_query($conn,$sql);



				while($row = mysqli_fetch_array($res)){
					echo '<tr style="background-color: black; color: white; opacity: 0.7;">
		<td style="width: 12%; ">'.$row['db_order'].'</td>
		<td style="width: 11%; ">'.get_doctor($row['db_practice']).'</td>
		<td style="width: 11%; ">'.$row['db_patient'].'</td>
		<td style="width: 11%; ">'.$row['db_date'].'</td>
		<td style="width: 11%; ">'.$row['db_Rstatus'].'</td>
		<td style="width: 11%; ">R'.get_total_cost($row['db_order']).'</td>
		<td style="width: 11%; ">R'.get_total_transaction($row['db_order']).'</td>
		<td style="width: 11%; ">R'.(get_total_cost($row['db_order'])-get_total_transaction($row['db_order'])).'</td>
		<td style="width: 11%; ">
		<select class="combo" onchange="location=this.value">
				<option value="#">Action</option>
				<option value="transaction.php? id='.$row['db_order'].'">Pay</option>
				<option value="clients/addons2.php? id='.$row['db_order'].'">Addons</option>
				<option value="view_order.php">Collect</option>

				<option value="remove.php">Print</option>



				</select>


		</td>
		</tr>
		';
				}
		?>


		</tbody></table>
    </div>
    </div>
</div>
<div class="art-content-layout">
    <div class="art-content-layout-row">
    <div class="art-layout-cell" style="width: 100%" >
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
