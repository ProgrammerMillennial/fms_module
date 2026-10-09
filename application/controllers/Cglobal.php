<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/CrdoDic.php");
date_default_timezone_set('Asia/Jakarta');

class Cglobal extends CrdoDic {

	private $vendorName;
	private $vendorCode;
	private $moddName;
	private $moddGend;
	// private $fact;

	public function __construct()
	{
		parent::__construct();
	}



	public function index()
	{
		
	}

	public function getDateServer(){
		$data = $this->master->getDateFromServer();
		$dt = json_encode($data);
        echo $dt;
	}

	public function getDateServerdt(){
		$data = $this->master->getDateFromServer();
		$dt = json_encode($data);
        return json_encode($data);
	}

    function setVendCode($vendorCode) {
        $this->vendorCode = $vendorCode;
    }

    function getVendCode() {
        return $this->vendorCode;
    }

    function setVendName($vendorName) {
        $this->vendorName = $vendorName;
    }

    function getVendName() {
        return $this->vendorName;
    }

	function loadVendor($vend){
		$list = $this->master->loadDataVendor($vend);
		foreach($list as $lst)
		{	
			$this->setVendName($lst->VEND_DESC);
			$this->setVendCode($lst->VEND_VEND);
		}
	}

	function setModdGend($moddGend) {
        $this->moddGend = $moddGend;
    }

    function getModdGend() {
        return $this->moddGend;
    }

    function setmoddName($moddName) {
        $this->moddName = $moddName;
    }

    function getmoddName() {
        return $this->moddName;
    }

	function loadModel($model){
		$list = $this->master->loadDataModel($model);
		foreach($list as $lst)
		{	
			$this->setmoddName($lst->MODD_NAME);
			$this->setModdGend($lst->MODD_GEND);
		}
	}

	function loadProcessMat(){
		$data = array();
		$list = $this->master->getProcessname();
		foreach($list as $lst)
		{	
			$row = array();
			$row['opcd'] = $lst->ROTE_OPCD;
			$row['opcd_name'] = $lst->ROTE_NAME;
			$data[] = $row;
		}
		$details['proc'] = $data;
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($details);
	}

	public function roleMenu($module, $menuid, $menupg, $menuname, $grno, $mnno){
        // $mn_nm = 'Barcode Stock Monthly';
        // $mn_nm = 'Barcode Stock Daily';
        // $mn_nm = 'Inspection Inquery';
		$o = json_decode($this->getDateServerdt());
		$menudt = date("Y-m-d",strtotime($o->tgl));
		$this->master->addRoleMenu($module, $menuid, $menupg, $menuname, $menudt, $grno, $mnno);
	}

	public function loadClosingDate(){
		$flag = $this->uri->segment(3);
		$data = array();
		$dt = $this->master->cekClosing($flag);
		foreach($dt as $lt){
			$row = array();
			$row['title'] = 'Closed';
			$row['start'] = $this->formatTgl($lt->CLOSE_DATE);
			$row['end'] = $this->formatTgl($lt->CLOSE_DATE);
			$row['overlap'] = false;
			$row['rendering'] = 'background';
			$row['color'] = '#fc0303';
			$data[]=$row;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
	}

}

/* End of file  */
/* Location: ./application/controllers/ */