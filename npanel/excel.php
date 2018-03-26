<?php
// ini_set("include_path", '/home2/mirsamie/php:' . ini_get("include_path") );

$fname = ((isset($_REQUEST['fname']) && trim($_REQUEST['fname'])!='')?trim($_REQUEST['fname']):'spreadsheet');
session_start();
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=$fname".".xls");
$data = $_SESSION['csv_ready'];
if(isset($_SESSION['selected_base']) && $_SESSION['selected_base']=='sh_mantaghe'){
  $data = str_replace('Zone_','منطقه ',$data);
  $data = str_replace('13','ثامن',$data);
  $data = str_replace('Zone Name','منطقه',$data);
}
echo $data;
die();

/*
require_once 'Spreadsheet/Excel/Writer.php';

// Creating a workbook
$workbook = new Spreadsheet_Excel_Writer();

// sending HTTP headers
$workbook->send('test.xls');

// Creating a worksheet
$worksheet =& $workbook->addWorksheet('My first worksheet');

// The actual data
$worksheet->write(0, 0, 'Name');
$worksheet->write(0, 1, 'Age');
$worksheet->write(1, 0, 'John Smith');
$worksheet->write(1, 1, 30);
$worksheet->write(2, 0, 'Johann Schmidt');
$worksheet->write(2, 1, 31);
$worksheet->write(3, 0, 'Juan Herrera');
$worksheet->write(3, 1, 32);

// Let's send the file
$workbook->close();
*/
?>