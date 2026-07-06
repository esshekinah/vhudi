<?php
$access='444444444';
$id=$_GET['id'];
$id2=$_GET['id2'];
require "../../../conn.php";
require_once('tcpdf_include.php');
$html='<p style="text-align: center;"><img src="logo.PNG" /></p>';
function get_doctor($pk){
	require "../../../conn.php";
 $sql = "SELECT * FROM `doctors` WHERE `pk`='".$pk."'";

		$res = mysqli_query($conn,$sql);

		$id='';
		while($row = mysqli_fetch_array($res)){

			$id=$row['surname_intial'];

			}


			return $id;
}
 function get_total_cost($db_order){
require "../../../conn.php";
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
function get_total_transaction($order){
require "../../../conn.php";
$sql = "SELECT * FROM `transaction` WHERE `order_id`='".$order."'";
// OR `Item_No`'".$left."'
  $res = mysqli_query($conn,$sql);

  $total=0;
  while($row = mysqli_fetch_array($res)){

    $total=$total+$row['amount'];

    }



    return $total;
}

$total=0;
$paid=0;
 $due=0;
$transac='';
 
  
 

function get_addons($id2){
    	require "../../../conn.php";
 $sql = "SELECT * FROM `addon_clients` WHERE `order_id`='".$id2."'";

		$res = mysqli_query($conn,$sql);

		$id='';
    $count=0;
		while($row = mysqli_fetch_array($res)){

			$id=$id.': '.$row['description'].' <b>R'.$row['price'].'</b><br>';
$count=$count+1;
			}

    if($count==0){
        $id='None';
    }

			return $id;
}
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


				$sql = "SELECT * FROM `prescription` WHERE `myCount`='".$id2."'";
 $res = mysqli_query($conn,$sql);


				while($row = mysqli_fetch_array($res)){
                      $total=get_total_cost($row['db_order']);
$paid=get_total_transaction($id);
  


  $due=$total-$paid;
  $transac=$transac.'Total : R'.$total;
   $transac=$transac. '<br>Paid &nbsp;: R'.$paid;
   $transac=$transac. '<br>Due &nbsp;: R'.$due;

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

						$ltype=$row['lens_left'].' <b>R'.($row['LEFT']).'</b>';
						$ltypeR=$row['db_lens'].' <b>R'.($row['RIGHT']).'</b>';
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




				$html=$html. '

<table class="art-article" style="width: 100%; border-bottom-width"><tbody>

<tr><td style="width: 15%;"></td>
<td style=" width: 30%; font-size: 14px; font-weight: normal;">Invoive No</td>
<td style="width: 40%;">#0'.$id2.'</td>
<td style="width: 15%;"></td>
<td style="width: 15%;"></td></tr>

<tr><td style="width: 15%;"></td>
<td style="width: 30%; font-size: 14px; font-weight: normal;">Order No</td>
<td style="width: 40%;">'.$order.'</td>
<td style="width: 15%;"></td></tr>

<tr><td style="width: 15%;"></td>
<td style="width: 30%; font-size: 14px; font-weight: normal;">Date</td>
<td style="width: 40%;">'.$date.'</td>
<td style="width: 15%;"></td></tr>

<tr><td style="width: 15%;"></td>
<td style="width: 30%; font-size: 14px; font-weight: normal;">Practice Name</td>
<td style="width: 40%;">'. get_doctor($pname).'</td>
<td style="width: 15%;"></td></tr>

<tr><td style="width: 15%;"></td>
<td style="width: 30%; font-size: 14px; font-weight: normal;">Patient</td>
<td style="width: 40%;">'.$patient.'</td>
<td style="width: 15%;"></td></tr>

<tr><td style="width: 15%;"></td>
<td style="width: 30%; font-size: 14px; font-weight: normal;">Frame Type</td>
<td style="width: 40%;">'.$ftype.'</td>
<td style="width: 15%;"></td></tr>

<tr><td style="width: 15%;"></td>
<td style="width: 30%; font-size: 14px; font-weight: normal;">Frame Price</td>
<td style="width: 40%;">R'.$fprice.'</td>
<td style="width: 15%;"></td></tr>




<br>
<br>
<tr><td style="width: 15%;"></td>

<td style="width: 30%; font-size: 14px; font-weight: normal;">LE: Lens Type & Vision</td>
<td style="width: 40%;">'.$ltype.'</td>
<td style="width: 15%;"></td></tr>

<tr><td style="width: 15%;"></td>

<td style="width: 30%; font-size: 14px; font-weight: normal;">RE: Lens Type & Vision</td>
<td style="width: 40%;">'.$ltypeR.'</td>
<td style="width: 15%;"></td></tr>

</tbody></table>











						<br>
						<br>
						<table class="art-article" style="width: 100%; "><tbody>

						<tr>
						<td style="width: 15%; "><br></td>
						<td style=" background-color: gray; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; "><br></td>
						<td style="background-color: gray; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; text-align: center; ">SPH</td>
						<td style="background-color: gray; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; text-align: center; ">CLYL</td>
						<td style="background-color: gray; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; text-align: center; ">AXIS</td>
						<td style="background-color: gray; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; text-align: center; ">P.D</td>
						<td style="background-color: gray; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; text-align: center; ">SEG</td>
						<td style="background-color: gray; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; text-align: center; ">R.G.D, ADD</td>
						<td style="width: 15%; "><br></td>
						</tr>


						<tr>
						<td style="width: 15%; "><br></td>
						<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">RE</td>
						<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$SPH.'</td>
						<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$CLYL.'</td>
						<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$AXIS.'</td>
						<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$P_D.'</td>
						<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$SEG.'</td>
						<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$R_G_D.'</td>
						<td style="width: 15%; "><br></td>
						</tr>

            <tr>
			<td style="width: 15%; "><br></td>
			<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">LE</td>
			<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$SPH2.'</td>
			<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$CLYL2.'</td>
			<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$AXIS2.'</td>
			<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$P_D2.'</td>
			<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$SEG2.'</td>
			<td style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; width: 10%; ">'.$R_G_D2.'</td>
			<td style="width: 15%; "><br></td>
            
			</tr>

            </tbody></table>
			<br>
			<br>
            
            
			<br>


<table class="art-article" style="width: 100%; "><tbody>

<tr><td style="width: 15%;"></td>
<td style=" width: 30%; font-size: 14px; font-weight: normal;"><h3>Addons</h3></td>
<td style="width: 40%;">'.get_addons($order).'</td>
<td style="width: 15%;"></td></tr>


<tr><td style="width: 15%;"></td>
<td style=" width: 30%; font-size: 14px; font-weight: normal;">TINT</td>
<td style="width: 40%;">'.$tint.'</td>
<td style="width: 15%;"></td></tr>


<tr><td style="width: 15%;"></td>
<td style="width: 30%; font-size: 14px; font-weight: normal;">Special Instructions</td>
<td style="width: 40%;">'.$special_instruction.'</td>
<td style="width: 15%;"></td></tr>


<tr><td style="width: 15%;"></td>
<td style="width: 30%; font-size: 14px; font-weight: normal;"><br><br><br><br>'.$transac.'</td>
<td style="width: 40%;"></td>
<td style="width: 15%;"></td></tr>


</tbody></table>



						 ';

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Vhudi');
$pdf->SetTitle('Vhudi_'.$id2);
$pdf->SetSubject('Vhudi');
$pdf->SetKeywords('Vhudi');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, 0, "", $tender, array(10,64,255), array(10,64,140));

$pdf->setPrintHeader(false);
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 10, '', true);


$pdf->AddPage();


$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->setPrintHeader(true);





$pdf->Output('vhudi_'.$id2.'.pdf', 'I');
