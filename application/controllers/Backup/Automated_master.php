<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Cglobal.php");

class Automated_master extends Cglobal {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('automated','attd');
	}

	public function index()
	{
		
	}

	public function convertArrayIn($list){
		$line = '';
		foreach ($list as $l){
			$line = $line . ", " . "'". $l[0]. "'";		
		}
		$line = ltrim($line, ',');
	}

	public function scanVendorByCode(){
		$data = array();
		$listLine = array();
		$cnt_sj = 0;
		$barcode = $this->uri->segment(3);
		$lst = $this->attd->getlineSJVendorPM();
		$line = $this->convertArrayIn($lst);
		// foreach ($lst as $l){
		// 	$lineSj = array();
		// 	$lineSj['lineSJ'] = $l->LineSj;
		// 	$data[] = $lineSj;
		// }
		// $list = $this->attd->CeklineSJVendorIR($data);
		// foreach ($list as $lst2){
		// 	$cnt_sj = $lst2->CNT;
		// }
		// if ($cnt_sj > 0){
		// 	$list2 = $this->attd->getSuratJalanVendorIR($data);
		// 	foreach ($list2 as $ls){
		// 		$dt = array(
		// 			'LineSj'=>$ls->LineSj,
		// 			'noSuratJalan'=>$ls->noSuratJalan, 
		// 			'vendor'=>$ls->vendor, 
		// 			'tanggal'=>$ls->tanggal, 
		// 			'totalSJ'=>$ls->totalSJ, 
		// 			'totalScan'=>$ls->totalScan,
		// 			'createDate'=>$ls->createDate,
		// 			'createUser'=>$ls->createUser,
		// 			'createHost'=> $ls->createHost,
		// 	        'updateDate'=> $ls->updateDate,
		// 	        'updateUser'=> $ls->updateUser,
		// 	        'updateHost'=> $ls->updateHost,
		// 	        'vendor2'=> $ls->vendor2,
		// 	        'ApiProcess'=> $ls->ApiProcess,
		// 	        'FACTORY'=> $ls->FACTORY
		// 		);
		// 		$listLine[] = $dt;
		// 	}
		// }
		// var_dump($list);
		// $list = $this->attd->getdetailPoVend($barcode);
		echo json_encode($line);
	}

}

/* End of file  */
/* Location: ./application/controllers/ */