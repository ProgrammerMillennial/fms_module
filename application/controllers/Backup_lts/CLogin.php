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

	function login_system(){
		$mParam = $_POST['params'];
		foreach ($mParam as $lst) {
	    	$user = $lst['user'];
	    	$pass = $lst['pass'];
	    }
		$row = array();
		$cek = $this->master->getLogin($user, $pass);
		if(count($cek) > 0){
			foreach ($cek as $lst) {
				$data_session = array(
					'user_id' => $lst->EMPL_NMBR,
					'username' => $lst->EMPL_ENME,
					'status' => "login",
					'last_timestamp' =>time()
					);
			}
			$this->session->set_userdata($data_session);
			$row['status'] = 'success';
			$row['message'] = 'success';
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