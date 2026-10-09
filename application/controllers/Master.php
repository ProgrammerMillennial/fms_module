<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Dashboard.php");

class Master extends Dashboard {

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
		$list = $this->master->getWarehouselist('');
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

	public function listlocation($id)
	{
		$list = $this->master->getWarehouserack($id);
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
			$this->setListMaterial($lst->mat_code);
		}
			$this->loadMaterial();
			
		foreach ($list as $lst) {
			$this->fillMaterial($lst->mat_code);
			$nm = $lst->matname;

			if($nm == ''){
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

    
    public function HeadSettingBarcode($id, $stat)
    {
		$list = $this->master->getHeadSettingBarcode($id, $stat);
		$data = array();
		$no = 1;

		foreach ($list as $lst) {
			$this->setListMaterial($lst->mat_code);
		}
			$this->loadMaterial();
			
		foreach ($list as $lst) {
			$this->fillMaterial($lst->mat_code);
			$nm = $lst->matname;

			if($nm == ''){
				$nama = trim($this->getNamaFull()," ");
			}else{
				$nama = $lst->matname;
			}
			
			$row = array();
			$row['line'] = $lst->line;
			$row['nama'] = $nama;
			$row['qtyakhir'] = floatval($lst->qtyakhir);
			$row['matcode'] = $lst->mat_code;
			$row['area'] = $lst->area;
			$row['stat'] = 'success';
			// $data[] = $row;
		}

		if(count($list) == 0){
			$row = array();
			$row['line'] = '';
			$row['nama'] = '';
			$row['qtyakhir'] = floatval(0);
			$row['matcode'] = '';
			$row['area'] = '';
			$row['stat'] = 'failed';
		}
		// $output = array(
		// 				"data" => $data
		// 		);
		echo json_encode($row);
    }

	public function headTransBarcodeDetail()
    {
	
    $dParam = $_POST['params'];
    	foreach($dParam as $lt){
    		$id = $lt['barcode'];
    		$stat = $lt['vend_stat'];
    	}

		$list = $this->master->getHeadTransBarcodeDetail($id, $stat);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row['no'] = $no++;
			$row['serial'] = $lst->ISSD_NLINE;
			$row['qtty'] = $lst->ISSD_QTTY;
			$row['action'] = '<a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak" onclick="printBybarcode('."'".$lst->ISSD_NLINE."'".')"><i class="fas fa-print"></i></a>
							  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_matl('."'".$lst->ISSD_NLINE."', '".$lst->ISSD_QTTY."', '".$stat."', this".')"><i class="fas fa-trash"></i></a>';
			$data[] = $row;
		}

		$rw2 = array();
		$rw2['list'] = $data;
		$rw2['status'] ='success';
		$rw2['message'] = 'Success, data has been saved !';

		echo json_encode($rw2);
    }

    // public function deletSplitbyBarcode(){
    // 	$dParam = $_POST['params'];
    // 	foreach($dParam as $lt){
    // 		$id = $lt['barcode'];
    // 		$stat = $lt['vend_stat'];
    // 	}

    // }

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
		header('Content-Type: application/json');
		echo json_encode($data1);
	}

	public function getWarehouse()
	{
		$user = $_SESSION['user_id'];
		$list = $this->master->get_wh_by_id($user);
		$data = array();
		foreach ($list as $lst) {
			$row = array();
			$row['opcd'] = $lst->ROTE_OPCD;
			$row['name'] = $lst->ROTE_NAME;
			$data[] = $row;
		}
		$data1['wh'] = $data;
		header('Content-Type: application/json');
		echo json_encode($data1);
	}

}

/* End of file  */
/* Location: ./application/controllers/ */