<?php

require "conn.php";
$access='444444444';
$id=$_GET['id'];



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
                                <h2 class="art-postheader">Transaction</h2>
                                                
              <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-content-layout">
    <div class="art-content-layout-row">
    <div class="art-layout-cell" style="width: 100%" >
	
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
					
					$total=$total+$row['LEFT'];
					$total=$total+$row['RIGHT'];
					
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
		$sql = "SELECT * FROM `prescription` WHERE `db_order`='".$id."'";
 $res = mysqli_query($conn,$sql);
				
		
		
		$total=0;
		$paid=0;
	     $due=0;
				while($row = mysqli_fetch_array($res)){
					$total=get_total_cost($row['db_order']);
		$paid=get_total_transaction($id);
				}
				
				$due=$total-$paid;
				echo 'Total : R'.$total;
				echo '<br>Paid : R'.$paid;
				echo '<br>Due : R'.$due;
				
				if (isset($_REQUEST['submit']))  {
	$amount=$_REQUEST['amount'];
	
	
	
	if($amount==''){
		echo '<center style="background-color:red">Enter Pay Amount</center>
					
					
				';
		
	}
	
	
	else if($amount>$due){
		
		
		echo '<center style="background-color:red">Enter Pay Amount which is less than or equal to R'.$due.'</center>
					
					
				';
	}
	
	
	else{
		$sql = "
		INSERT INTO `transaction`(`order_id`, `date`, `amount`) 
		VALUES ('".$id."','".date('Y-m-d H:i:s')."','".$amount."')
	
	";
	
	
        if ($conn->query($sql) === TRUE) {
			echo '
			<script> location.replace("transaction.php? id='.$id.'"); </script>
			';
		}
			else{
			echo mysql_errno($conn) . ": " . mysql_error($conn) . "\n";
		}
	}
	
				}
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				if($due>0){
					echo '
					<form name="form" method="post" style="margin-left: 5%">
					<table class="art-article" style="width: 100%; ">
				<tbody><tr><td style="width: 34%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<span style="font-weight: bold;">Amount</span>
				</td><td style="width: 33%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<input name="amount" type="number">
				
				<br></td><td style="width: 33%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				
				
				&nbsp;<input type="submit" width="100px" name="submit" value="Pay" class="art-button">
				
				
				</td></tr></tbody></table>
				</form>
				';
				}else{
					echo '<center>Fully Paid</center>';
				}
				
	
	?>
	
       
	   
		
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