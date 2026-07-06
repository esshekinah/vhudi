<?php
session_start();
require "conn.php";

$id=$_GET['id'];


$access='';
if(isset($_SESSION['access'])){
  $access=$_SESSION['access'];
}else{
    echo '<script> location.replace("../index.php"); </script>';
}

$total=0;
$paid=0;
$due=0;
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
                                <h2 class="art-postheader">Invoices: Dr <?php
								$sql = "SELECT * FROM `doctors` WHERE `pk`='".$id."'";
 $res = mysqli_query($conn,$sql);



				while($row = mysqli_fetch_array($res)){
					echo $row['surname_intial'];
				}

								echo '</h2>

<a class="art-button" href="new_order.php?id='.$id.'">New Order</a>&nbsp;&nbsp;
<a class="art-button" href="transaction.php?id='.$id.'">Payments</a>
                <div class="art-postcontent art-postcontent-0 clearfix">
';


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

					$total=$total+$row['LEFT']+$row['RIGHT'];
					//$total=$total+$row['RIGHT'];

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

                    if (isset($_REQUEST['search'])){
                        $order_no=$_REQUEST['order_no'];
						$sql = "SELECT * FROM `prescription` WHERE `db_practice`='".$id."' AND `db_order`='".$order_no."' ORDER BY `prescription`.`myCount` DESC LIMIT 0,30";
 $res = mysqli_query($conn,$sql);


echo '
 <form name="form" method="post" style="margin-left: 5%">
    <table class="art-article" style="width: 100%; ">
  <tbody>

  <tr>
  <td style="width: 20%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   <input name="order_no" type="text" value="'.$order_no.'">

  </td>
 <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="search" value="Search" class="art-button">
  </td>

  <td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="from" type="date">
 </td>
<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="to" type="date">
 </td>
<td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="sort" value="Sort" class="art-button">
  </td>

  <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="view_all" value="View All" class="art-button">
  </td>


  </td></tr></tbody></table>
  </form>
  <div>
  <table id="header" style="width: 100%; text-align:center; border: black; table-layout:fixed;">
<tr style="background-color: black; color: white;">

          <td style="width: 8.5%; ">Invoice No</td>
          <td style="width: 11%; ">Order No</td>
      		<td style="width: 11%; ">Practice</td>
		<td style="width: 11%; ">Patient</td>
		<td style="width: 11%; ">Date</td>
		<td style="width: 11%; ">Status</td>
		<td style="width: 8.5%; ">Total</td>
		<td style="width: 8.5%; ">Paid</td>
		<td style="width: 8.5%; ">Due Balance</td>
		<td style="width: 11%; ">Action</td>
    <td style="width: 10px; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; vertical-align: middle; "> </td>
    </tr>
    </table>
    </div><!--end #boundary-->
    <div id="box" style="width:100%; height:350px; overflow-y:auto;border:solid 0px #000; ">
    <table id="tbl-content" style="width:100%;table-layout:fixed;">
     			 <tbody>
';
				$total=0;
$paid=0;
$due=0;
				while($row = mysqli_fetch_array($res)){
          $total=$total+get_total_cost($row['db_order']);
          $paid=$paid+get_total_transaction($row['db_order']);
            $due=$due+(get_total_cost($row['db_order'])-get_total_transaction($row['db_order']));
					echo '<tr style="background-color: black; color: white; opacity: 0.7;">
          <td style="width: 8.5%; ">0'.$row['myCount'].'</td>
          <td style="width: 11%; ">'.$row['db_order'].'</td>
      		<td style="width: 11%; ">'.get_doctor($row['db_practice']).'</td>
		<td style="width: 11%; ">'.$row['db_patient'].'</td>
		<td style="width: 11%; ">'.$row['db_date'].'</td>
		<td style="width: 11%; ">'.$row['db_Rstatus'].'</td>
		<td style="width: 8.5%; ">R'.get_total_cost($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.get_total_transaction($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.(get_total_cost($row['db_order'])-get_total_transaction($row['db_order'])).'</td>
		<td style="width: 11%; ">
		<select class="combo" onchange="location=this.value">
				<option value="#">Action</option>

				<option value="addons2.php? id='.$row['db_order'].'&id_doc='.$id.'">Addons</option>
        <option value="open.php? id2='.$row['myCount'].'&id='.$id.'">Open</option>
        <option value="pdf/examples/pdf.php? id2='.$row['myCount'].'&id='.$id.'" target=_blank>print</option>




				</select>


		</td>
		</tr>
		';
				}
                        echo '</tbody></table></div><!--end #box-->';
                    }
					else if(isset($_REQUEST['view_all'])){

						 $sql = "SELECT * FROM `prescription` WHERE `db_practice`='".$id."' ORDER BY `prescription`.`myCount` DESC LIMIT 0,30";
 $res = mysqli_query($conn,$sql);


echo '
 <form name="form" method="post" style="margin-left: 5%">
    <table class="art-article" style="width: 100%; ">
  <tbody>

  <tr>
  <td style="width: 20%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   <input name="order_no" type="text" placeholder=" Order No">

  </td>
  <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="search" value="Search" class="art-button">
  </td>

  <td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="from" type="date" placeholder="From">
 </td>
<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="to" type="date" placeholder="To">
 </td>
<td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="sort" value="Sort" class="art-button">
  </td>

  <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="view_all" value="View All" class="art-button">
  </td>



  </td></tr></tbody></table>
  </form>
  <div>
  <table id="header" style="width: 100%; text-align:center; border: black; table-layout:fixed;">
<tr style="background-color: black; color: white;">

          <td style="width: 8.5%; ">Invoice No</td>
          <td style="width: 11%; ">Order No</td>
      		<td style="width: 11%; ">Practice</td>
		<td style="width: 11%; ">Patient</td>
		<td style="width: 11%; ">Date</td>
		<td style="width: 11%; ">Status</td>
		<td style="width: 8.5%; ">Total</td>
		<td style="width: 8.5%; ">Paid</td>
		<td style="width: 8.5%; ">Due Balance</td>
		<td style="width: 11%; ">Action</td>
    <td style="width: 10px; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; vertical-align: middle; "> </td>
    </tr>
    </table>
    </div><!--end #boundary-->
    <div id="box" style="width:100%; height:350px; overflow-y:auto;border:solid 0px #000; ">
    <table id="tbl-content" style="width:100%;table-layout:fixed;">
     			 <tbody>
';
				$total=0;
$paid=0;
$due=0;
				while($row = mysqli_fetch_array($res)){
          $total=$total+get_total_cost($row['db_order']);
          $paid=$paid+get_total_transaction($row['db_order']);
            $due=$due+(get_total_cost($row['db_order'])-get_total_transaction($row['db_order']));
					echo '<tr style="background-color: black; color: white; opacity: 0.7;">
          <td style="width: 8.5%; ">0'.$row['myCount'].'</td>
          <td style="width: 11%; ">'.$row['db_order'].'</td>
      		<td style="width: 11%; ">'.get_doctor($row['db_practice']).'</td>
		<td style="width: 11%; ">'.$row['db_patient'].'</td>
		<td style="width: 11%; ">'.$row['db_date'].'</td>
		<td style="width: 11%; ">'.$row['db_Rstatus'].'</td>
		<td style="width: 8.5%; ">R'.get_total_cost($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.get_total_transaction($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.(get_total_cost($row['db_order'])-get_total_transaction($row['db_order'])).'</td>
		<td style="width: 11%; ">
		<select class="combo" onchange="location=this.value">
				<option value="#">Action</option>

				<option value="addons2.php? id='.$row['db_order'].'&id_doc='.$id.'">Addons</option>
        <option value="open.php? id2='.$row['myCount'].'&id='.$id.'">Open</option>
        <option value="pdf/examples/pdf.php? id2='.$row['myCount'].'&id='.$id.'" target=_blank>print</option>


				</select>


		</td>
		</tr>
		';
				}
                        echo '</tbody></table></div><!--end #box-->';

					}

					else if(isset($_REQUEST['print'])){
						  $from=$_REQUEST['from'];
						  $to=$_REQUEST['to'];
                         $sql = "SELECT * FROM `prescription` WHERE `db_practice`='".$id."' AND `db_date` BETWEEN '".$from."' AND '".$to."' ORDER BY `prescription`.`myCount` DESC LIMIT 0,30";
 $res = mysqli_query($conn,$sql);


echo '
 <form name="form" method="post" style="margin-left: 5%">
    <table class="art-article" style="width: 100%; ">
  <tbody>

  <tr>
  <td style="width: 20%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   <input name="order_no" type="text" placeholder=" Order No">

  </td>
  <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="search" value="Search" class="art-button">
  </td>

  <td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="from" type="date" value="'.$from.'">
 </td>
<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="to" type="date" value="'.$to.'">
 </td>
<td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="sort" value="Sort" class="art-button">
  </td>
 <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="print" value="Print" class="art-button">
  </td>

  <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="view_all" value="View All" class="art-button">
  </td>



  </td></tr></tbody></table>
  </form>
  <div>
  <table id="header" style="width: 100%; text-align:center; border: black; table-layout:fixed;">
<tr style="background-color: black; color: white;">

          <td style="width: 8.5%; ">Invoice No</td>
          <td style="width: 11%; ">Order No</td>
      		<td style="width: 11%; ">Practice</td>
		<td style="width: 11%; ">Patient</td>
		<td style="width: 11%; ">Date</td>
		<td style="width: 11%; ">Status</td>
		<td style="width: 8.5%; ">Total</td>
		<td style="width: 8.5%; ">Paid</td>
		<td style="width: 8.5%; ">Due Balance</td>
		<td style="width: 11%; ">Action</td>
    <td style="width: 10px; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; vertical-align: middle; "> </td>
    </tr>
    </table>
    </div><!--end #boundary-->
    <div id="box" style="width:100%; height:350px; overflow-y:auto;border:solid 0px #000; ">
    <table id="tbl-content" style="width:100%;table-layout:fixed;">
     			 <tbody>
';
				$total=0;
$paid=0;
$due=0;
				while($row = mysqli_fetch_array($res)){
          $total=$total+get_total_cost($row['db_order']);
          $paid=$paid+get_total_transaction($row['db_order']);
            $due=$due+(get_total_cost($row['db_order'])-get_total_transaction($row['db_order']));
					echo '<tr style="background-color: black; color: white; opacity: 0.7;">
          <td style="width: 8.5%; ">0'.$row['myCount'].'</td>
          <td style="width: 11%; ">'.$row['db_order'].'</td>
      		<td style="width: 11%; ">'.get_doctor($row['db_practice']).'</td>
		<td style="width: 11%; ">'.$row['db_patient'].'</td>
		<td style="width: 11%; ">'.$row['db_date'].'</td>
		<td style="width: 11%; ">'.$row['db_Rstatus'].'</td>
		<td style="width: 8.5%; ">R'.get_total_cost($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.get_total_transaction($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.(get_total_cost($row['db_order'])-get_total_transaction($row['db_order'])).'</td>
		<td style="width: 11%; ">
		<select class="combo" onchange="location=this.value">
				<option value="#">Action</option>

				<option value="addons2.php? id='.$row['db_order'].'&id_doc='.$id.'">Addons</option>
        <option value="open.php? id2='.$row['myCount'].'&id='.$id.'">Open</option>
        <option value="pdf/examples/pdf.php? id2='.$row['myCount'].'&id='.$id.'" target=_blank>print</option>




				</select>


		</td>
		</tr>
		';
				}
                        echo '</tbody></table></div><!--end #box-->';

                    }
					else if(isset($_REQUEST['sort'])){
						  $from=$_REQUEST['from'];
						  $to=$_REQUEST['to'];
                         $sql = "SELECT * FROM `prescription` WHERE `db_practice`='".$id."' AND `db_date` BETWEEN '".$from."' AND '".$to."' ORDER BY `prescription`.`myCount` DESC LIMIT 0,30";
 $res = mysqli_query($conn,$sql);


echo '
 <form name="form" method="post" style="margin-left: 5%">
    <table class="art-article" style="width: 100%; ">
  <tbody>

  <tr>
  <td style="width: 20%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   <input name="order_no" type="text" placeholder=" Order No">

  </td>
  <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="search" value="Search" class="art-button">
  </td>

  <td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="from" type="date" value="'.$from.'">
 </td>
<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="to" type="date" value="'.$to.'">
 </td>
<td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="sort" value="Sort" class="art-button">
  </td>
 <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<a href="pdf/examples/summary.php?id='.$id.'&from='.$from.'&to='.$to.'" target="_blank"  class="art-button">Print</a>
  </td>

  <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="view_all" value="View All" class="art-button">
  </td>



  </td></tr></tbody></table>
  </form>
  <div>
  <table id="header" style="width: 100%; text-align:center; border: black; table-layout:fixed;">
<tr style="background-color: black; color: white;">

          <td style="width: 8.5%; ">Invoice No</td>
          <td style="width: 11%; ">Order No</td>
      		<td style="width: 11%; ">Practice</td>
		<td style="width: 11%; ">Patient</td>
		<td style="width: 11%; ">Date</td>
		<td style="width: 11%; ">Status</td>
		<td style="width: 8.5%; ">Total</td>
		<td style="width: 8.5%; ">Paid</td>
		<td style="width: 8.5%; ">Due Balance</td>
		<td style="width: 11%; ">Action</td>
    <td style="width: 10px; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; vertical-align: middle; "> </td>
    </tr>
    </table>
    </div><!--end #boundary-->
    <div id="box" style="width:100%; height:350px; overflow-y:auto;border:solid 0px #000; ">
    <table id="tbl-content" style="width:100%;table-layout:fixed;">
     			 <tbody>
';
				$total=0;
$paid=0;
$due=0;
				while($row = mysqli_fetch_array($res)){
          $total=$total+get_total_cost($row['db_order']);
          $paid=$paid+get_total_transaction($row['db_order']);
            $due=$due+(get_total_cost($row['db_order'])-get_total_transaction($row['db_order']));
					echo '<tr style="background-color: black; color: white; opacity: 0.7;">
          <td style="width: 8.5%; ">0'.$row['myCount'].'</td>
          <td style="width: 11%; ">'.$row['db_order'].'</td>
      		<td style="width: 11%; ">'.get_doctor($row['db_practice']).'</td>
		<td style="width: 11%; ">'.$row['db_patient'].'</td>
		<td style="width: 11%; ">'.$row['db_date'].'</td>
		<td style="width: 11%; ">'.$row['db_Rstatus'].'</td>
		<td style="width: 8.5%; ">R'.get_total_cost($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.get_total_transaction($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.(get_total_cost($row['db_order'])-get_total_transaction($row['db_order'])).'</td>
		<td style="width: 11%; ">
		<select class="combo" onchange="location=this.value">
				<option value="#">Action</option>

				<option value="addons2.php? id='.$row['db_order'].'&id_doc='.$id.'">Addons</option>
        <option value="open.php? id2='.$row['myCount'].'&id='.$id.'">Open</option>
        <option value="pdf/examples/pdf.php? id2='.$row['myCount'].'&id='.$id.'" target=_blank>print</option>




				</select>


		</td>
		</tr>
		';
				}
                        echo '</tbody></table></div><!--end #box-->';

                    }
					else{
                        $sql = "SELECT * FROM `prescription` WHERE `db_practice`='".$id."' ORDER BY `prescription`.`myCount` DESC LIMIT 0,30";
 $res = mysqli_query($conn,$sql);


echo '
 <form name="form" method="post" style="margin-left: 5%">
    <table class="art-article" style="width: 100%; ">
  <tbody>

  <tr>
  <td style="width: 20%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   <input name="order_no" type="text" placeholder="Order No">

  </td>
  <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="search" value="Search" class="art-button">
  </td>

  <td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="from" type="date" placeholder="From">
 </td>
<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
  <input name="to" type="date" placeholder="To">
 </td>
<td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="sort" value="Sort" class="art-button">
  </td>

  <td style="width: 10%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">

   &nbsp;<input type="submit" width="100px" name="view_all" value="View All" class="art-button">
  </td>



  </td></tr></tbody></table>
  </form>


  <div>
  <table id="header" style="width: 100%; text-align:center; border: black; table-layout:fixed;">
<tr style="background-color: black; color: white;">

          <td style="width: 8.5%; ">Invoice No</td>
          <td style="width: 11%; ">Order No</td>
      		<td style="width: 11%; ">Practice</td>
		<td style="width: 11%; ">Patient</td>
		<td style="width: 11%; ">Date</td>
		<td style="width: 11%; ">Status</td>
		<td style="width: 8.5%; ">Total</td>
		<td style="width: 8.5%; ">Paid</td>
		<td style="width: 8.5%; ">Due Balance</td>
		<td style="width: 11%; ">Action</td>
    <td style="width: 10px; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; vertical-align: middle; "> </td>
    </tr>
    </table>
    </div><!--end #boundary-->
    <div id="box" style="width:100%; height:350px; overflow-y:auto;border:solid 0px #000; ">
    <table id="tbl-content" style="width:100%;table-layout:fixed;">
     			 <tbody>


';
$total=0;
$paid=0;
$due=0;

				while($row = mysqli_fetch_array($res)){
          $total=$total+get_total_cost($row['db_order']);
          $paid=$paid+get_total_transaction($row['db_order']);
          $due=$due+(get_total_cost($row['db_order'])-get_total_transaction($row['db_order']));
					echo '<tr style="background-color: black; color: white; opacity: 0.7;">
          <td style="width: 8.5%; ">0'.$row['myCount'].'</td>
          <td style="width: 11%; ">'.$row['db_order'].'</td>
      		<td style="width: 11%; ">'.get_doctor($row['db_practice']).'</td>
		<td style="width: 11%; ">'.$row['db_patient'].'</td>
		<td style="width: 11%; ">'.$row['db_date'].'</td>
		<td style="width: 11%; ">'.$row['db_Rstatus'].'</td>
		<td style="width: 8.5%; ">R'.get_total_cost($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.get_total_transaction($row['db_order']).'</td>
		<td style="width: 8.5%; ">R'.(get_total_cost($row['db_order'])-get_total_transaction($row['db_order'])).'</td>
		<td style="width: 11%; ">
		<select class="combo" onchange="location=this.value">
				<option value="#">Action</option>

				<option value="addons2.php? id='.$row['db_order'].'&id_doc='.$id.'">Addons</option>
        <option value="open.php? id2='.$row['myCount'].'&id='.$id.'">Open</option>
        <option value="pdf/examples/pdf.php? id2='.$row['myCount'].'&id='.$id.'" target=_blank>print</option>




				</select>


		</td>
		</tr>
		';
				}
                        echo '</tbody></table></div><!--end #box-->';

                    }

				?>


        <table id="header" style="width: 100%; text-align:center; border: black; table-layout:fixed;">
      <tr style="background-color: green; color: white;">

                <td style="width: 8.5%; "></td>
                <td style="width: 11%; "></td>
            		<td style="width: 11%; "></td>
      		<td style="width: 11%; "></td>
      		<td style="width: 11%; "></td>
      		<td style="width: 11%; ">Totals</td>
      		<td style="width: 8.5%; ">R<?=$total?></td>
      		<td style="width: 8.5%; ">R<?=$paid?></td>
      		<td style="width: 8.5%; ">R<?=$due?></td>
      		<td style="width: 11%; "></td>
          <td style="width: 10px; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; vertical-align: middle; "> </td>
          </tr>
          </table>


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
