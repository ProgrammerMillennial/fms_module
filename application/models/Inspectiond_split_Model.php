<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Inspectiond_model.php");

class Inspectiond_split_Model extends Inspectiond_Model {

	// var $table = 'PRTMMRP.PRTM.TM_MI_INSPECTD_SPLIT';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function saveSplitBarcode($param, $line, $id){

		foreach($param as $lt){
			$data=array(
				'ISSD_IDXX' => $id,
				'ISSD_LINE' => $line,
				'ISSD_NLINE' => $lt['INSD_LINE'],
				'ISSD_QTTY' => $lt['INSD_IQTY'],
			);
			$this->db->set($data);
			$this->db->insert($this->inspectSplit2);
			$rowsaffected= $this->db->affected_rows();
			if (floatval($rowsaffected) == 0) {
				$msg = 'error';
				return $msg;
			}
		}

	}

	public function deleteSplitBarcode($id){
		$this->db->where('ISSD_NLINE', $id);
		$this->db->delete($this->inspectSplit2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}

	}

}

/* End of file  */
/* Location: ./application/models/ */