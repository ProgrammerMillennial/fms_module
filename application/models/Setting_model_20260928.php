<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/RdoDic_Master.php");

class Setting_model extends RdoDic_Master {

	// public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}


	public function getHeadSettingBarcode($barcode, $stat){
		if($stat == 0){
			if(substr($barcode,0,1) == 'B'){
			$sql = "select INSD_LINE line, INSD_CODE mat_code, INSD_BQTY qtyakhir, INSD_NAME matname, TBQR_PLCD area
					FROM ".$this->inspectd2." A
					LEFT  JOIN ".$this->palette_inspection." B ON B.TBQR_LINE = A.INSD_LINE  
					WHERE INSD_LINE = '".$barcode."'";
			}else{
			$sql = "select CONVERT(VARCHAR(255),a.line) line, a.headerMatCode COLLATE Korean_Wansung_CI_AS mat_code,  a.qtyakhir, '' matname, TBQR_PLCD area
					FROM ".$this->inspectd." A
					LEFT  JOIN ".$this->palette_inspection." B ON B.TBQR_LINE = A.headerMatCode  
					WHERE CONVERT(VARCHAR(255),a.line) = '".$barcode."'";
			}
		}else{
			$sql = "select CONVERT(VARCHAR(255),INSD_LIN2) line, INSD_CODE mat_code, INSD_BQTY qtyakhir, INSD_NAME matname, TBQR_PLCD area
					FROM ".$this->inspectd2." A
					LEFT  JOIN ".$this->palette_inspection." B ON B.TBQR_LINE = A.INSD_LINE  
					WHERE CONVERT(VARCHAR(255),INSD_LIN2) = '".$barcode."'";
		}

		return $this->db->query($sql)->result();
	}


    public function countInspection($line) {
        $this->db->where('TBQR_LINE', $line);
        return $this->db->count_all_results($this->palette_inspection);
    }

