<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMaterial extends CI_Controller {

	private $namaFull;
	private $nama;
	private $tipe;
	private $wide;
	private $spec;
	private $color;
	private $unitName;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model','master');
        // if($this->session->userdata('status') != "login"){
        //     redirect(base_url());
        // }
	}

	function setnamaFull($namaFull) {
        $this->namaFull = $namaFull;
    }

    function getnamaFull() {
        return $this->namaFull;
    }

	function setNama($nama) {
        $this->nama = $nama;
    }

    function getNama() {
        return $this->nama;
    }

    function setTipe($tipe) {
        $this->tipe = $tipe;
    }

    function getTipe() {
        return $this->tipe;
    }

    function setWide($wide) {
        $this->wide = $wide;
    }

    function getWide() {
        return $this->wide;
    }

    function setSpec($spec) {
        $this->spec = $spec;
    }

    function getSpec() {
        return $this->spec;
    }

    function setColor($color) {
        $this->color = $color;
    }

    function getColor() {
        return $this->color;
    }

    function setUnitName($unitName) {
        $this->unitName = $unitName;
    }

    function getUnitName() {
        return $this->unitName;
    }

	public function loadMaterial($kode)
	{
		$list = $this->master->getNamaMaterial($kode);
		foreach($list as $lst)
		{	
			$this->setnamaFull($lst->mat_name.";".$lst->tipe_name.";".$lst->wide_name.";".$lst->spec_name.";".$lst->colo_name.";".$lst->mat_unit);
			$this->setNama($lst->mat_name);
			$this->setTipe($lst->tipe_name);
			$this->setWide($lst->wide_name);
			$this->setSpec($lst->spec_name);
			$this->setColor($lst->colo_name);
			$this->setUnitName($lst->mat_unit);
		}

	}

}

/* End of file  */
/* Location: ./application/controllers/ */