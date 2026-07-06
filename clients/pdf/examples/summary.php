<?php
$access='444444444';
$id=$_GET['id'];
$from=$_GET['from'];
$to=$_GET['to'];
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


$html='<p style="text-align: center;"><img src="logo.PNG" /></p>';
$html=$html.'
<b>Summarised Statement</b><br>
'.get_doctor($id).'<br>
from: '.$from.'<br>
To : '.$to.'<br>

<table style="width: 100%; text-align:center; "><thead>
<tr nobr="true" style="background-color: gray; color: white;">

				<td style="width: 8.5%; ">Invoice No</td>
				<td style="width: 11%; ">Order No</td>
				<td style="width: 16.5%; ">Practice</td>
	<td style="width: 16.5%; ">Patient</td>
	<td style="width: 11%; ">Date</td>
	<td style="width: 11%; ">Status</td>
	<td style="width: 8.5%; ">Total</td>
	<td style="width: 8.5%; ">Paid</td>
	<td style="width: 8.5%; ">Due Balance</td>

	</tr>
	</thead><tbody>
';


$sql = "SELECT * FROM `prescription` WHERE `db_practice`='".$id."' AND `db_date` BETWEEN '".$from."' AND '".$to."'";
$res = mysqli_query($conn,$sql);

$total=0;
$paid=0;
$due=0;
while($row = mysqli_fetch_array($res)){
	$total=$total+get_total_cost($row['db_order']);
	$paid=$paid+get_total_transaction($row['db_order']);
		$due=$due+(get_total_cost($row['db_order'])-get_total_transaction($row['db_order']));
	$html=$html.'<tr style="color: black">
	<td style="width: 8.5%; border-left-width:0px; border-bottom-width:0px; ">0'.$row['myCount'].'</td>
	<td style="width: 11%;  border-left-width:0px; border-bottom-width:0px;">'.$row['db_order'].'</td>
	<td style="width: 16.5%;  border-left-width:0px; border-bottom-width:0px;">'.get_doctor($row['db_practice']).'</td>
<td style="width: 16.5%;  border-left-width:0px; border-bottom-width:0px;">'.$row['db_patient'].'</td>
<td style="width: 11%;  border-left-width:0px; border-bottom-width:0px;">'.$row['db_date'].'</td>
<td style="width: 11%;  border-left-width:0px; border-bottom-width:0px;">'.$row['db_Rstatus'].'</td>
<td style="width: 8.5%;  border-left-width:0px; border-bottom-width:0px;">R'.get_total_cost($row['db_order']).'</td>
<td style="width: 8.5%;  border-left-width:0px; border-bottom-width:0px;">R'.get_total_transaction($row['db_order']).'</td>
<td style="width: 8.5%;  border-left-width:0px; border-bottom-width:0px;border-right-width:0px;">R'.(get_total_cost($row['db_order'])-get_total_transaction($row['db_order'])).'</td>

</tr>
';
}
$html=$html.'<tr style="color: black">
<td style="width: 8.5%; "></td>
<td style="width: 11%; "></td>
<td style="width: 16.5%; "></td>
<td style="width: 16.5%; "></td>
<td style="width: 11%; "></td>
<td style="width: 11%;  "><b>Total</b></td>
<td style="width: 8.5%;">R'.$total.'</td>
<td style="width: 8.5%;  ">R'.$paid.'</td>
<td style="width: 8.5%; ">R'.$due.'</td>

</tr>
';



$html=$html.'</tbody></table>';
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





// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Vhudi');
$pdf->SetTitle('Vhudi_'.$id);
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


$pdf->AddPage('L');


$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->setPrintHeader(true);





$pdf->Output('vhudi_'.$id.'.pdf', 'I');
