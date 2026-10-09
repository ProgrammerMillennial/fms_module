<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/OutInspectd_Model.php");

class OutInspecth_Model extends OutInspectd_Model {

	// var $table1 =  'PRTMMRP.PRTM.TM_MO_INSPECTH';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getOtIdHeader($proc){
		$sql = "select CONVERT(VARCHAR(10),GETDATE(), 112) bln, COUNT(ONSH_IDXX) NOMER
				FROM ". $this->outbarcodeh2 ." WHERE LEFT(LTRIM((REPLACE(ONSH_IDXX,'". $proc ."',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)";

		return $this->db->query($sql)->result();
	}

	public function outSavedH($param, $ttl, $Ttlqtty, $proc){
	   	$mrno2 = '';
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
		    					'ONSH_TQTY'=>floatval($Ttlqtty)
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
			}
		}else{
			if($ttl != 0){
				$this->updateTotal($param, $details);
			}else {
				$this->delete_H_all($id);
			}
		}

	    return $line;
	}

	public function updateTotal($param, $details){
		$this->db->where($param);
		$this->db->update($this->outbarcodeh2, $details);
	}

	public function delete_H_all($id)
	{
		$this->db->where('ONSH_IDXX', $id);
		$this->db->delete($this->outbarcodeh2);
	}

	public function loadDataOutBarcode($trno, $mrno, $dt1, $dt2, $opcd, $fact, $wh){
		$sql = "select B.ONSH_IDXX, A.ONSD_MRNO, B.ONSH_DATE, B.ONSH_NBRN, B.ONSH_PART, B.ONSH_OPCD, C.ROTE_NAME, B.ONSH_FACT, B.ONSH_TQTY, B.ONSH_WHID
				FROM ".$this->outbarcoded2." A
				LEFT JOIN ".$this->outbarcodeh2." B ON A.ONSD_IDXX = B.ONSH_IDXX
				LEFT JOIN ".$this->routem." C ON B.ONSH_OPCD = C.ROTE_OPCD
				WHERE A.ONSD_MRNO != ''";
		if($trno != ''){
			$sql.= "AND  B.ONSH_IDXX = '". $trno ."'";
		}
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
}

/* End of file  */
/* Location: ./application/models/ */