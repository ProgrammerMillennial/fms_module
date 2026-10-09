<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Inspectiond_split_Model.php");

class OutInspectd_Model extends Inspectiond_split_Model {

	// var $table1 =  'PRTMMRP.PRTM.TM_MO_INSPECTD';
	// var $table2 =  'PRTMMRP.PRTM.TM_MO_INSPECTH';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function totalOutbyMatl($mrno,$code){
		$sql="select ONSD_MRNO, ONSD_CODE, SUM(ONSD_QTTY) OQTY
			  FROM ".$this->outbarcoded2."
			  WHERE ONSD_MRNO = '".$mrno."'";
			  if($code != ''){
			  	$sql.="AND ONSD_CODE = '".$code."'";	
			  }
		$sql.="GROUP BY ONSD_CODE, ONSD_MRNO";
		return $this->db->query($sql)->result();
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

	public function outSavedD($dh, $mParam, $list, $seqn){
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
			    					'ONSD_UNIT'=>$lt->INSD_UNIT
			    				);

						$this->db->set($details);
						$this->db->insert($this->outbarcoded2);
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
	}

	public function delete_D_all($id)
	{
		$this->db->where('ONSD_IDXX', $id);
		$this->db->delete($this->outbarcoded2);
	}

	public function loadDatabyid($tranid, $mrno){
		$sql="select ONSD_LINE, ONSD_CODE, SUM(ONSD_QTTY) QTTY, ONSD_UNIT
			  FROM ".$this->outbarcoded2."
			  WHERE ONSD_IDXX = '".$tranid."'
			  AND ONSD_MRNO = '".$mrno."'
			  GROUP BY ONSD_LINE, ONSD_CODE, ONSD_UNIT";
		return $this->db->query($sql)->result();
	}

}

/* End of file  */
/* Location: ./application/models/ */