    public function saveInspectionData($serial, $user, $divi, $plcd) {
        $cnt = $this->countInspection($serial);

        $data = array(
            'TBQR_PLCD' => $plcd,
            'TBQR_LINE' => $serial,
            'TBQR_USER' => $user,
            'TBQR_DTTM' => date('Y-m-d H:i:s'),
            'TBQR_DIVI' => $divi
        );

       
        if ($cnt == 0) {
            $this->db->set($data);
            return $this->db->insert($this->palette_inspection);
        } else {
      
            $param = array(
                'TBQR_LINE' => $serial
            );
            $this->db->where($param);
            return $this->db->update($this->palette_inspection, $data);
        }
    }


    
	public function checkPcardLocation($barcode,$wh='SET'){

	$cnt = 0;
	$sql = "SELECT COUNT(tpi.TBQR_LINE) IDXX
			FROM  prtmmrp.prtm.TO_PALETTE_Inspection tpi
			WHERE tpi.TBQR_PLCD ='". $wh ."'
			and   tpi.TBQR_LINE ='". $barcode ."'";

		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->IDXX;	
		}
		return $cnt;
	}



	// public function addRoleMenu($module, $menuid, $menupg, $menuname, $menudt, $grno, $mnno){
	// 	$cnt = $this->countMenu($module, $menuid, $menupg);
	// 	$name = $this->getMenuName($module, $menuid, $menupg);

	// 	$data = array(
	// 				'MENU_MODULE'=>$module,
	// 				'MENU_USERID'=>'ADMIN', 
	// 				'MENU_GROUP'=>$grno, 
	// 				'MENU_GRNM'=>$mnno, 
	// 				'MENU_MENUID'=>$menuid, 
	// 				'MENU_PGMID'=>$menupg,
	// 				'MENU_NAME'=>$menuname,
	// 				'MENU_DATE'=>$menudt,
	// 				'MENU_READ'=> '1',
	// 		        'MENU_INQUERY'=> '1',
	// 		        'MENU_INSERT'=> '1',
	// 		        'MENU_UPDATE'=> '1',
	// 		        'MENU_DELETE'=> '1',
	// 		        'MENU_PRINT'=> '1',
	// 		        'MENU_PASS'=> ''
	// 			);
	// 	if($cnt == 0){
	// 		$this->db->set($data);
	// 		$this->db->insert($this->mainmenu);
	// 	}else{
	// 		if($menuname != $name){
	// 			$param=array(
	// 				'MENU_MODULE'=>$module,
	// 				'MENU_MENUID'=>$menuid, 
	// 				'MENU_PGMID'=>$menupg,
	// 			);
	// 			$this->db->where($param);
	// 			$this->db->update($this->mainmenu, $data);
	// 		}
	// 	}

	// }

	// public function updateInspectPO($list, $wh, $totalBarcode, $pono, $matl, $seqn){

	// 	$param=array('POMD_PONO'=>$pono,'POMD_CODE'=>$matl,'POMD_SEQN'=>$seqn);
	// 	$data2 = array('POMD_INSP_QTTY'=>floatval($totalBarcode));

	// 	if($wh=='MAT'){
	// 		$this->db->where($param);
	// 		$this->db->update($this->pomxxd, $data2);
	// 	}else{
	// 		$this->db->where($param);
	// 		$this->db->update($this->pomexd, $data2);
	// 	}

	// 	$rowsaffected= $this->db->affected_rows();
	// 	if (floatval($rowsaffected) == 0) {
	// 		$msg = 'error';
	// 		return $msg;
	// 	}

	// 	foreach($list as $l){
	// 		$data = array(
	// 				'header_line'=>$l['INSD_LINE'],
	// 				'headerPO'=>$l['INSD_PONO'], 
	// 				'headerSEQH'=>$l['SEQH'], 
	// 				'headerSEQN'=>$l['SEQN'], 
	// 				'header_matl'=>$l['INSD_CODE'] 
	// 			);
	// 		// $totalBarcode = $totalBarcode + $l->INSD_IQTY;

	// 		if($wh=='MAT'){
	// 			$this->db->set($data);
	// 			$this->db->insert($this->pomxxd_inspect2);
	// 		}else{
	// 			$this->db->set($data);
	// 			$this->db->insert($this->pomexd_inspect2);
	// 		}

	// 		$rowsaffected= $this->db->affected_rows();
	// 		if (floatval($rowsaffected) == 0) {
	// 			$msg = 'error';
	// 			return $msg;
	// 		}
	// 	}
	// }





	// public function saveTransdErp($mParam, $mrno, $part, $list, $Ttlqtty){
	// $stat = 0;
	// $out = 0;
	// $ttlOut = 0;
	// 	foreach($list as $lt){
	// 		$stat = $this->cekTransdErp($mrno, $lt->INSD_CODE);
	// 	}	

	// 	if($stat == 0){
	// 		foreach($list as $lt2){
	// 			$data = array(
	// 					'TRND_MRNO'=>$mrno,
	// 					'TRND_CODE'=>$lt2->INSD_CODE, 
	// 					'TRND_SIZE'=>'', 
	// 					'TRND_LINE'=>'', 
	// 					'TRND_QTTY'=>floatval($lt2->INSD_BQTY), 
	// 					'TRND_RMKS'=>'',
	// 					'TRND_UNIT'=>$lt2->INSD_UNIT,
	// 					'TRND_PART'=>$part,
	// 					'TRND_CDAL'=> '',
	// 			        'REAL_QTTY'=> '0'
	// 				);

	// 		$this->db->set($data);
	// 		$this->db->insert($this->transd);
	// 		$rowsaffected= $this->db->affected_rows();
	// 			if (floatval($rowsaffected) == 0) {
	// 				$msg = 'error';
	// 			    return $msg;
	// 			}
	// 		}
	// 	}else{
	// 		foreach($list as $lt2){
	// 			// $out = $this->cekTtlTransdErp($mrno, $lt2->INSD_CODE);
	// 			// $ttlOut = floatval($out) + floatval($lt2->INSD_BQTY);
	// 			$out = $this->otspectd->totalOutbyMatl2($mrno, $lt2->INSD_CODE);
	// 			$ttlOut = $out;

	// 			$param=array('TRND_MRNO'=>$mrno,'TRND_CODE'=>$lt2->INSD_CODE);
	// 			$data2 = array('TRND_QTTY'=>floatval($ttlOut));

	// 			$this->db->where($param);
	// 			$this->db->update($this->transd, $data2);
	// 			$rowsaffected= $this->db->affected_rows();
	// 			if (floatval($rowsaffected) == 0) {
	// 				$msg = 'error';
	// 			    return $msg;
	// 			}
	// 		}
	// 	}
	// 	return $out;
	// }

	// public function deleteTranshBymr($mrno){
	// 	$details= array('TRNH_MRNO'=>$mrno);
	// 	$this->db->where($details);
	// 	$this->db->delete($this->transh);
	// 	$rowsaffected= $this->db->affected_rows();
	// 	if (floatval($rowsaffected) == 0) {
	// 		$msg = 'error';
	// 		return $msg;
	// 	}
	// }



}

/* End of file Master_model.php */
/* Location: ./application/models/Master_model.php */