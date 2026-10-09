<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CrdoDic extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Master_model','master');
		$this->load->model('Inspectiond_model','inspectd');
		$this->load->model('Inspectionh_model','inspecth');
		$this->load->model('Outinspectd_model','otspectd');
		$this->load->model('Outinspecth_model','otspecth');
		$this->load->model('Inspectiond_split_model','inspectds');
		$this->load->model('Laminating_model','lammod');
		$this->load->model('Rdodic_master','rdodic');
		$this->load->model('Barcodestock_model','stock');
		$this->load->model('Transaction_model','transc');
		$this->load->model('Setting_model','presett');

		if($this->session->userdata('status') != "login"){
			redirect(base_url());
		}
		// $this->checkSession();
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
			$seri = $seri."', '" . $code;
			$seri = ltrim($seri,"',");
		}
		$seri = $seri."'";
		return $seri;
	}

	public function lastDate($tgl){
		$last_date = '';
		$last_date = date("Y-m-t", strtotime($tgl));
		return $last_date;
	}

	public function BeginDate($tgl){
		$a = date("Y-m-01", strtotime($tgl));
		return $a;
	}

	public function getSchemaTable($table){
	    $arr = '';
	    $lo = $this->rdodic->testdb($table);
		foreach ($lo->list_fields() as $field)
		{
		   $arr = $arr."', '" . $field;
		   $arr = ltrim($arr,"',");
		}
		$arr = $arr."'";
		return $arr;
	}
    public function checkErrorVal($p){
		$val = '';
		if(!is_numeric($p)){
			$val = $p;
		}else{
			$val = chr($p);
		}
		return $val;
	}
}

/* End of file  */
/* Location: ./application/controllers/ */