<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/CMaterial.php");
class Dashboard extends CMaterial {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model','master');
	}

	public function index()
	{
		// $menuid ='D';
		// $menupg='Dashboard';
		// $menuname = 'Dashboard';
		// $grno = 1;
		// $mnno = 1;
		// $this->roleMenu($menuid, $menupg, $menuname, $grno, $mnno);
		// $this->loadTemplate();
		// $this->template->load('template', 'dashboard');
	}

	public function DashboardMBS()
	{
		$module ='MBS';
		$menuid ='D';
		$menupg='Dashboard/DashboardMBS';
		$menuname = 'Dashboard';
		$grno = 1;
		$mnno = 1;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'dashboard');	
	}

	public function locationRack()
	{
		$module ='MBS';
		$menuid ='M';
		$menupg='Dashboard/locationRack';
		$menuname = 'Rack Location';
		$grno = 2;
		$mnno = 1;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'master/rackLocation');	
	}

	public function warehouse()
	{
		$module ='MBS';
		$menuid ='M';
		$menupg='Dashboard/warehouse';
		$menuname = 'Warehouse Area';
		$grno = 2;
		$mnno = 2;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'master/warehouseAkses');	
	}

	public function barcodeTransaction()
	{
		$module ='MBS';
		$menuid ='M';
		$menupg='Dashboard/barcodeTransaction';
		$menuname = 'Transaksi Barcode';
		$grno = 2;
		$mnno = 3;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'master/barcodeTransaction');	
	}

	public function browsePO()
	{
		$module ='MBS';
		$menuid ='T';
		$menupg='Dashboard/browsePO';
		$menuname = 'Browse PO';
		$grno = 3;
		$mnno = 1;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/browsePO');	
	}

	public function browsePODetail()
	{
		$this->loadTemplate();
		$this->template->load('template', 'transaction/PODetail');	
	}


	public function browsePOvendor()
	{
		$module ='MBS';
		$menuid ='T';
		$menupg='Dashboard/browsePOvendor';
		$menuname = 'Browse PO Vendor';
		$grno = 3;
		$mnno = 2;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/browsePOvendor');	
	}

	public function browseSJ()
	{
		$menuid ='T';
		$menupg='Dashboard/browseSJ';
		$menuname = 'Browse SJ';
		$grno = 3;
		$mnno = 3;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/browseSJ');	
	}

	public function outBarcode()
	{
		$module ='MBS';
		$menuid ='T';
		$menupg='Dashboard/outBarcode';
		$menuname = 'Out Barcode';
		$grno = 3;
		$mnno = 4;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/outBarcode');	
	}

	public function outBarcodeDetail()
	{
		$this->loadTemplate();
		$this->template->load('template', 'transaction/outBarcodeDetail');	
	}

	public function outBarcodeDetailEng()
	{
		$this->loadTemplate();
		$this->template->load('template', 'transaction/outBarcodeDetailEng');	
	}

	public function outBarcodeRiject()
	{
		$module ='MBS';
		$menuid ='T';
		$menupg='Dashboard/outBarcodeRiject';
		$menuname = 'Out Barcode Riject';
		$grno = 3;
		$mnno = 5;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/outBarcodeRiject');	
	}

	public function outBarcodeRijectDetail()
	{
		$this->loadTemplate();
		$this->template->load('template', 'transaction/outBarcodeRijectDetail');	
	}

	public function splitBarcode()
	{
		$module ='MBS';
		$menuid ='T';
		$menupg='Dashboard/splitBarcode';
		$menuname = 'Split Barcode';
		$grno = 3;
		$mnno = 6;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/splitBarcode');	
	}

	public function outBarcodeKK()
	{
		$module ='MBS';
		$menuid ='T';
		$menupg='Dashboard/outBarcodeKK';
		$menuname = 'Out Barcode KK';
		$grno = 3;
		$mnno = 7;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/outBarcodeKK');	
	}

	public function MultipleoutBarcodeKK()
	{
		$module ='MBS';
		$menuid ='T';
		$menupg='Dashboard/MultipleoutBarcodeKK';
		$menuname = 'Multiple Out Barcode';
		$grno = 3;
		$mnno = 8;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/multipleOutBarcode');	
	}

	public function outBarcodeKK2()
	{
		$module ='MBS';
		$menuid ='T';
		$menupg='Dashboard/outBarcodeKK2';
		$menuname = 'Out Barcode Add Entry';
		$grno = 3;
		$mnno = 9;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/outBarcodeKK2');	
	}

	public function SettingBarcode()
	{
		$module ='MBS';
		$menuid ='T';
		$menupg='Dashboard/SettingBarcode';
		$menuname = 'Setting Barcode Entry';
		$grno = 3;
		$mnno = 10;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'transaction/SettingBarcode');	
	}


	// Fungsi ini akan dipanggil saat membuka halaman URL: .../Dashboard/scanTransferMez
    public function scanTransferMez()
    {
        $module ='MBS';
        $menuid ='T';
        $menupg='Dashboard/scanTransferMez';
        $menuname = 'Scan & Transfer Rack';
        $grno = 3;
        $mnno = 11;
        $this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
        $this->loadTemplate();
        
        // Memanggil file view yang ada di folder application/views/transaction/scanTransferMez.php
        $this->template->load('template', 'transaction/scanTransferMez'); 
    }



	public function listInBarcode()
	{
		$module ='MBS';
		$menuid ='R';
		$menupg='Dashboard/listInBarcode';
		$menuname = 'In Transaction';
		$grno = 4;
		$mnno = 1;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'report/inBarcodeTrans');	
	}

	public function listOutBarcode()
	{
		$module ='MBS';
		$menuid ='R';
		$menupg='Dashboard/listOutBarcode';
		$menuname = 'Out Transaction';
		$grno = 4;
		$mnno = 2;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'report/outBarcodeTrans');	
	}

	public function ClosingDate()
	{
		$module ='MBS';
		$menuid ='R';
		$menupg='Dashboard/ClosingDate';
		$menuname = 'Closing Transaction';
		$grno = 4;
		$mnno = 3;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'report/closingDate');	
	}

	public function listOutBarcodeDetail()
	{
		$this->loadTemplate();
		$this->template->load('template', 'report/outBarcodeTransDetail');	
	}

	public function MaterialStock()
	{
		$module ='MBS';
		$menuid ='R';
		$menupg='Dashboard/MaterialStock';
		$menuname = 'Material Stock Barcode';
		$grno = 4;
		$mnno = 4;
		$this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'report/barcode_stock');	
	}

	public function DashboardMLS()
	{
		// $module ='MLS';
		// $menuid ='D';
		// $menupg='Dashboard/DashboardMBS';
		// $menuname = 'Dashboard';
		// $grno = 1;
		// $mnno = 1;
		// $this->roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno);
		$this->loadTemplate();
		$this->template->load('template', 'scan_laminating/laminating');	
	}

	public function loadTemplate(){
		$this->loadNavbar();
		$this->loadSidebar();
		$this->loadHead();
		$this->loadFoot();
	}

	public function loadSidebar(){
		$r = array();
		$lmodul = $this->master->getModul();
		foreach($lmodul as $lo){
			$row = array();
			$row[] = $lo->CODD_VALU;
			$r[] = $row;
		}
		$t = $this->convertSqlIn($r);
		$list = $this->master->getMenu($_SESSION['user_id'], $t);
		foreach($list as $l){
			$row= array();
			$row['menu_md'] = $l->MENU_MODULE;
			$row['menu_id'] = $l->MENU_MENUID;
			$row['menu_nm'] = $l->MENU_NAME;
			$row['menu_pg'] = $l->MENU_PGMID;
			$cnt = $this->master->countMenuId($l->MENU_MODULE, $l->MENU_MENUID);
			$row['menu_cnt'] = $cnt;
			$data[]= $row;
		}
		$data1['menu_list'] = $data;
		$this->template->loadSidebar('template', 'sidebar', $data1);
	}

	public function loadNavbar(){
		$r = array();
		$lmodul = $this->master->getModul();
		foreach($lmodul as $lo){
			$row = array();
			$row[] = $lo->CODD_VALU;
			$r[] = $row;
		}
		$t = $this->convertSqlIn($r);

		$list = $this->master->getMenu($_SESSION['user_id'], $t);
		foreach($list as $l){
			$row= array();
			$row['menu_md'] = $l->MENU_MODULE;
			$row['menu_gp'] = $l->MENU_GROUP;
			$row['menu_gm'] = $l->MENU_GRNM;
			$row['menu_id'] = $l->MENU_MENUID;
			$row['menu_nm'] = $l->MENU_NAME;
			$row['menu_pg'] = $l->MENU_PGMID;
			$cnt = $this->master->countMenuId($l->MENU_MODULE, $l->MENU_MENUID);
			$row['menu_cnt'] = $cnt;
			$data[]= $row;
		}
		$data1['menu_list'] = $data;
		$this->template->loadNavbar('template', 'navbar', $data1);
	}

	public function loadHead(){
		$this->template->loadHead('template', 'custom_head');
	}


	public function loadFoot(){
		$this->template->loadFoot('template', 'custom_foot');
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */