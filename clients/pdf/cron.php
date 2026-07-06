<?php


//UPDATE `bench_ev` SET `variance`=`estimate`-`amount` WHERE `qty`>0 AND `type`='bench'
require "conn.php";

                $sql = "SELECT * FROM `cron` WHERE `status`='cron'";
 
				$res = mysqli_query($conn,$sql);
				$access='';
				$id='';
				
				while($row = mysqli_fetch_array($res)){
					
					$access=$row['access'];
				$id=$row['id'];
				
					}
					
					echo '<script> location.replace("examples/Summary_bench.php?access='.$access.'&date='.$id.'"); </script>';
					
?>