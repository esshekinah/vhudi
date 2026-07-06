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
    <ul class="art-hmenu"><li><a href="../invoice.php" class="">Invoice</a></li><li><a href="../clients.php" class="active">Clients</a><ul class="active"><li><a href="../clients/all-clients.php" class="">All Clients</a></li><li><a href="../clients/new-client.php" class="active">New Client</a></li></ul></li><li><a href="../products.php">Products</a><ul><li><a href="../products/lense-and-vision.php">Lense and Vision</a></li><li><a href="../products/addons.php">Addons</a></li></ul></li><li><a href="../logout.php">Logout</a></li></ul>
        </div>
    </nav>
<div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content"><article class="art-post art-article">
                                <h2 class="art-postheader">New Clients</h2>

                <div class="art-postcontent art-postcontent-0 clearfix"><table class="art-article" style="width: 100%; "><tbody>

				<?php
				if (isset($_REQUEST['submit']))  {

	$email= $_REQUEST['email'];
	$cell= $_REQUEST['cell'];

	$email= $_REQUEST['email'];
	$name= $_REQUEST['name'];
	$surname= $_REQUEST['surname'];
	$initials= $_REQUEST['initials'];




	$surname2=$surname.' '.$initials;
	$sql = "
	INSERT INTO `doctors`(`surname`, `names`, `cell no`, `email`, `surname_intial`)
	VALUES ('".$surname."','".$name."','".$cell."','".$email."','".$surname2."')
	";


        if ($conn->query($sql) === TRUE) {
			echo '<script> location.replace("all-clients.php"); </script>';
		}else{
			echo mysql_errno($conn) . ": " . mysql_error($conn) . "\n";
		}



				}else{
					echo '
					<form name="form" method="post" style="margin-left: 5%">
				<tr><td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<span style="font-size: 18px; ">Surname</span></td>
				<td style="width: 80%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<input name="surname" type="text" require><br></td></tr><tr>
				<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<span style="font-size: 18px; ">Names</span><br></td>
				<td style="width: 80%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<input  name="name"  type="text"><br></td></tr><tr>
				<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<span style="font-size: 18px; ">Initials</span><br></td>
				<td style="width: 80%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<input  name="initials"  type="text"><br></td></tr>

        <tr>
				<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<span style="font-size: 18px; ">Cell Number</span></td>

				<td style="width: 80%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<input name="cell" type="text"><br></td>
				</tr><tr>
				<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<span style="font-size: 18px; ">Email</span><br></td>
				<td style="width: 80%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<input name="email" type="text"><br></td></tr><tr>
				<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">
				<span style="font-size: 18px;"><br></span></td>
				<td style="width: 80%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; ">


				<input type="submit" width="100px" name="submit" value="Add Client" class="art-button">

				<br></td></tr><tr>
				<td style="width: 20%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; "><br></td>
				<td style="width: 80%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; "><br></td></tr>
				</tbody></table>



				</form>
					';

				}
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
