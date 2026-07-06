<?php
session_start();
require "conn.php";


//$_SESSION['access']='hhhhhhh';
$access='';
if(isset($_SESSION['access'])){
  $access=$_SESSION['access'];
  echo '<script> location.replace("clients/all-clients.php")</script>';
}else{

}



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


        </div>
    </nav>
<div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content"><article class="art-post art-article">

<h2 class="art-slogan">Login</h2>








</header>
<div class="art-sheet clearfix">
<nav class="art-nav">



</nav>
<div class="art-layout-wrapper">
            <div class="art-content-layout">
                <div class="art-content-layout-row">
                    <div class="art-layout-cell art-content"><article class="art-post art-article">


            <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-content-layout">
<div class="art-content-layout-row">
<div class="art-layout-cell" style="width: 100%" >
    <p style="text-align: center;"><br></p>
</div>
</div>
</div>
<div class="art-content-layout">
<div class="art-content-layout-row">
<div class="art-layout-cell" style="width: 33%" >
    <p><br></p>
</div>



<div class="art-layout-cell layout-item-0" style="width: 34%" >
<?php
if(isset($_REQUEST['login'])){
		$email= $_REQUEST['email'];
		$password= $_REQUEST['password'];

if($email=='info@vhudi.co.za' && $password=='vhudi@2018'){
  $_SESSION['access']='11111111111111';
  
  if(isset($_SESSION['access'])){
    $access=$_SESSION['access'];
    echo '<script> location.replace("clients/all-clients.php")</script>';
  }else{

  }
}else {
  echo '<center style="background-color: red">Please Enter Correct Email & Password as Registered</center>';
}

  }

 ?>

  <form name="form" method="post" style="margin-left: 5%">
  		<div><h2 style="color: white">Login</h2></div>
          <p><input placeholder=" Email or Username" name="email" style="text-align: center;" type="email" required><br></p>
  		<p><input placeholder=" Password" name="password" style="text-align: center;" type="password" required><br></p>
  		<p style="text-align: left;">
  		<input type="submit" width="100px" name="login" value="Sign in" class="art-button">&nbsp;



      </form>




  	</div>






  	<div class="art-layout-cell" style="width: 33%" >
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


      <p class="art-page-footer">
          <span id="art-footnote-links">Designed by <a href="http://www.easycloudhosting.co.za" target="_blank">Easy Cloud Hosting</a>.</span>
      </p>
    </div>
  </footer>

  </div>


  </body></html>
