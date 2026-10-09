<!DOCTYPE html>
<html lang="en">
<head>
<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
<style>

p.big {
  line-height: 1;
}

table, th, td {
  border: solid ;
  border-collapse: collapse;
}

h1 {
  text-align: center
}


@media print {
  .two {
    column-count: 2;
    -webkit-column-count: 2;
    -moz-column-count: 2;
  }
  .thes{
    column-count: 3;
    -webkit-column-count: 3;
    -moz-column-count: 3;
  }
  .one {
    column-count: 1;
    -webkit-column-count: 1;
    -moz-column-count: 1;
  }
  input[type="button"] {
    display: none
  }
}

br {
   display: block;
   margin: 0px 0px;
}

</style>
</head>
<body>
<section class="one" style="line-height: 0.3;">
<?php

require_once APPPATH.'phpqrcode/qrlib.php';



$connection_string = 'DRIVER={SQL Server};SERVER=172.16.160.10;DATABASE=PRTMMRP'; 
$user = 'PRTM'; 
$pass = 'PRTM';  
$conn = odbc_connect( $connection_string, $user, $pass); 

$location = $this->uri->segment(3);
?>

<?php 
  
  $count = 0;
  $a = '';

  $tempdir = FCPATH.'assets/temp/';
  if (!file_exists($tempdir)) {
    mkdir($tempdir, 0777, true);
  }

$sql = odbc_exec($conn,"SELECT * FROM PRTMMRP.PRTM.TO_PALETTE_MSCODE
WHERE LOCATION_TYPE = 'R'
/*AND SUBSTRING(LOCATION_NAME, 3, 1) <> 4*/
AND LOCATION_ROW LIKE '$location'
AND LOCATION_NAME NOT IN ('SET')
ORDER BY LOCATION_GROP, LOCATION_ROW, IS_PANEL, IS_ROW DESC");

while(odbc_fetch_row($sql)) {

    $codeContents = odbc_result($sql,"LOCATION_CODE");
    $namaFile = odbc_result($sql,"LOCATION_NAME").".png";

    QRcode::png($codeContents, $tempdir.$namaFile, QR_ECLEVEL_H, 10, 3);

    $count++;

   // $sql=odbc_exec($conn,"SELECT * FROM PRTMMRP.PRTM.TO_PALETTE_MSCODE
   //                  WHERE LOCATION_TYPE = 'R'
   //                  AND SUBSTRING(LOCATION_NAME, 3, 1) <> 4
   //                  AND LOCATION_ROW LIKE '$location'
   //                  AND LOCATION_NAME NOT IN ('SET')
   //                  ORDER BY LOCATION_GROP, LOCATION_ROW, IS_PANEL, IS_ROW DESC");

   // while(odbc_fetch_row($sql)) 
   // { 

   //  // $tempdir = base_url();"assets/temp/"; //Nama folder tempat menyimpan file qrcode


   //  if (!file_exists($tempdir)) //Buat folder bername temp
   //      mkdir($tempdir);

            // //isi qrcode jika di scan
            // $codeContents = odbc_result($sql,"LOCATION_CODE");
            // //nama file qrcode yang akan disimpan
            // $namaFile=odbc_result($sql,"LOCATION_NAME").".png";
            // //ECC Level
            // $level=QR_ECLEVEL_H;
            // //Ukuran pixel
            // $UkuranPixel=10;
            // //Ukuran frame
            // $UkuranFrame=3;

            // QRcode::png($codeContents, $tempdir.$namaFile, $level, $UkuranPixel, $UkuranFrame);

    // $count = $count + 1;

?>
<table width="100%" style="line-height: 1;">

<?php
  if($a != substr(odbc_result($sql,"LOCATION_CODE"),5,5))
  {
?>
    <tr>
        <td align="text-center" colspan="2" style="line-height: 1;">
          <p style="line-height: 1;"></p>
           <p style="text-align: center; font-weight: bold; font-size: 22px; line-height: 2;">
            <img src="<?php echo base_url(); ?>assets/images/logo.png" width="35" height="27">
              PM LOCATION RACK CODE
           </p>
        </td>
    </tr>
<?php
 }
?>
    <tr>
        <td style ="width: 110px; height: 110px">
            <center><img src="<?php echo base_url(); ?>assets/temp/<?php echo odbc_result($sql,"LOCATION_NAME"); ?>.png"></center>
        </td>
        <td align="text-center" style="line-height:0.2;">
            <center><p style="font-size: 90px; font-weight: bold; line-height:0.2;"><?php echo odbc_result($sql,"LOCATION_NAME"); ?> &nbsp;&nbsp;<img src="<?php echo base_url(); ?>assets/images/panah.png" width="100" height="100"></p></center>
        </td>
    </tr>
</table>

<?php
  if($count == 3)
  {
    $count = 0;
?>
<br style="line-height: 75px;">

<?php
}
?>

<?php
  $a = substr(odbc_result($sql,"LOCATION_CODE"),5,5);
  }
?>
</section>
 <br style="line-height:100px;">
<script>
    window.print()
</script>

</body>