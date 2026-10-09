<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {


	public function getMaterialNama($kode)
	{
		$list = $this->master->getNamaMaterial($kode);
		foreach($list as $lst)
		{
			$nama = $lst->mat_name.";".$lst->tipe_name.";".$lst->wide_name.";".$lst->spec_name.";".$lst->colo_name.";".$lst->mat_unit;
		}
		return $nama;
	}

}

/* End of file Master.php */
/* Location: ./application/controllers/Master.php */