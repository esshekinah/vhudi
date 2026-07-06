<?php 
session_start();
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.3.0.60745 -->
    <meta charset="utf-8">
    <title>Logout</title>
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
    <ul class="art-hmenu"><li><a href="invoice.php" class="">Invoice</a></li><li><a href="clients.php" class="">Clients</a><ul class=""><li><a href="clients/all-clients.php" class="">All Clients</a></li><li><a href="clients/new-client.php" class="">New Client</a></li></ul></li><li><a href="products.php" class="">Products</a><ul class=""><li><a href="products/lense-and-vision.php" class="">Lense and Vision</a></li><li><a href="products/addons.php" class="">Addons</a></li></ul></li><li><a href="logout.php" class="active">Logout</a></li></ul>
        </div>
    </nav>
<div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content"><article class="art-post art-article">
                                <h2 class="art-postheader">Logout</h2>

                <div class="art-postcontent art-postcontent-0 clearfix">

                            <?php

                            $access='';


                            
                            unset($_SESSION['access']);



                            if(isset($_SESSION['access'])){
                              //  echo '<script> location.replace("clients/all-clients.php"); </script>';
                            //  echo $_SESSION['access'];
                            }else{
                                echo '<script> location.replace("index.php"); </script>';
                                //  echo $_SESSION['access'];
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
