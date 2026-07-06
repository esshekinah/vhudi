<?php
session_start();
require "conn.php";

$id=$_GET['id'];
$id2=$_GET['id2'];


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

.art-content .art-postcontent-0 .layout-item-0 { color: #D4E7ED; background: #000000;background: rgba(0, 0, 0, 0.6);  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

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

								?></h2>



                <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-content-layout">
              <div class="art-content-layout-row">
              <div class="art-layout-cell" style="width: 25%" >
              <p><br></p>
              </div><div class="art-layout-cell layout-item-0" style="width: 50%" >


				<?php

				$order='';
						$date='';
						$pname='';
						$patient='';
						$ftype='';
						$fprice='';

							$ltype2='';
							//$vision2=$_REQUEST['vision2'];
							$SPH2='';
							$CLYL2='';
							$AXIS2='';
							$P_D2='';
							$SEG2='';
							$R_G_D2='';

						$ltype='';
							//$vision=$_REQUEST['vision'];
							$SPH='';
							$CLYL='';
							$AXIS='';
							$P_D='';
							$SEG='';
							$R_G_D='';

						//echo $ltype2.' | '.$ltype;


						$tint='';
						$tprice='';
						$fdetails='';
						$special_instruction='';


require '../functions.php';
				$sql = "SELECT * FROM `prescription` WHERE `myCount`='".$id2."'";
 $res = mysqli_query($conn,$sql);


				while($row = mysqli_fetch_array($res)){
				$order=$row['db_order'];
						$date=$row['db_date'];
						$pname=$row['db_practice'];
						$patient=$row['db_patient'];
						$ftype=$row['db_FrameType'];
						$fprice=$row['db_FramePrice'];

							$ltype2=$row['lens_left'];
							//$vision2=$row['vision2'];
							$SPH2=$row['db_rSPH'];
							$CLYL2=$row['db_rCLYL'];
							$AXIS2=$row['db_rAXIS'];
							$P_D2=$row['db_rP.D'];
							$SEG2=$row['db_rSEG'];
							$R_G_D2=$row['db_rRGD'];

						$ltype=$row['db_lens'];
							//$vision=$row['vision'];
							$SPH=$row['db_lSPH'];
							$CLYL=$row['db_lCLYL'];
							$AXIS=$row['db_lAXIS'];
							$P_D=$row['db_lP.D'];
							$SEG=$row['db_lSEG'];
							$R_G_D=$row['db_lRGD'];

						//echo $ltype2.' | '.$ltype;


						$tint=$row['db_TINT'];
					//	$tprice=$row['tprice'];
						$fdetails=$row['db_FrameD'];
						$special_instruction=$row['db_sInstruction'];

					}


$doctor='';

$sql = "SELECT * FROM `doctors` WHERE `id`='".$pname."'";
 $res = mysqli_query($conn,$sql);



				while($row = mysqli_fetch_array($res)){
					$doctor=$row['surname_intial'];
				}


				echo ' <center style="background-color:green">'.$patient.'</center>


						<form name="form" method="post" style="margin-left: 5%">

						<table class="art-article" style="width: 100%; "><tbody><tr>
						<td style="width: 100%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
						<span style="font-weight: bold;"><span style="font-size: 16px;">

                        </span>&nbsp;</span><br>Order No
						<input name="order" value="'.$order.'" type="text" readonly><br>Date<input name="date" value="'.$date.'" type="date">
						<br>Practice Name<input value="'.$doctor.'" name="pname" type="text" readonly><br>Patient<input value="'.$patient.'" name="patient" type="text">
						<br>Frame Type<input value="'.$ftype.'" name="ftype" type="text"><br>Frame Price<input value="'.$fprice.'" name="fprice" type="text"><br><br>

RE: Lens Type & Vision

<select name="ltype2" class="combo">


';

$sql = "SELECT * FROM `price` WHERE 1";
   $res = mysqli_query($conn,$sql);
echo '<option value="'.$ltype.'" name="'.$ltype.'">'.$ltype.'</option>';
   while($row = mysqli_fetch_array($res)){
echo '<option value="'.$row['Item_No'].'" name="'.$row['Item_No'].'">'.$row['Item_Vision'].' '.$row['Item_Type'].'</option>';
}



echo '
</select>



LE: Lens Type & Vision

<select name="ltype2" class="combo">


';

$sql = "SELECT * FROM `price` WHERE 1";
   $res = mysqli_query($conn,$sql);
echo '<option value="'.$ltype.'" name="'.$ltype2.'">'.$ltype2.'</option>';
   while($row = mysqli_fetch_array($res)){
echo '<option value="'.$row['Item_No'].'" name="'.$row['Item_No'].'">'.$row['Item_Vision'].' '.$row['Item_Type'].'</option>';
}



echo '
</select>

<input value="'.$ltype.'" name="ltype" type="text">

						<br><table class="art-article" style="width: 100%; "><tbody><tr>
						<td style="width: 15%; "><br></td><td style="width: 15%; text-align: center; ">
						SPH<br></td><td style="width: 14%; text-align: center; ">
						CLYL<br></td><td style="width: 14%; text-align: center; ">
						AXIS<br></td><td style="width: 14%; text-align: center; ">
						P.D<br></td><td style="width: 14%; text-align: center; ">
						SEG<br></td><td style="width: 14%; text-align: center; ">
						R.G.D, ADD<br></td></tr><tr><td style="width: 15%; ">
						RE</td><td style="width: 15%; ">
						<input value="'.$SPH.'" name="SPH" type="text"><br></td><td style="width: 14%; ">
						<input value="'.$CLYL.'" name="CLYL"type="text"><br></td><td style="width: 14%; ">
						<input value="'.$AXIS.'" name="AXIS" type="text"><br></td><td style="width: 14%; ">
						<input value="'.$P_D.'" name="P.D" type="text"><br></td><td style="width: 14%; ">
						<input value="'.$SEG.'" name="SEG" type="text"><br></td><td style="width: 14%; ">
						<input value="'.$R_G_D.'" name="R.G.D" type="text"><br></td></tr>

            <tr><td style="width: 15%; ">
            LE</td><td style="width: 15%; ">
            <input value="'.$SPH2.'" name="SPH2" type="text"><br></td><td style="width: 14%; ">
            <input value="'.$CLYL2.'" name="CLYL2"type="text"><br></td><td style="width: 14%; ">
            <input value="'.$AXIS2.'" name="AXIS2" type="text"><br></td><td style="width: 14%; ">
            <input value="'.$P_D2.'" name="P_D2" type="text"><br></td><td style="width: 14%; ">
            <input value="'.$SEG2.'" name="SEG2" type="text"><br></td><td style="width: 14%; ">
            <input value="'.$R_G_D2.'" name="R_G_D2" type="text"><br></td></tr>
            </tbody></table><br>








						<br>TINT<input value="'.$tint.'" name="tint" type="text">
                         TINT PRICE<input value="'.$tprice.'" name="tprice" type="text">
                         Frame Details<input value="'.$fdetails.'" name="fdetails" type="text">
                         Special Instructions<input value="'.$special_instruction.'" name="special_instruction" type="text">

						 <br></td></tr><tr>
						 <td style="width: 100%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
						 <input type="submit" width="100px" name="submit" value="Update" class="art-button"></td></tr></tbody></table><p><br></p>
						 </form>
						 ';
						 
  function order($order){
		require "conn.php";
		 $sql = "SELECT * FROM `prescription` WHERE `db_order`='".$order."'";

				$res = mysqli_query($conn,$sql);

				$order='';
				while($row = mysqli_fetch_array($res)){

					$order=$row['db_order'];

					}


					return $order;


	}
  function get_count(){
    require "conn.php";
     $sql = "SELECT * FROM `prescription` ORDER BY `myCount` DESC LIMIT 1";

        $res = mysqli_query($conn,$sql);

        $count=0;
        while($row = mysqli_fetch_array($res)){

          $count=$row['myCount'];

          }


          return $count+1;


  }

	function lens_price($ltype){
		require "conn.php";
		 $sql = "SELECT * FROM `price` WHERE `Item_No`='".$ltype."'";

				$res = mysqli_query($conn,$sql);

				$order='';
				while($row = mysqli_fetch_array($res)){

					$order=$row['Item_Type'].' '.$row['Item_Vision'];

					}


					return $order;


	}
	function lens_price2($ltype){
		require "conn.php";
		 $sql = "SELECT * FROM `price` WHERE `Item_No`='".$ltype."'";

				$res = mysqli_query($conn,$sql);

				$order='';
				while($row = mysqli_fetch_array($res)){

					$order=$row['Item_Price'];

					}


					return $order;


	}




	if (isset($_REQUEST['submit']))  {
	$order=$_REQUEST['order'];
						$date=$_REQUEST['date'];

						$patient=$_REQUEST['patient'];
						$ftype=$_REQUEST['ftype'];
						$fprice=$_REQUEST['fprice'];

							$ltype2=$_REQUEST['ltype'];
							//$vision2=$_REQUEST['vision2'];
							$SPH2=$_REQUEST['SPH2'];
							$CLYL2=$_REQUEST['CLYL2'];
							$AXIS2=$_REQUEST['AXIS2'];
							$P_D2=$_REQUEST['P_D2'];
							$SEG2=$_REQUEST['SEG2'];
							$R_G_D2=$_REQUEST['R_G_D2'];

						$ltype=$_REQUEST['ltype2'];
						//$rtype=$_REQUEST['rtype'];
							//$vision=$_REQUEST['vision'];
							$SPH=$_REQUEST['SPH'];
							$CLYL=$_REQUEST['CLYL'];
							$AXIS=$_REQUEST['AXIS'];
							$P_D=$_REQUEST['P_D'];
							$SEG=$_REQUEST['SEG'];
							$R_G_D=$_REQUEST['R_G_D'];

						//echo $ltype2.' | '.$ltype;


						$tint=$_REQUEST['tint'];
						$tprice=$_REQUEST['tprice'];
						$fdetails=$_REQUEST['fdetails'];
						$special_instruction=$_REQUEST['special_instruction'];



		$sql = "
		UPDATE `prescription` SET `db_patient`='".$patient."',`db_lens`='".lens_price($ltype)."',
		`lens_left`='".lens_price($ltype2)."',`db_rSPH`='".$SPH."',`db_rCLYL`='".$CLYL."',
		`db_rAXIS`='".$AXIS."',`db_rP.D`='".$P_D."',`db_rSEG`='".$SEG."',`db_rRGD`='".$R_G_D."',
		`db_lSPH`='".$SPH2."',`db_lCLYL`='".$CLYL2."',`db_lAXIS`='".$AXIS2."',`db_lP.D`='".$P_D2."',
		`db_lSEG`='".$SEG2."',`db_lRGD`='".$R_G_D2."',`db_TINT`='".$tint."',`db_FrameD`='".$fdetails."',
		`db_sInstruction`='".$special_instruction."',`db_FrameType`='".$ftype."',`db_FramePrice`='".$fprice."' WHERE `db_order`='".$order."'
	";


        if ($conn->query($sql) === TRUE) {
			 echo '<script> location.replace("view_order.php?id='.$id.'"); </script>';
		}
			else{
				echo " 6576567567576";
			echo mysql_errno($conn) . ": " . mysql_error($conn) . "\n";
		}
	}

				?>



        </div><div class="art-layout-cell" style="width: 25%" >
          <p>







          <br></p>
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
