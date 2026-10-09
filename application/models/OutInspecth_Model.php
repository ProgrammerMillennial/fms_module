<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Outinspectd_model.php");

class Outinspecth_model extends OutInspectd_Model {

	// var $table1 =  'PRTMMRP.PRTM.TM_MO_INSPECTH';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function checkOutIdHeader($id){
	$cnt = 0;
		$sql = "select COUNT(ONSH_IDXX) IDXX
				FROM ". $this->outbarcodeh2 ." WHERE ONSH_IDXX='". $id ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->IDXX;	
		}
		return $cnt;
	}

	public function getOtNewIdHeader($proc){
		$sql = "select CONVERT(VARCHAR(10),GETDATE(), 112) bln, COUNT(ONSH_IDXX) NOMER
				FROM ". $this->outbarcodeh2 ." WHERE LEFT(LTRIM((REPLACE(ONSH_IDXX,'". $proc ."',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)";

		return $this->db->query($sql)->result();
	}

	public function getOtIdHeader($proc){
		$sql = "select TOP 1 CONVERT(VARCHAR(10),GETDATE(), 112) bln, CONVERT(INT,RIGHT(ONSH_IDXX,5)) NOMER
				FROM ". $this->outbarcodeh2 ." WHERE LEFT(LTRIM((REPLACE(ONSH_IDXX,'". $proc ."',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)
				ORDER BY CONVERT(INT,RIGHT(ONSH_IDXX,5)) DESC";

		return $this->db->query($sql)->result();
	}

	public function outSavedH($param, $ttl, $Ttlqtty, $proc, $mrnum = ''){
	   	$mrno2 = '';
	   	$id = '';
	   	$details = array();
	   	$idH = $this->getOtNewIdHeader($proc);

		foreach ($idH as $value) 
		{
			$urut = $value->NOMER + 1;
			if($proc == 'RJT'){
				$line = 'RJT'. $value->bln.'-'. sprintf("%05s", $urut);
				
			}else{
				$line = 'TMR'. $value->bln.'-'. sprintf("%05s", $urut);
			}
		}
		$ck = $this->checkOutIdHeader($line);
		if($ck == 1){
			$idH = $this->getOtIdHeader($proc);
			foreach ($idH as $value) 
			{
				$urut = $value->NOMER + 1;
				if($proc == 'RJT'){
					$line = 'RJT'. $value->bln.'-'. sprintf("%05s", $urut);
					
				}else{
					$line = 'TMR'. $value->bln.'-'. sprintf("%05s", $urut);
				}
			}
		}

		foreach ($param as $ls) {
			$mrno =$ls['mrno'];
			if($mrno2 != $mrno){
				$id = $ls['id'];
				if($id != ''){
					$line = $id;
				}
				if($id == ''){
					$details=array(
		    					'ONSH_IDXX'=>$line,
		    					'ONSH_WHID'=>$ls['wh'],
		    					'ONSH_DATE'=>$ls['tgl'],
		    					'ONSH_PART'=>$ls['part'],
		    					'ONSH_NBRN'=>$ls['nbrn'],
		    					'ONSH_OPCD'=>$ls['opcd'],
		    					'ONSH_FACT'=>$ls['fact'],
		    					'ONSH_MRNO'=>$ls['mrno'],
		    					'ONSH_MRN2'=>$mrnum,
		    					'ONSH_TQTY'=>floatval($Ttlqtty),
								'CREATE_USER'=>$ls['user'],
								'CREATE_DATE'=>date("Y-m-d h:i:s")
		    				);
			    }else{
					$details= array(
					            'ONSH_TQTY'=>floatval($Ttlqtty)
					        );
					$param= array(
					            'ONSH_IDXX'=>$id
					        );
			    }
			}
			$mrno2 = $mrno;
	    }

	    if($id == ''){
	    	if($ttl != 0){
			    $this->db->set($details);
				$this->db->insert($this->outbarcodeh2);	  
				$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
					return $msg;
				}  		
			}
		}else{
			if($ttl != 0){
				$msg = $this->updateTotal($param, $details);
				if($msg == 'error'){
					return $msg;
				}
			}else {
				$msg = $this->delete_H_all($id);
				if($msg == 'error'){
					return $msg;
				}
			}
		}

	    return $line;
	}

	public function updateTtlOut2($id, $Ttlqtty){
		$details= array(
					    'ONSH_TQTY'=>floatval($Ttlqtty)
					   );
		$param= array(
					    'ONSH_IDXX'=>$id
					 );
		$rt = $this->updateTotal($param, $details);
		return $rt;
	}

	public function updateTotal($param, $details){
		$this->db->where($param);
		$this->db->update($this->outbarcodeh2, $details);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function delete_H_all($id)
	{
		$this->db->where('ONSH_IDXX', $id);
		$this->db->delete($this->outbarcodeh2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function loadDataOutBarcode($mrno, $dt1, $dt2, $opcd, $fact, $wh){
		$sql = "select B.ONSH_IDXX, A.ONSD_MRNO, B.ONSH_DATE, B.ONSH_NBRN, B.ONSH_PART, B.ONSH_OPCD, C.ROTE_NAME, B.ONSH_FACT, B.ONSH_TQTY, B.ONSH_WHID
				FROM ".$this->outbarcoded2." A
				LEFT JOIN ".$this->outbarcodeh2." B ON A.ONSD_IDXX = B.ONSH_IDXX
				LEFT JOIN ".$this->routem." C ON B.ONSH_OPCD = C.ROTE_OPCD
				WHERE A.ONSD_MRNO != ''";
		// if($trno != ''){
		// 	$sql.= "AND  B.ONSH_IDXX = '". $trno ."'";
		// }
		if($mrno != ''){
			$sql.= "AND A.ONSD_MRNO = '". $mrno ."'";
		}
		if($dt1 != '' && $dt2 != ''){
			$sql.= "AND B.ONSH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
		}
		if($opcd != ''){
			$sql.= "AND B.ONSH_OPCD = '". $opcd ."'";
		}
		if($fact != ''){
			$sql.= "AND B.ONSH_FACT = '". $fact ."'";
		}
		$sql.= "GROUP BY B.ONSH_IDXX, A.ONSD_MRNO, B.ONSH_DATE, B.ONSH_NBRN, B.ONSH_PART, B.ONSH_OPCD, C.ROTE_NAME, B.ONSH_FACT, B.ONSH_TQTY, B.ONSH_WHID";

		return $this->db->query($sql)->result();
	}

	public function getTanggalMrOut($mrno, $id){
		$tanggal = '';
		$sql = "select ONSH_DATE FROM ". $this->outbarcodeh2 ." A LEFT JOIN ". $this->outbarcoded2 ." B ON A.ONSH_IDXX = B.ONSD_IDXX
				WHERE ONSD_MRNO != ''";
		if($mrno != ''){
			$sql.= "AND ONSD_MRNO = '". $mrno ."'";	
		}
		if($id != ''){
			$sql.= "AND ONSH_IDXX = '". $id ."'";
		}
		
		$lt = $this->db->query($sql)->result();
		foreach ($lt as $lst) {
			$tanggal = $lst->ONSH_DATE;
		}

		return $tanggal;
	}


	public function cekOutBarcode($mrno, $date = ''){
		$cnt = 0;
		$sql =" select COUNT(*) CNT
				FROM ". $this->outbarcodeh2 ."
				WHERE ONSH_MRNO = '". $mrno ."'";
		if($date != ''){
			$sql .="AND ONSH_DATE = '". $date ."'";
		}

		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}
}

/* End of file  */
/* Location: ./application/models/ */