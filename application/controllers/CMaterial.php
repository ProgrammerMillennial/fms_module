<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Cglobal.php");

class CMaterial extends Cglobal {

	private $namaFull;
	private $group_name;
    private $group_code;
    private $epte_code;
    private $nama;
	private $tipe;
	private $wide;
	private $spec;
	private $color;
	private $unitName;
    private $lMaterial;
    public $dMatl= array();

	public function __construct()
	{
		parent::__construct();
	}

	function setnamaFull($namaFull) {
        $this->namaFull = $namaFull;
    }

    function getnamaFull() {
        return $this->namaFull;
    }

    function setGroupName($group_name) {
        $this->group_name = $group_name;
    }

    function getGroupName() {
        return $this->group_name;
    }

    function setGroupCode($group_code) {
        $this->group_code = $group_code;
    }

    function getGroupCode() {
        return $this->group_code;
    }

    function setEpteCode($epte_code) {
        $this->epte_code = $epte_code;
    }

    function getEpteCode() {
        return $this->epte_code;
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

    public function setListMaterial($kode){
    $matl = array();
        if (!array_key_exists($kode,$matl))
          {
                array_push($this->dMatl,array(
                    'kode' => $kode
                ));

                array_push($matl,array(
                   $kode => $kode
                ));
         }
    }

	public function loadMaterial()
	{
		$this->lMaterial = $this->master->loadDataMaterial($this->dMatl);
	}

    public function fillMaterial($kode){
        foreach($this->lMaterial as $lst)
        {   
            if($lst->kode == $kode){
                $this->setnamaFull($lst->mat_name.";".$lst->tipe_name.";".$lst->wide_name.";".$lst->spec_name.";".$lst->colo_name.";".$lst->mat_unit);
                $this->setGroupName($lst->groupName);
                $this->setGroupCode($lst->groupCode);
                $this->setEpteCode($lst->epte_code);
                $this->setNama($lst->mat_name);
                $this->setTipe($lst->tipe_name);
                $this->setWide($lst->wide_name);
                $this->setSpec($lst->spec_name);
                $this->setColor($lst->colo_name);
                $this->setUnitName($lst->mat_unit);
            }
        }
    }

    public function getGroupMatl(){
        $data = array();
        $list = $this->master->loadGropMaterial();
        foreach ($list as $v) {
            $row = array();
            $row[] = $v->PARH_GROP;
            $row[] = $v->PARH_NAME;
            $data[] = $row;
        }
        $output = array(
                        "data" => $data
                );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($output);
    }

}

/* End of file  */
/* Location: ./application/controllers/ */