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

?>


<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.3.0.60745 -->
    <meta charset="utf-8">
    <title>Payments</title>
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
                          <h2 class="art-postheader">Transaction: <?=get_doctor($id)?></h2>

        <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-content-layout">
<div class="art-content-layout-row">
<div class="art-layout-cell" style="width: 30%" >
<div style="background-color: black; opacity: 0.7">
<?php
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



 ?>
</div>

</div>
<div class="art-layout-cell" style="width: 40%" >
<?php

function   payme($db_order,$due){
  //echo 'yeah-->'.$db_order.'<br>';
require 'conn.php';
  $sql = "
  INSERT INTO `transaction`(`order_id`, `date`, `amount`)
  VALUES ('".$db_order."','".date('Y-m-d H:i:s')."','".$due."')

  ";


    if ($conn->query($sql) === TRUE) {

      //echo 'yeah-->'.$db_order.'<br>';
    }

}

if(isset($_REQUEST['pay'])){
    $paynow=$_REQUEST['paynow'];

    $sql = "SELECT * FROM `prescription` WHERE `db_practice`='".$id."'";
    $res = mysqli_query($conn,$sql);



    				while($row = mysqli_fetch_array($res)){

$due2=0;
                $due2=$due2+(get_total_cost($row['db_order'])-get_total_transaction($row['db_order']));

                if($due2>0){

                  //----then paynow
                  if($paynow>=$due2){

                    //--pay--here
                    payme($row['db_order'],$due2);
                    $paynow=$paynow-$due2;
                  }else if($paynow<$due2){

                      payme($row['db_order'],$due2);
                      $paynow=0;
                      break;
                  }


                }



            }
            if($paynow>0){
              echo '<center><b>Your Change is R'.$paynow.'</b></center>';
            }

  }
  $sql = "SELECT * FROM `prescription` WHERE `db_practice`='".$id."'";
  $res = mysqli_query($conn,$sql);

  $total=0;
  $paid=0;
  $due=0;

  				while($row = mysqli_fetch_array($res)){
            $total=$total+get_total_cost($row['db_order']);
            $paid=$paid+get_total_transaction($row['db_order']);
              $due=$due+(get_total_cost($row['db_order'])-get_total_transaction($row['db_order']));

          }
 ?>

  <table id="header" style="width: 100%; text-align:center; border: black; table-layout:fixed;">
  <tr style="background-color: black; color: white;">

          <td style="width: 20%; ">Total</td>
          <td style="width: 20%; ">Paid</td>
          <td style="width: 20%; ">Due</td>
    <td style="width: 20%; ">Pay now</td>

    <td style="width: 20%; ">Action</td>


    </tr>
<form name="form" method="post" style="margin-left: 5%">
    <tr style="background-color: black; opacity: 0.7" >

            <td style="width: 20%; ">R<?=$total;?></td>
            <td style="width: 20%; ">R<?=$paid;?></td>
            <td style="width: 20%; ">R<?=$due;?></td>
      <td style="width: 20%; ">
         <input name="paynow" type="number" value="<?=$due;?>"></td>

      <td style="width: 20%; "><input type="submit" width="100px" name="pay" value="Pay" class="art-button"></td>


      </tr>
    </form>
    </table>
</div>
<div class="art-layout-cell" style="width: 30%" >


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
