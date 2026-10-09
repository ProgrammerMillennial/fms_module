<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/CMaterial.php");
date_default_timezone_set('Asia/Jakarta');

class Cglobal extends CMaterial {

	private $vendorName;
	private $vendorCode;
	private $moddName;
	private $moddGend;
	// private $fact;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model','master');
		if($this->session->userdata('status') != "login"){
			redirect(base_url());
		}
		// var_dump($_SESSION['last_timestamp']);
		$this->checkSession();
	}

	public function checkSession(){
		$inactivity_time = 15 * 60;

		if (isset($_SESSION['last_timestamp']) && (time() - $_SESSION['last_timestamp']) > $inactivity_time) {
		    $this->session->sess_destroy();
		    redirect(base_url());
		    exit();
		  }else{
		    // Regenerate new session id and delete old one to prevent session fixation attack
		    // session_regenerate_id(true);

		    // Update the last timestamp
		    $_SESSION['last_timestamp'] = time();
		  }
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
		echo json_encode($details);
	}

	public function roleMenu($menuid, $menupg, $menuname, $grno, $mnno){
        // $mn_nm = 'Barcode Stock Monthly';
        // $mn_nm = 'Barcode Stock Daily';
        // $mn_nm = 'Inspection Inquery';
		$o = json_decode($this->getDateServerdt());
		$menudt = date("Y-m-d",strtotime($o->tgl));
		$this->master->addRoleMenu($menuid, $menupg, $menuname, $menudt, $grno, $mnno);
	}

	public function formatNumber($nilai, $format){
		if($format == 2){
			$a = number_format($nilai,2);
		}elseif($format == 3){
			$a = number_format($nilai,3);
		}elseif($format == 4){
			$a = number_format($nilai,4);
		}
		return $a;
	}

	public function formatTgl($tgl){
		$a = date("Y-m-d", strtotime($tgl));
		return $a;
	}

	public function convertSqlIn($list){
		$seri = '';
		foreach ($list as $lst2) {
			$code = strval($lst2[0]);
			$seri = "'" .$seri."', '" . $code;
		}
		$seri = $seri."'";
		$seri = substr($seri,7);
		return $seri;
	}

	// public function cekProsesStok(){
	// 	$tranno = $this->uri->segment(3);
	// 	$trandt = $this->uri->segment(4);
	// 	$flag = $this->uri->segment(5);

	// 	$dt = $this->master->cekClosing($tranno, $trandt, $flag);
	// 	print_r($dt);
	// }

	// function setFact($fact) {
    //     $this->fact = $fact;
    // }

    // function getFact() {
    //     return $this->fact;
    // }

	// function loadFact($fact){
	// 	$list = $this->master->loadFactfromPO($fact);
	// 	foreach($list as $lst)
	// 	{	
	// 		$this->setFact($lst->POMH_FACT);
	// 	}
	// }

}

/* End of file  */
/* Location: ./application/controllers/ */