<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CLogin extends CI_Controller {

function __construct(){
		parent::__construct();		
		$this->load->model('master_model','master');
		// $this->session->sess_destroy();
	}

	function index(){
		$this->load->view('login');
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

	function login_system(){
		$mParam = $_POST['params'];
		$user='';
		$page='';
		$cnt = 0;
		foreach ($mParam as $lst) {
	    	$user = $lst['user'];
	    	$pass = $lst['pass'];
	    }
		$row = array();
		$cek = $this->master->getLogin($user, $pass);
		if(count($cek) > 0){
			foreach ($cek as $lst) {
				$user = $lst->EMPL_NMBR;
				$data_session = array(
					'user_id' => $lst->EMPL_NMBR,
					'username' => $lst->EMPL_ENME,
					'status' => "login",
					'last_timestamp' =>time()
					);
			}

			$r = array();
			$lmodul = $this->master->getModul();
			foreach($lmodul as $lo){
				$row = array();
				$row[] = $lo->CODD_VALU;
				$r[] = $row;
			}
			$t = $this->convertSqlIn($r);

			$list = $this->master->getMenu($user, $t);
			foreach($list as $l){
				if($l->MENU_GROUP == 1 and $l->MENU_GRNM == 1 and $cnt == 0) {
					$page = $l->MENU_PGMID;
					$cnt = $cnt + 1;
				}
			}
			$this->session->set_userdata($data_session);
			$row['status'] = 'success';
			$row['message'] = 'success';
			$row['page'] = $page;
		}else{
			$row['status'] = 'failed';
			$row['message'] = 'Failed, username or password invalid, please try again!';
		}
		echo json_encode($row);
	}

	function logout(){
		$this->session->sess_destroy();
		redirect(base_url());
	}

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */