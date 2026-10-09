<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Cglobal.php");
class Dashboard extends Cglobal {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model','master');
		// if($this->session->userdata('status') != "login"){
		// 	redirect(base_url());
		// }
	}

	public function index()
	{
		$menuid ='D';
		$menupg='Dashboard';
		$menuname = 'Dashboard';
		$grno = 1;
		$mnno = 1;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'dashboard');
	}

	public function locationRack()
	{
		$menuid ='M';
		$menupg='Dashboard/locationRack';
		$menuname = 'Rack Location';
		$grno = 2;
		$mnno = 1;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'master/rackLocation');	
	}

	public function warehouse()
	{
		$menuid ='M';
		$menupg='Dashboard/warehouse';
		$menuname = 'Warehouse Area';
		$grno = 2;
		$mnno = 2;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'master/warehouseAkses');	
	}

	public function barcodeTransaction()
	{
		$menuid ='M';
		$menupg='Dashboard/barcodeTransaction';
		$menuname = 'Transaksi Barcode';
		$grno = 2;
		$mnno = 3;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'master/barcodeTransaction');	
	}

	public function browsePO()
	{
		$menuid ='T';
		$menupg='Dashboard/browsePO';
		$menuname = 'Browse PO';
		$grno = 3;
		$mnno = 1;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'transaction/browsePO');	
	}

	public function browsePODetail()
	{
		$this->loadSidebar();
		$this->template->load('template', 'transaction/PODetail');	
	}

	public function browsePOvendor()
	{
		$menuid ='T';
		$menupg='Dashboard/browsePOvendor';
		$menuname = 'Browse PO Vendor';
		$grno = 3;
		$mnno = 2;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'transaction/browsePOvendor');	
	}

	public function browseSJ()
	{
		$menuid ='T';
		$menupg='Dashboard/browseSJ';
		$menuname = 'Browse SJ';
		$grno = 3;
		$mnno = 3;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'transaction/browseSJ');	
	}

	public function outBarcode()
	{
		$menuid ='T';
		$menupg='Dashboard/outBarcode';
		$menuname = 'Out Barcode';
		$grno = 3;
		$mnno = 4;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'transaction/outBarcode');	
	}

	public function outBarcodeDetail()
	{
		$this->loadSidebar();
		$this->template->load('template', 'transaction/outBarcodeDetail');	
	}

	public function outBarcodeRiject()
	{
		$menuid ='T';
		$menupg='Dashboard/outBarcodeRiject';
		$menuname = 'Out Barcode Riject';
		$grno = 3;
		$mnno = 5;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'transaction/outBarcodeRiject');	
	}

	public function outBarcodeRijectDetail()
	{
		$this->loadSidebar();
		$this->template->load('template', 'transaction/outBarcodeRijectDetail');	
	}

	public function splitBarcode()
	{
		$menuid ='T';
		$menupg='Dashboard/splitBarcode';
		$menuname = 'Split Barcode';
		$grno = 3;
		$mnno = 6;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'transaction/splitBarcode');	
	}

	public function outBarcodeKK()
	{
		$menuid ='T';
		$menupg='Dashboard/outBarcodeKK';
		$menuname = 'Out Barcode KK';
		$grno = 3;
		$mnno = 7;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'transaction/outBarcodeKK');	
	}

	public function listOutBarcode()
	{
		$menuid ='R';
		$menupg='Dashboard/listOutBarcode';
		$menuname = 'Out Barcode Transaction';
		$grno = 4;
		$mnno = 1;
		$this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadSidebar();
		$this->template->load('template', 'report/outBarcodeTrans');	
	}

	public function loadSidebar(){
		$list = $this->master->getMenu('ADMIN');
		foreach($list as $l){
			$row= array();
			$row['menu_id'] = $l->MENU_MENUID;
			$row['menu_nm'] = $l->MENU_NAME;
			$row['menu_pg'] = $l->MENU_PGMID;
			$cnt = $this->master->countMenuId($l->MENU_MENUID);
			$row['menu_cnt'] = $cnt;
			$data[]= $row;
		}
		$data1['menu_list'] = $data;
		$this->template->loadSidebar('template', 'sidebar', $data1);
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */