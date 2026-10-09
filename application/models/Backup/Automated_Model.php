<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Automated_Model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		// $this->conn_ir();
		// $this->db2 = $this->load->database('connir', TRUE);
	}

	public function getlineSJVendorPM(){
		$sql = "select LineSj FROM ITERP.PRTM.SuratJalanVendor";
		return $this->db->query($sql)->result();
	}

	// public function CeklineSJVendorIR($list){
	// 	$line = '';
	// 	foreach ($list as $l){
	// 		$line = $line . ", " . "'". $l['lineSJ']. "'";		
	// 	}
	// 	$line = ltrim($line, ',');

	// 	if ($line != ''){
	// 	$sql = "select count(*) as CNT from ITERP.PRTM.SuratJalanVendor
	// 			where factory = 'PM'
	// 			and LineSj not in (". $line .")";

	// 	}else{
	// 	$sql = "select count(*) as CNT from ITERP.PRTM.SuratJalanVendor
	// 			where factory = 'PM'";
	// 	}
	// 	return $this->db2->query($sql)->result();
	// }

	// public function getlineSJVendorIR($list){
	// 	$line = '';
	// 	foreach ($list as $l){
	// 		$line = $line . ", " . "'". $l['lineSJ']. "'";		
	// 	}
	// 	$line = ltrim($line, ',');

	// 	if ($line != ''){
	// 	$sql = "select * from ITERP.PRTM.SuratJalanVendor
	// 			where factory = 'PM'
	// 			and LineSj in (". $line .")";

	// 	}else{
	// 	$sql = "select * from ITERP.PRTM.SuratJalanVendor
	// 			where factory = 'PM'";
	// 	}
	// 	return $this->db2->query($sql)->result();
	// }

	public function getdetailPoVend($barcode){
		$sql = "select noSuratJalan, vendor, tanggal, totalSJ, C.FACTORY, A.linebarcode, B.Pono, A.LineItem, B.itemId, B.satuan, A.qtty, B.Kemasan, B.jmlKemasan
				FROM ITERP.PRTM.suratJalanVendorDetailBarcode A
				LEFT JOIN ITERP.PRTM.suratJalanVendorDetail B ON A.LineSj = B.LineSj
																		   AND A.LineItem = B.LineItem
				LEFT JOIN ITERP.PRTM.SuratJalanVendor C ON B.LineSj = C.LineSj
				WHERE A.linebarcode ='". $barcode ."'
				AND C.FACTORY = 'PM'";

		return $this->db->query($sql)->result();
	}

}

/* End of file  */
/* Location: ./application/models/ */