<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inspection_master extends CI_Model {

	var $inspecth =  'PRTMMRP.PRTM.TO_MI_Inspection';
	var $inspectd =  'PRTMMRP.PRTM.TO_MI_InspectionD';
	var $pomxxh =  'PRTMMRP.PRTM.TO_POMXXH';
	var $pomxxd =  'PRTMMRP.PRTM.TO_POMXXD';
	var $pomexh =  'PRTMMRP.PRTM.TO_POMEXH';
	var $pomexd =  'PRTMMRP.PRTM.TO_POMEXD';
	var $whakses =  'PRTMMRP.PRTM.WarehouseAkses';
	var $routem =  'PRTMERP.PRTM.TP_ROUTEM';
	var $location =  'PRTMMRP.PRTM.TO_PALETTE_MSCODE';
	var $inspecth2 =  'PRTMMRP.PRTM.TM_MI_INSPECTH';
	var $inspectd2 =  'PRTMMRP.PRTM.TM_MI_INSPECTD';
	var $outbarcodeh =  'PRTMMRP.PRTM.TO_OUT_Barcode_Inspection';
	var $outbarcoded =  'PRTMMRP.PRTM.TO_OUT_Barcode_InspectionD';
	var $model =  'PRTMERP.PRTM.TB_MODELD';
	var $vendor =  'PRTMERP.PRTM.TC_VENDXM';
	var $transh =  'PRTMMRP.PRTM.TM_TRANSH';
	var $transd =  'PRTMMRP.PRTM.TM_TRANSD';
	var $tmmreqxh =  'PRTMMRP.PRTM.TM_MREQXH';
	var $tmmreqxd =  'PRTMMRP.PRTM.TM_MREQXD';
	var $tmkrnh =  'PRTMMRP.PRTM.TM_KRNH';
	var $outbarcodeh2 =  'PRTMMRP.PRTM.TM_MO_INSPECTH';
	var $outbarcoded2 =  'PRTMMRP.PRTM.TM_MO_INSPECTD';
	var $inspectSplit = 'PRTMMRP.PRTM.TO_MI_InspectionD_Split';
	var $inspectSplit2 = 'PRTMMRP.PRTM.TM_MI_INSPECTD_SPLIT';
	var $tomreqxh =  'PRTMMRP.PRTM.TO_MREQXH';
	var $tomreqxd =  'PRTMMRP.PRTM.TO_MREQXD';
	var $recxxh =  'PRTMMRP.PRTM.TM_RECXXH';
	var $recxxd =  'PRTMMRP.PRTM.TM_RECXXD';
	var $recexh =  'PRTMMRP.PRTM.TM_RECEXH';
	var $recexd =  'PRTMMRP.PRTM.TM_RECEXD';
	var $mainmenu =  'ITERP.PRTM.MAINMENU';
	var $codexd =  'PRTMERP.PRTM.TB_CODEXD';
	var $emplxm =  'PRTMERP.PRTM.TH_EMPLXM';
	var $emp02m =  'PRTMERP.PRTM.TH_EMP02M';
	var $pomxxd_inspect2 =  'PRTMMRP.PRTM.to_pomxxd_inspect2';
	var $pomexd_inspect2 =  'PRTMMRP.PRTM.to_pomexd_inspect2';
	var $sjvendord =  'ITERP.PRTM.suratJalanVendorDetail';
	var $sjvendor =  'ITERP.PRTM.SuratJalanVendor ';
	var $sjvendordb =  'ITERP.PRTM.suratJalanVendorDetailBarcode';
	var $closedt =  'PRTMMRP.PRTM.CLOSE_DT_BARC';
	var $lampxb =  'PRTMMRP.PRTM.TM_LAMPXB';
	var $stylem =  'PRTMERP.PRTM.TB_STYLEM';
	var $trconsxd =  'PRTMERP.PRTM.TR_CONSXD';
	var $tolamxh =  'PRTMMRP.PRTM.TO_LAMXH';
	var $tmstock =  'PRTMMRP.PRTM.TM_BARCODE_STOCK';
	var $seristock =  'PRTMMRP.PRTM.TM_BARCODE_SERIAL';
	var $exraxm =  'PRTMMRP.PRTM.TO_EXRAXM';
	var $irijecth =  'PRTMMRP.PRTM.TO_RJT_Barcode_Inspection';
	var $irijectd =  'PRTMMRP.PRTM.TO_RJT_Barcode_InspectionD';
	var $seriBarcode =  'PRTMMRP.PRTM.TM_SERI_BARCODE_MATERIAL';
	var $inshdetail =  'PRTMMRP.PRTM.TM_MO_INSPETCH_DETAIL';
	var $palette_inspection = 'PRTMMRP.PRTM.TO_PALETTE_Inspection';

}

/* End of file inspection_master.php */
/* Location: ./application/models/inspection_master.php */