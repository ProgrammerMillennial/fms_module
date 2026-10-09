<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Dashboard.php");

class Setting extends Dashboard {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

	}

    public function HeadSettingBarcode($id, $stat)
    {
		$list = $this->presett->getHeadSettingBarcode($id, $stat);
		$data = array();
		$no = 1;

		foreach ($list as $lst) {
			$this->setListMaterial($lst->mat_code);
		}
			$this->loadMaterial();
			
		foreach ($list as $lst) {
			$this->fillMaterial($lst->mat_code);
			$nm = $lst->matname;

			if($nm == ''){
				$nama = trim($this->getNamaFull()," ");
			}else{
				$nama = $lst->matname;
			}
			
			$row = array();
			$row['line'] = $lst->line;
			$row['nama'] = $nama;
			$row['qtyakhir'] = floatval($lst->qtyakhir);
			$row['matcode'] = $lst->mat_code;
			$row['area'] = $lst->area;
			$row['stat'] = 'success';
		}

		if(count($list) == 0){
			$row = array();
			$row['line'] = '';
			$row['nama'] = '';
			$row['qtyakhir'] = floatval(0);
			$row['matcode'] = '';
			$row['area'] = '';
			$row['stat'] = 'failed';
		}

		echo json_encode($row);
    }


     public function simpanDataSetting(){

        $details = $this->input->post('details');

        if (!empty($details)) {
            $sukses = true;

            foreach ($details as $row) {
                $serial = $row['serial'];
                $divi   = 'R';
                $current_user =  $row['user'];
                $nrack =  $row['rack'];
              
                $result = $this->presett->saveInspectionData($serial, $current_user, $divi, $nrack);
                if (!$result) {
                    $sukses = false;
                }
            }

            if ($sukses) {
                $response = ['stat' => 'success', 'message' => 'Data berhasil disimpan/diperbarui!'];
            } else {
                $response = ['stat' => 'error', 'message' => 'Beberapa data gagal diproses!'];
            }
        } else {
            $response = ['stat' => 'error', 'message' => 'Tidak ada data untuk disimpan!'];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }


}
