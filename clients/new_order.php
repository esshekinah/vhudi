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












$doctor='';

$sql = "SELECT * FROM `doctors` WHERE `id`='".$id."'";
 $res = mysqli_query($conn,$sql);



				while($row = mysqli_fetch_array($res)){
					$doctor=$row['surname_intial'];
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
                                <h2 class="art-postheader">New Order : <?php echo $doctor; ?></h2>


                <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-content-layout">
    <div class="art-content-layout-row">
    <div class="art-layout-cell" style="width: 25%" >
        <p><br></p>
    </div><div class="art-layout-cell layout-item-0" style="width: 50%" >

	<?php


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

						$ltype=$_REQUEST['rtype'];
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



					if($order=='' || $order==order($order)){
						echo ' <center style="background-color:red">Enter Valid Order Number</center>


						<form name="form" method="post" style="margin-left: 5%">

						<table class="art-article" style="width: 100%; "><tbody><tr>
						<td style="width: 100%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
						<span style="font-weight: bold;"><span style="font-size: 16px;">

                        </span>&nbsp;</span><br>Order No<input name="order" value="'.$order.'" type="text"><br>Date<input name="date" value="'.$date.'" type="date">
						<br>Practice Name

<input name="pname" value="'.$doctor.'" type="text" disabled>
            <br>Patient<input value="'.$patient.'" name="patient" type="text">
						<br>Frame Type<input value="'.$ftype.'" name="ftype" type="text"><br>Frame Price<input value="'.$fprice.'" name="fprice" type="number"><br><br>


						<br>Lens Type & Vision RE<br>

            <select name="rtype" class="combo">


            ';

            $sql = "SELECT * FROM `price` WHERE 1";
               $res = mysqli_query($conn,$sql);

               while($row = mysqli_fetch_array($res)){
            echo '<option value="'.$row['Item_No'].'" name="'.$row['Item_No'].'">'.$row['Item_Vision'].' '.$row['Item_Type'].'</option>';
            }



            echo '
            </select>
	<br>Lens Type & Vision LE<br>

            <select name="ltype" class="combo">


            ';

            $sql = "SELECT * FROM `price` WHERE 1";
               $res = mysqli_query($conn,$sql);

               while($row = mysqli_fetch_array($res)){
            echo '<option value="'.$row['Item_No'].'" name="'.$row['Item_No'].'">'.$row['Item_Vision'].' '.$row['Item_Type'].'</option>';
            }



            echo '
            </select

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
            </tbody></table>






						</td></tr></tbody></table>




						<br>TINT<input value="'.$tint.'" name="tint" type="text">
                         TINT PRICE<input value="'.$tprice.'" name="tprice" type="text">
                         Frame Details<input value="'.$fdetails.'" name="fdetails" type="text">
                         Special Instructions<input value="'.$special_instruction.'" name="special_instruction" type="text">

						 <br></td></tr><tr>
						 <td style="width: 100%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
						 <input type="submit" width="100px" name="submit" value="Add" class="art-button"></td></tr></tbody></table><p><br></p>
						 </form>
						 ';
					}
					else{

                if($fprice==''){
                    $fprice='0.00';
                }


		$sql = "
		INSERT INTO `prescription`(
		`myCount`, `db_order`, `db_practice`,`db_patient`, 
		`db_lens`,`lens_left`, `db_rSPH`, `db_rCLYL`,`db_rAXIS`, `db_rP.D`, `db_rSEG`, `db_rRGD`, `db_lSPH`, `db_lCLYL`,
		`db_lAXIS`, `db_lP.D`,`db_lSEG`,`db_lRGD`, `db_TINT`, `db_FrameD`, `db_sInstruction`,`db_FrameType`,`db_FramePrice`,`db_date`, 
		`LEFT`, `RIGHT`,`invoice`,`vision_left`,`db_Ref`,`db_Progress`,`extra`,`db_Paid`,`db_Bal`,`db_Total`)

		VALUES (
		'".get_count()."',	'".$order."','".$id."','".$patient."',
		'".lens_price($ltype)."','".lens_price($ltype2)."',
		'".$SPH."','".$CLYL."','".$AXIS."','".$P_D."','".$SEG."','".$R_G_D."','".$SPH2."','".$CLYL2."',
		'".$AXIS2."','".$P_D2."','".$SEG2."','".$R_G_D2."','".$tint."','".$fdetails."','".$special_instruction."','".$ftype."','".$fprice."','".$date."',
		'".lens_price2($ltype)."','".lens_price2($ltype2)."','0','0','0','0','0','0','0','0'
		)


	";
        if ($conn->query($sql) === TRUE) {
			echo '<br><br><center style="background-color:green">Order No: '.$order.' is successfully added</center>
			<center><br><br>
			<a class="art-button" href="new_order.php?id='.$id.'">Create New Order</a><br><br>
			<a class="art-button" href="view_order.php?id='.$id.'">Goto Orders</a><br><br>

			</center>

			';
		}
			else{
			    echo mysqli_error($conn);
			//echo mysql_errno($conn) . ": " . mysql_error($conn) . "\n";
		}
					}

	}
					else{
						echo '  <form name="form" method="post" style="margin-left: 5%">

						<table class="art-article" style="width: 100%; "><tbody><tr>
						<td style="width: 100%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
						<span style="font-weight: bold;"><span style="font-size: 16px;">

                        </span>&nbsp;</span><br>Order No<input name="order" type="text"><br>Date<input name="date" type="date">
						<br>Practice Name




<input name="pname" value="'.$doctor.'" type="text" disabled>


						<br>Patient<input name="patient" type="text">
						<br>Frame Type<input name="ftype" type="text"><br>Frame Price<input name="fprice" type="text"><br><br>


						<span style="font-weight: bold; font-size: 16px;">

						</span><br>

						Lens Type & Vision RE<br>

						<select name="rtype" class="combo">


				';

				 $sql = "SELECT * FROM `price` WHERE 1";
               $res = mysqli_query($conn,$sql);

               while($row = mysqli_fetch_array($res)){
				  echo '<option value="'.$row['Item_No'].'" name="'.$row['Item_No'].'">'.$row['Item_Vision'].' '.$row['Item_Type'].'</option>';
			   }



			echo '
				</select><br>

				Lens Type & Vision LE<br>

						<select name="ltype" class="combo">';
						
						
						$sql = "SELECT * FROM `price` WHERE 1";
               $res = mysqli_query($conn,$sql);

               while($row = mysqli_fetch_array($res)){
				  echo '<option value="'.$row['Item_No'].'" name="'.$row['Item_No'].'">'.$row['Item_Vision'].' '.$row['Item_Type'].'</option>';
			   }



			echo '
				</select><br>


						<table class="art-article" style="width: 100%; "><tbody><tr>
						<td style="width: 15%; "><br></td><td style="width: 15%; text-align: center; ">
						SPH<br></td><td style="width: 14%; text-align: center; ">
						CLYL<br></td><td style="width: 14%; text-align: center; ">
						AXIS<br></td><td style="width: 14%; text-align: center; ">
						P.D<br></td><td style="width: 14%; text-align: center; ">
						SEG<br></td><td style="width: 14%; text-align: center; ">
						R.G.D, ADD<br></td></tr><tr><td style="width: 15%; ">
						RE</td><td style="width: 15%; ">
						<input name="SPH" type="text"><br></td><td style="width: 14%; ">
						<input name="CLYL"type="text"><br></td><td style="width: 14%; ">
						<input name="AXIS" type="text"><br></td><td style="width: 14%; ">
						<input name="P.D" type="text"><br></td><td style="width: 14%; ">
						<input name="SEG" type="text"><br></td><td style="width: 14%; ">
						<input name="R.G.D" type="text"><br></td></tr>
            <tr><td style="width: 15%; ">
            LE</td><td style="width: 15%; ">
            <input name="SPH2" type="text"><br></td><td style="width: 14%; ">
            <input name="CLYL2"type="text"><br></td><td style="width: 14%; ">
            <input name="AXIS2" type="text"><br></td><td style="width: 14%; ">
            <input name="P_D2" type="text"><br></td><td style="width: 14%; ">
            <input name="SEG2" type="text"><br></td><td style="width: 14%; ">
            <input name="R_G_D2" type="text"><br></td>

            </tbody></table>







						</td></tr></tbody></table>




						<br>TINT<input name="tint" type="text">
                         TINT PRICE<input name="tprice" type="text">
                         Frame Details<input name="fdetails" type="text">
                         Special Instructions<input name="special_instruction" type="text">

						 <br></td></tr><tr>
						 <td style="width: 100%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
						 <input type="submit" width="100px" name="submit" value="Add" class="art-button"></td></tr></tbody></table><p><br></p>
						 </form>
						 ';

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
