<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Inspectiond_split_Model.php");

class Outinspectd_model extends Inspectiond_split_Model {

	// var $table1 =  'PRTMMRP.PRTM.TM_MO_INSPECTD';
	// var $table2 =  'PRTMMRP.PRTM.TM_MO_INSPECTH';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function totalOutbyMatl($mrno,$code,$flag = 0){
		$sql="select ONSD_MRNO, ONSD_CODE, SUM(ONSD_QTTY) OQTY
			  FROM ".$this->outbarcoded2;
		if($flag == 0){
			$sql.="\nWHERE ONSD_MRNO = '".$mrno."'";
		}else{
			$sql.="\nWHERE ONSD_MRNO IN (".$mrno.")";
		}
		
		if($code != ''){
			$sql.="AND ONSD_CODE = '".$code."'";	
		}

		$sql.="GROUP BY ONSD_CODE, ONSD_MRNO";
		return $this->db->query($sql)->result();
	}

	public function totalOutbyMatl2($mrno,$code){
		$ttl = 0;
		$sql="select ONSD_MRNO, ONSD_CODE, SUM(ONSD_QTTY) OQTY
			  FROM ".$this->outbarcoded2."
			  WHERE ONSD_MRNO = '".$mrno."'";
			  if($code != ''){
			  	$sql.="AND ONSD_CODE = '".$code."'";	
			  }
		$sql.="GROUP BY ONSD_CODE, ONSD_MRNO";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$ttl = $d->OQTY;	
		}
		return $ttl;
	}

	public function loadSqnOutmatl($id){
		$sql="select COUNT(*) SEQN
			  FROM ".$this->outbarcoded2."
			  WHERE ONSD_IDXX = '".$id."'";
		return $this->db->query($sql)->result();
	}

	public function getLastSeqnOut($id){
		$dtl = $this->loadSqnOutmatl($id);
		foreach($dtl as $dt){
			$seqn = $dt->SEQN;
		}
		return $seqn;
	}

	public function cekBarcodeOut($id, $code = ''){
	$cnt = 0;
	if(substr($id,0,1) =='B'){
		$sql = "select SUM(c0.IDXX) IDXX
				FROM(
						SELECT COUNT(headerID) IDXX
						FROM ". $this->outbarcoded ." WHERE CONVERT(VARCHAR(255),InspectionD_line)='". $id ."'
						UNION ALL
						select COUNT(ONSD_IDXX) IDXX
						FROM ". $this->outbarcoded2 ." WHERE ONSD_LINE='". $id ."'
					) c0";
	}else{
		$sql = "select SUM(c0.IDXX) IDXX
				FROM(
						SELECT COUNT(headerID) IDXX
						FROM ". $this->outbarcoded ." 
						WHERE CONVERT(VARCHAR(255),InspectionD_line)='". $id ."'
						AND header_MR_mateial LIKE '". $code ."%'
						UNION ALL
						select COUNT(ONSD_IDXX) IDXX
						FROM ". $this->outbarcoded2 ." 
						WHERE ONSD_LINE='". $id ."'
						AND ONSD_CODE LIKE '". $code ."%'
					) c0";
	}
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->IDXX;	
		}
		return $cnt;
	}

	public function outSavedD($dh, $mParam, $list, $seqn, $pono, $mr_num = ''){
	$mrno = '';
	$mrno2 = '';
	$details2 = array();
		foreach($list as $lt){
			foreach ($mParam as $ls) {
				$mrno = $ls['mrno'];
				if($mrno2 != $mrno){
					$line = $ls['barcode'];
					$vend_stat = $ls['vend_stat'];
						$details=array(
			    					'ONSD_IDXX'=>$dh,
			    					'ONSD_MRNO'=>$ls['mrno'],
			    					'ONSD_SEQN'=>$seqn,
			    					'ONSD_LINE'=>$lt->INSD_LINE,
			    					'ONSD_CODE'=>$lt->INSD_CODE,
			    					'ONSD_QTTY'=>floatval($lt->INSD_BQTY),
			    					'ONSD_UNIT'=>$lt->INSD_UNIT,
			    					'ONSD_PONO'=>$pono,
			    					'ONSD_MRN2'=>$mr_num
			    				);

						$this->db->set($details);
						$this->db->insert($this->outbarcoded2);

		                if ($this->db->trans_status() === FALSE) {
		                    $msg = 'error';
							return $msg;
		                }

						$rowsaffected= $this->db->affected_rows();
						if (floatval($rowsaffected) == 0) {
							$msg = 'error';
							return $msg;
						} 
						array_push($details2,array(
				            'ONSD_LINE'=>$line,
				            'ONSD_CODE'=>$lt->INSD_CODE,
				            'ONSD_NAME'=>$lt->INSD_NAME,
				            'ONSD_UNIT'=>$lt->INSD_UNIT,
				            'ONSD_QTTY'=>floatval($lt->INSD_BQTY),
				            'ACTION' => '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_matl('."'".$line."', '".$vend_stat."', this".')"><i class="fas fa-trash"></i></a>'
				        ));
					// }
				}
					$mrno2 = $mrno;
			}
		}
		return $details2;
	}

	public function delete_detail_out($param)
	{
		foreach($param as $dm){
			$details= array(
					        'ONSD_IDXX'=>$dm['id'],
					        'ONSD_MRNO'=>$dm['mrno'],
					        'ONSD_LINE'=>$dm['barcode']
					  );
		}
		$this->db->where($details);
		$this->db->delete($this->outbarcoded2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function delete_D_all($id)
	{
		$this->db->where('ONSD_IDXX', $id);
		$this->db->delete($this->outbarcoded2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function loadDatabyid($tranid, $mrno){
		$sql="select ONSD_LINE, ONSD_CODE, COALESCE(B.INSD_NAME,C.INSD_NAME) INSD_NAME,  ONSD_UNIT, SUM(ONSD_QTTY) QTTY, CASE WHEN COALESCE(B.INSD_LIN2,C.INSD_LIN2)!='' THEN 1 ELSE 0 END VEND_STAT
			  FROM ".$this->outbarcoded2." A
			  LEFT JOIN ".$this->inspectd2." B ON A.ONSD_LINE = B.INSD_LINE
			  								  AND B.INSD_LIN2 = 0
			  LEFT JOIN ".$this->inspectd2." C ON A.ONSD_LINE = CONVERT(VARCHAR(100),C.INSD_LIN2)
			  								  AND C.INSD_LIN2 != 0
			  WHERE ONSD_IDXX = '".$tranid."'
			  AND ONSD_MRNO = '".$mrno."'
			  GROUP BY ONSD_LINE, ONSD_CODE, ONSD_UNIT, COALESCE(B.INSD_LIN2,C.INSD_LIN2), COALESCE(B.INSD_NAME,C.INSD_NAME)";
		return $this->db->query($sql)->result();
	}

	public function getTransactionOutBacode($tgl1, $tgl2, $wh){
		$sql = "select a0.kode, max(a0.warehouse) as wh, sum(a0.qtty) as out_qtty,max(a0.unit) as UNIT                
				from (
						select  a.ONSH_WHID AS warehouse, b.ONSD_CODE collate database_default as kode, b.ONSD_QTTY AS qtty, b.ONSD_UNIT AS unit
						from    ". $this->outbarcodeh2 ." as a
						inner join ". $this->outbarcoded2 ." as b on a.ONSH_IDXX=b.ONSD_IDXX
						where a.ONSH_DATE BETWEEN '". $tgl1 ."' AND '". $tgl2 ."'
					 )a0
				WHERE 1=1
				AND a0.warehouse = '". $wh ."'
				group by  a0.kode";
		return $this->db->query($sql)->result();
	}

}

/* End of file  */
/* Location: ./application/models/ */