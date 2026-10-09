<html>
	<head>
		<style>

		p.big {
		  font-family: Arial;
		  margin-top: 1px;
		}

		table, th, td {
		  margin-top: 1px;
		  border: 1px solid black;
		  border-collapse: collapse;
		  padding: 0;
		}

		.fifty-chars {
		    width: 58ch;
		    overflow: hidden;
		    white-space: nowrap;
		    text-overflow: ellipsis;
		}

		</style>
	</head>
<body>
<?php
$data = "";
if(isset($print)){
	foreach ($print as $lst){

	$header =substr($lst['headerMatCode'],0,1);
	if ($header !== 'X'){

?>
	<table width="100%" style="border-width:: 2px; font-family: Arial;" >
    <tr>
      	<td width="4%" style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">FACTORY</p></center></td>
      	<td style="border-width: 2px;"><center><p style="font-size: 25px; font-weight: 700;"><?php echo trim($lst['fact']); ?></p></center></td>
      	<td rowspan="2" colspan="0" width="5%" style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">LABEL ID</p></center></td>
      	<td rowspan="2" colspan="0" style="border-width: 2px;"><center><img src="http://172.16.160.3/fms_module/Transaction/generateQRcode/<?php echo $lst['line']; ?>" width="50" height="50"></center><center><p style="font-size: 7px; font-weight: 700;"><?php echo $lst['line']; ?></p></center></td>
    </tr>
    <tr>
    </tr>
    <tr>
    		<td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">ITEM CODE</p></center></td>
      	<td colspan="2" width="4%" style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;"><?php echo $lst['headerMatCode']; ?></p></center></td>
      	<td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;"><?php echo $lst['color']; ?></p></center></td>
    </tr>
    <tr>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">DESCRIPTION</p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 8px; font-weight: 700;"><?php echo $lst['nama']; ?></p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">SPEC</p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 9px; font-weight: 700;"><?php echo $lst['spec']; ?></p></center></td>
    </tr>
    <tr>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">PO ID</p></center></td>
        <td style="border-width: 2px;"><center><B><p style="font-size: 9px; font-weight: 700;"><?php echo $lst['headerPONumber']; ?></p></B></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">RELEASE</p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;"><?php echo $lst['release']; ?></p></center></td>
    </tr>
    <tr>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">STYLE</p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 9px; font-weight: 700;"><?php echo $lst['style']; ?></p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">MODEL</p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;"><?php echo $lst['model']; ?></p></center></td>
    </tr>
    <tr>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">QTY</p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 9px; font-weight: 700;"><?php echo $lst['QtyAkhir']; ?></p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">UNIT : <?php echo $lst['unit']; ?></p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">WIDE : <?php echo $lst['wide']; ?></p></center></td>
    </tr>
    <tr>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">VENDOR</p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;"><?php echo $lst['vend_desc']; ?></p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;">TYPE</p></center></td>
        <td style="border-width: 2px;"><center><p style="font-size: 7px; font-weight: 700;"><?php echo $lst['tipe']; ?></p></center></td>
    </tr>
 </table>
<?php
	}else{

     $data.= "\nCT~~CD,~CC^~CT~^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ
                \n^XA
                \n^MMT
                \n^PW424
                \n^LL0256
                \n^LS0
                \n^FT32,58^A0N,31,31\^FH\^FD(".$lst['headerPONumber'].")___".$lst['fact']."^FS
                \n^FT30,250\^BQN,2,4
                \n^FH^FDLA,".$lst['line']."^FS
                \n\^FT33,85^A0N,23,16\^FH\^FD".$lst["nama"]."_".$lst["tipe"]."^FS  
                \n\^FT33,105^A0N,23,16\^FH\^FD".$lst["headerMatCode"]."^FS                                   
                \n\^FT167,128^A0N,20,24\^FH\^FD".$lst["spec"]."_".$lst["wide"]."^FS
                \n\^FT167,160^A0N,20,19\^FH\^FD".$lst['QtyAkhir']."_".$lst['unit']."^FS
                \n\^FT167,188^A0N,20,19\^FH\^FD(".trim($lst['line']).")".$lst['release']."^FS
                \n\^FT167,208^A0N,20,19\^FH\^FD(".$lst['vend_desc'].")^FS
                \n^PQ1,0,1,Y
                \n^XZ";

     echo $data;

	}

	// $data.="\n^XA
	// 		\n^LH140,10
	// 		\n^FO5,6^GB530,6,2^FS
	// 		\n^FO5,5^GB530,0,3^FS

	// 		\n^FO10,50^A0N,20,15^FE^FDFACTORY^FS 
	// 	    \n^FO10,140^FB120,20,15^FE^FDITEM CODE^FS 
	// 		\n^FO10,200^A0N,20,15^FE^FDDESCRIPTION^FS 
	// 		\n^FO10,260^A0N,20,15^FE^FDPO ID^FS 
	// 		\n^FO10,340^A0N,20,15^FE^FDSTYLE^FS
	// 		\n^FO10,410^A0N,20,15^FE^FDQTY^FS 
	// 		\n^FO10,470^A0N,20,15^FE^FDVENDOR^FS

	// 		\n^FO115,50^A0N,30,70^FE^FD".trim($lst['fact'])."^FS 
	// 		\n^FO110,140^A0N,20,15^FE^FD".$lst['headerMatCode']."^FS 
	// 		\n^CF0,15,15^FO110,180^FB120,3,,^FD".$lst['nama']."^FS 
	// 		\n^FO110,260^FB120,20,15^FE^FD".$lst['headerPONumber']."^FS 
	// 		\n^FO110,340^A0N,20,15^FE^FD".$lst['model']."^FS
	// 		\n^FO120,410^A0N,20,15^FE^FD".number_format($lst['QtyAkhir'],2)."^FS 
	// 		\n^CF0,15,15^FO110,470^FB120,3,,^FD".$lst['vend_desc']."^FS

	// 		\n^FO0,10^GB2,515,4^FS
	// 		\n^FO100,10^GB2,515,4^FS
	// 		\n^FO230,10^GB2,110,4^FS
	// 		\n^FO230,170^GB2,350,4^FS
	// 		\n^FO300,10^GB2,515,4^FS
	// 		\n^FO530,10^GB2,515,4^FS

	// 		\n^FO240,50^A0N,20,15^FE^FDLABEL ID^FS 
	// 		\n^FO240,80^A0N,20,15^FE^FD^FS 
	// 		\n^FO240,200^A0N,20,15^FE^FDSPEC^FS 
	// 		\n^FO240,260^A0N,20,15^FE^FDRELEASE^FS 
	// 		\n^FO240,340^A0N,20,15^FE^FDMODEL^FS
	// 		\n^FO240,410^A0N,20,15^FE^FDUNIT:".$lst['unit']."^FS 
	// 		\n^FO240,470^A0N,20,15^FE^FDTYPE^FS

	// 		\n^FO360,10^BQ,3,4^FDQA,".$lst['line']."^FS
	// 		\n^FO370,105^A0N,20,14^FE^FDKODE:".$lst['line']."^FS 
	// 		\n^FO320,140^A0N,20,15^FE^FD".$lst['color']."^FS 
	// 		\n^FO320,200^A0N,20,15^FE^FD".$lst['spec']."^FS 
	// 		\n^FO320,260^A0N,20,15^FE^FD".$lst['release']."^FS 
	// 		\n^FO320,340^A0N,20,15^FE^FD".$lst['model']."^FS
	// 		\n^FO320,410^A0N,20,15^FE^FDWIDE:".$lst['wide']."^FS 
	// 		\n^FO320,470^A0N,20,15^FE^FD".$lst['tipe']."^FS

	// 		\n^FO5,120^GB530,0,4^FS
	// 		\n^FO5,170^GB530,0,4^FS
	// 		\n^FO5,240^GB530,0,4^FS
	// 		\n^FO5,310^GB530,0,4^FS
	// 		\n^FO5,380^GB530,0,4^FS
	// 		\n^FO5,450^GB530,0,4^FS

	// 		\n^FO5,520^GB530,6,2^FS
	// 		\n^FO5,520^GB530,0,3^FS
	// 		\n^XZ";
	}
	// echo $data;

}else{
	echo"data tidak ada";
}			
?>

<script type="text/javascript">
    function printZpl() {
      window.focus();
      window.print();
  }
  printZpl();
</script>

</body>
</html>