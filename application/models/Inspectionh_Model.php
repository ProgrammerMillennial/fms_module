<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
include_once (dirname(__FILE__) . "/Master_model.php");

class Inspectionh_Model extends Master_model {

	// var $table =  'PRTMMRP.PRTM.TM_MI_INSPECTH';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function checkIdHeader($id){
	$cnt = 0;
		$sql = "select COUNT(INSH_IDXX) IDXX
				FROM ". $this->inspecth2 ." WHERE INSH_IDXX='". $id ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->IDXX;	
		}
		return $cnt;
	}

	public function getIdHeader(){
		// $sql = "select CONVERT(VARCHAR(10),GETDATE(), 112) bln, COUNT(INSH_IDXX) NOMER
		// 		FROM ". $this->inspecth2 ." WHERE LEFT(LTRIM((REPLACE(INSH_IDXX,'INH',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)";

		$sql = "select TOP 1 CONVERT(VARCHAR(10),GETDATE(), 112) bln, CONVERT(INT,RIGHT(INSH_IDXX,5)) NOMER
				FROM ". $this->inspecth2 ." WHERE LEFT(LTRIM((REPLACE(INSH_IDXX,'INH',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)
				ORDER BY CONVERT(INT,RIGHT(INSH_IDXX,5)) DESC";

		return $this->db->query($sql)->result();
	}

	public function getNewIdHeader(){
		$sql = "select CONVERT(VARCHAR(10),GETDATE(), 112) bln, COUNT(INSH_IDXX) NOMER
				FROM ". $this->inspecth2 ." WHERE LEFT(LTRIM((REPLACE(INSH_IDXX,'INH',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)";

		return $this->db->query($sql)->result();
	}

	public function CalculateHeader($data, $ttlQtty)
	{	
		
		$sj = '';
		$idH = $this->getNewIdHeader();
			foreach ($idH as $value) {
				$urut = $value->NOMER + 1;
				$line = 'INH'. $value->bln.'-'. sprintf("%05s", $urut);
			}

		$ck = $this->checkIdHeader($line);
		if($ck == 1){
			$idH = $this->getIdHeader();
			foreach ($idH as $value) {
				$urut = $value->NOMER + 1;
				$line = 'INH'. $value->bln.'-'. sprintf("%05s", $urut);
			}
		}


		$details = array();
		$param= array();
		$trans = '';
		$tranno = '';
		$headerLot = null;
		$headerExpired = null;
	    $lotValues = array();
	    $expiredValues = array();
	    foreach ($data as $lst) {
	    	if(!empty($lst['lotnumber'])) {
	    		$lotValues[] = trim($lst['lotnumber']);
	    	}
	    	if(!empty($lst['tgl_expired'])) {
	    		$expiredValues[] = trim($lst['tgl_expired']);
	    	}
	    	if($sj != $lst['sjno']){
	    		$trans = $lst['tranno'];
	    		if($trans != ''){
	    			$line = $lst['tranno'];
	    			if(substr($line,0,4)!="INH-"){
	    				$tranno = $lst['tranno'];
	    			}
	    		}

	    		if($lst['vstat']==0){
			        $pono = $lst['pono'];
			        $release =$lst['release'];
			        $style =$lst['style'];
			        $vend =$lst['vend'];
			        $sjno =$lst['sjno'];
			    }else{
			        $pono = '';
			        $release ='';
			        $style ='';
			        $vend ='';
			        $sjno ='';
			    }
	    		if($tranno == ''){
	    			$details= array(
			            'INSH_IDXX'=>$line,
			            'INSH_PONO'=>$pono,
			            'INSH_PART'=>$release,
			            'INSH_NBRN'=>$style,
			           	'INSH_VEND'=>$vend,
			           	'INSH_SJNO'=>$sjno,
			            'INSH_DATE'=>$lst['tgl_inspect'],
			            'INSH_WHID'=>$lst['wh'],
			            'INSH_VSAT'=>$lst['vstat'],
			            'INSH_TOTAL'=>floatval($ttlQtty),
			            'CREATE_USER'=>$lst['user'],
			            'CREATE_DATE'=>date("Y-m-d h:i:s"),
		            'INSH_LOTX'=>$headerLot,
		            'INSH_EXP_DATE'=>$headerExpired
			        );
	    		}else{
					$details= array(
			            'INSH_SJNO'=>$sjno,
			            'INSH_TOTAL'=>floatval($ttlQtty),
			            'UPDATE_USER'=>$lst['user'],
			            'UPDATE_DATE'=>date("Y-m-d h:i:s")
			        );
			       	$param= array(
			            'INSH_IDXX'=>$tranno
			        );
	    		}
	        }
	        $sj = $lst['sjno'];
	    }

	    if(!empty($lotValues)){
	    	$uniqueLots = array_unique($lotValues);
	    	if(count($uniqueLots) == 1){
	    		$headerLot = reset($uniqueLots);
	    	}else{
	    		$headerLot = null;
	    	}
	    }
	    if(!empty($expiredValues)){
	    	$uniqueExpired = array_unique($expiredValues);
	    	if(count($uniqueExpired) == 1){
	    		$headerExpired = reset($uniqueExpired);
	    	}else{
	    		$headerExpired = null;
	    	}
	    }

	    $details['INSH_LOTX'] = $headerLot;
	    $details['INSH_EXP_DATE'] = $headerExpired;

	    if($tranno == ''){
	    	if($ttlQtty != 0){
			    $this->db->set($details);
				$this->db->insert($this->inspecth2);	
				$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
				    return $msg;
				}
			}
		}else{
			if($ttlQtty != 0){
			$this->db->where($param);
			$this->db->update($this->inspecth2, $details);
				$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
				    return $msg;
				}
			}else {
				
				$this->delete_header($tranno);

			}
		}

	    return $line;
	}

	public function delete_header($id)
	{
		$this->db->where('INSH_IDXX', $id);
		$this->db->delete($this->inspecth2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function updateTotalHeader($param, $data)
	{
		$this->db->where($param);
		$this->db->update($this->inspecth2, $data);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}
}

/* End of file  */
/* Location: ./application/models/ */