<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Cglobal.php");

class Master extends Cglobal {

	public function __construct()
	{
		parent::__construct();
		// if($this->session->userdata('status') != "login"){
		// 	redirect(base_url());
		// }
	}

	public function index()
	{

	}

	public function listwarehouse()
	{
		$list = $this->master->getWarehouselist();
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row[] = $no++;
			$row[] = $lst->ROTE_OPCD;
			$row[] = $lst->ROTE_NAME;
			$data[] = $row;
		}

		$output = array(
						"data" => $data
				);
		echo json_encode($output);
	}

	public function listlocation()
	{
		$list = $this->master->getWarehouserack();
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row[] = $lst->LOCATION_ROW;
			$row[] = $lst->LOCATION_TYPE;
			$data[] = $row;
		}

		$output = array(
						"data" => $data
				);
		echo json_encode($output);
	}

	public function headTransBarcode($id, $stat)
    {
		$list = $this->master->getHeadTransBarcode($id, $stat);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$nm = $lst->matname;

			if($nm == ''){
				$this->loadMaterial($lst->mat_code);
				$nama = trim($this->getNamaFull()," ");
			}else{
				$nama = $lst->matname;
			}
			
			$row = array();
			$row['line'] = $lst->line;
			$row['nama'] = $nama;
			$row['qtyakhir'] = floatval($lst->qtyakhir);
			$row['matcode'] = $lst->mat_code;
			$row['stat'] = 'success';
			// $data[] = $row;
		}

		if(count($list) == 0){
			$row = array();
			$row['line'] = '';
			$row['nama'] = '';
			$row['qtyakhir'] = floatval(0);
			$row['matcode'] = '';
			$row['stat'] = 'failed';
		}
		// $output = array(
		// 				"data" => $data
		// 		);
		echo json_encode($row);
    }

	public function DetTransBarcode($id)
	{
		$list = $this->master->getTransactionBarcode($id);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row[] = $no++;
			$row[] = $lst->line;
			$row[] = '<a href="#" id="mylink" onclick="callDetail('."'".$lst->id_in."'".');return false;">'.$lst->id_in.'</a>';
			$row[] = $this->formatTgl($lst->tgl_in);
			$row[] = $lst->sjno;
			$row[] = $lst->pono;
			$row[] = $this->formatNumber($lst->qty_in,3);
			$row[] = '<a href="#" id="mylink" onclick="callDetailOut('."'".$lst->id_out."'".');return false;">'.$lst->id_out.'</a>';
			$row[] = $this->formatTgl($lst->tgl_out);
			$row[] = $lst->mr_no;
			$row[] = $this->formatNumber($lst->qty_out,3);
			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		echo json_encode($output);
	}

	public function loadVendorList(){
		$list = $this->master->loadDataVendor('');
		$data = array();
		foreach ($list as $lst) {
			$row = array();
			$row['vend_id'] = $lst->VEND_VEND;
			$row['vend_nama'] = $lst->VEND_DESC;
			$data[] = $row;
		}
		$data1['vendor'] = $data;
		echo json_encode($data1);
	}

}

/* End of file  */
/* Location: ./application/controllers/ */