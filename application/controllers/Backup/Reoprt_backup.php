<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Transaction.php");

class Report extends Transaction {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
	}

	public function loadTranOut(){

    	$mrno = $_POST['mrno'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$fact = $_POST['fact'];
		$opcd = $_POST['opcd'];
		$wh = $_POST['wh'];

		$list = $this->otspecth->loadDataOutBarcode($mrno, $dt1, $dt2, $opcd, $fact, $wh);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row[] = $no++;
			$row[] = $lst->ONSH_IDXX;
			$row[] = date("Y-m-d", strtotime($lst->ONSH_DATE));
			$row[] = $lst->ONSD_MRNO;
			$row[] = $lst->ONSH_NBRN;
			$row[] = $lst->ONSH_PART;
			$row[] = $lst->ONSH_OPCD;
			$row[] = $lst->ROTE_NAME;
			$row[] = $lst->ONSH_FACT;
			$row[] = number_format(floatval($lst->ONSH_TQTY),2);
			$row[] = $lst->ONSH_WHID;

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($output);
    }

    public function loadTranOutDetail(){

		$trno = $this->uri->segment(3);
    	$mrno = $this->uri->segment(4);

		$list = $this->otspectd->loadDatabyid($trno, $mrno);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row[] = $lst->ONSD_LINE;
			$row[] = $lst->ONSD_CODE;
			$row[] = $lst->INSD_NAME;
			$row[] = $lst->ONSD_UNIT;
			$row[] = $this->formatNumber($lst->QTTY, 2);
			$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_matl('."'".$lst->ONSD_LINE."', '".$lst->VEND_STAT."', this".')"><i class="fas fa-trash"></i></a>';

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($output);
    }

    public function loadTransIn(){
    	
    	$pono = $_POST['pono'];
    	$serial = $_POST['serial'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$vend = $_POST['vend'];
		$wh = $_POST['wh'];
		$tranno = '';

		if($serial!=''){
			$tranno = $this->inspectd->getListMaterialInbyLine($serial);
		}
		$list = $this->inspectd->listTransBarcodeIn($pono, $vend, $dt1, $dt2, $wh, $tranno);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row[] = $no++;
			$row[] = $lst->INSH_IDXX;
			$row[] = $this->formatTgl($lst->INSH_DATE);
			$row[] = $lst->INSD_PONO;
			$row[] = $lst->INSD_NBRN;
			$row[] = $lst->INSD_PART;
			$row[] = $lst->VEND_DESC;
			$row[] = $lst->INSD_SJNO;
			$row[] = $this->formatNumber($lst->QTTY,2);

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($output);
    }

	public function saveClosingDate(){
		$tgl = '';
		$flag = '';
		$stat = '';
		$dparam=$_POST['params'];
    	$ds = $this->master->simpanClosingDate($dparam);
		$row2 = array();
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$row2['status'] ='error';
			$row2['message'] = 'Failed, data failed to saved, re-chek again !';
		}else{
			$this->db->trans_commit();
			$row2['flag'] = $ds;
			$row2['status'] = 'success';
			$row2['message'] = 'Success, data has been saved !';
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($row2);
    }

    public function calculateStockBarcode($wh, $fact, $tgl1, $tgl2, $prev_date, $kode='', $divi){
	// $wh = $this->uri->segment(3);
	// $fact = $this->uri->segment(4);
    // $tgl1 = $this->uri->segment(5);
    // $tgl2 = $this->uri->segment(6);
    $lKode = array();
    $lData = array();
    // $dt = date('d', strtotime($tgl1));

    // if($dt != '01' and $divi == 'load'){
    // 	$prev_date = date("Y-m-01", strtotime($prev_date));
    // 	$prev_date = date("Y-m-d", strtotime($prev_date . " -1 day"));
    // }

    	$lst = $this->stock->loadDataStock($prev_date, $wh, $fact, $kode);
    	foreach($lst as $l){
    		$kode = strtoupper($l->BCDH_CODE);
    		$kode2 = $kode.$l->BCDH_FACT;
    		if (!in_array($kode2, $lKode)) {
    			$row = $this->modelStock();
	    		$row['kode'] = $kode;
	    		$row['factory'] = $l->BCDH_FACT;
	    		$row['wh'] = $l->wh;
		        array_push($lKode,$kode2);
		        $lData[$kode2] = $row;
    		}

    		foreach($lData as $key => $rw){
    			if($key == $kode2){
		    		$lData[$key]['qty_sa'] = $lData[$key]['qty_sa'] + $l->BCDH_CQTY ;
		    		$lData[$key]['nil_sa'] = $lData[$key]['nil_sa'] + $l->BCDH_CAMT;
		    		$lData[$key]['nil_sa_usd'] = $lData[$key]['nil_sa_usd'] + $l->BCDH_CAMT_OTH;

		    		if($lData[$key]['qty_sa'] != 0){
		    			$lData[$key]['harga'] = $lData[$key]['nil_sa_usd'] / $lData[$key]['qty_sa'];
		    			$lData[$key]['harga_conv'] = $lData[$key]['nil_sa'] / $lData[$key]['qty_sa'];
		    		}
		    		$lData = $this->calculateBalanceStok($lData, $kode2);
    			}
    		}
    	}

    	// if($dt != '01' and $divi == 'load'){
    	// 	$lData = $this->getStockBetweenMaterial($lKode, $lData, $tgl1, $tgl2, $wh, $fact, $kode);
    	// }

    	$lst = $this->stock->getTransactionInBacodeOld($tgl1, $tgl2, $wh, $fact, $kode);
    	foreach($lst as $l){
    		$kode = strtoupper($l->kode);
    		$kode2 = $kode.$l->factory;
    		if (!in_array($kode2, $lKode)) {
    			$row = $this->modelStock();
	    		$row['kode'] = $kode;
	    		$row['factory'] = $l->factory;
	    		$row['wh'] = $l->wh;
		        array_push($lKode,$kode2);
		        $lData[$kode2] = $row;
    		}

    		foreach($lData as $key => $rw){
    			if($key == $kode2){
					$lData[$key]['qty_in'] = $lData[$key]['qty_in'] + $l->in_qtty ;
					$lData[$key]['nil_in'] = $lData[$key]['nil_in'] + $l->in_amount;
					$lData[$key]['nil_in_usd'] = $lData[$key]['nil_in_usd'] + $l->in_amount_usd;

					if($lData[$key]['qty_in'] > 0){
						$lData[$key]['hargain'] = $lData[$key]['nil_in_usd'] / $lData[$key]['qty_in'];
						$lData[$key]['hargain_conv'] = $lData[$key]['nil_in'] / $lData[$key]['qty_in'];
					}

	    			$lData = $this->calculateBalanceStok($lData, $kode2);
    			}
    		}
    	}
    	$lData = $this->calculatePriceAvg($lData);
    	$lst = $this->stock->getTransactionOutBacodeOld($tgl1, $tgl2, $wh, $fact, $kode);
    	foreach($lst as $l){
    		$kode = strtoupper($l->kode);
    		$kode2 = $kode.$l->factory;
    		if (!in_array($kode2, $lKode)) {
    			$row = $this->modelStock();
	    		$row['kode'] = $kode;
	    		$row['factory'] = $l->factory;
	    		$row['wh'] = $l->wh;
		        array_push($lKode,$kode2);
		        $lData[$kode2] = $row;
    		}

    		foreach($lData as $key => $rw){
    			if($key == $kode2){
		    		$lData[$key]['qty_out'] = $lData[$key]['qty_out'] + $l->out_qtty;
		    		$lData[$key]['nil_out_usd'] = $lData[$key]['priceAvg'] * $lData[$key]['qty_out'];
		    		$lData[$key]['nil_out'] = $lData[$key]['priceAvg_conv'] * $lData[$key]['qty_out'];
		    		
		    		// if($lData[$key]['hargain'] > 0){
		    		// 	$lData[$key]['nil_usd_out'] = $lData[$key]['hargain'] * $lData[$key]['qty_out'];
		    		// 	$lData[$key]['nil_out'] = $lData[$key]['hargain_conv'] / $lData[$key]['qty_out'];
		    		// }else{
		    		// 	$lData[$key]['nil_usd_out'] = $lData[$key]['harga'] * $lData[$key]['qty_out'];
		    		// 	$lData[$key]['nil_out'] = $lData[$key]['harga_conv'] / $lData[$key]['qty_out'];
		    		// }
		    		$lData = $this->calculateBalanceStok($lData, $kode2);
    			}
    		}
    	}
    	return  $lData;
    }

	public function getStockBetweenMaterial($lKode, $lData, $tgl1, $tgl2, $wh, $fact, $kode){
    // $tgl1 = $this->uri->segment(3);
    // $tgl2 = $this->uri->segment(4);
   	// $wh = $this->uri->segment(5);
   	// if($wh == 0){
   	// 	$wh = '';
   	// }
	// $fact = $this->uri->segment(6);
   	// if($fact == 0){
   	// 	$fact = '';
   	// }
   	$tgl1 = date("Y-m-01", strtotime($tgl1));
   	$tgl2 = date("Y-m-d", strtotime($tgl2 . " -1 day"));
	// $kode = $this->uri->segment(7);
    // $lKode = array();
    // $lData = array();
    	$lst = $this->stock->getTransactionInBacodeOld($tgl1, $tgl2, $wh, $fact, $kode);
    	foreach($lst as $l){
    		$kode = strtoupper($l->kode);
    		$kode2 = $kode.$l->factory;
    		if (!in_array($kode2, $lKode)) {
    			$row = $this->modelStock();
	    		$row['kode'] = $kode;
	    		$row['factory'] = $l->factory;
	    		$row['wh'] = $l->wh;
		        array_push($lKode,$kode2);
		        $lData[$kode2] = $row;
    		}

    		foreach($lData as $key => $rw){
    			if($key == $kode2){
					$lData[$key]['qty_sa'] = $lData[$key]['qty_sa'] + $l->in_qtty ;
					$lData[$key]['nil_sa'] = $lData[$key]['nil_sa'] + $l->in_amount;
					$lData[$key]['nil_sa_usd'] = $lData[$key]['nil_sa_usd'] + $l->in_amount_usd;

					if($lData[$key]['qty_sa'] > 0){
						$lData[$key]['harga'] = $lData[$key]['nil_sa_usd'] / $lData[$key]['qty_sa'];
						$lData[$key]['harga_conv'] = $lData[$key]['nil_sa'] / $lData[$key]['qty_sa'];
					}

	    			$lData = $this->calculateBalanceStok($lData, $kode2);
    			}
    		}
    	}
    	$lst = $this->stock->getTransactionOutBacodeOld($tgl1, $tgl2, $wh, $fact, $kode);
    	foreach($lst as $l){
    		$qty_sa_out = 0;
    		$nil_sa_out = 0;
    		$nil_sa_out_usd = 0;
    		$kode = strtoupper($l->kode);
    		$kode2 = $kode.$l->factory;
    		if (!in_array($kode2, $lKode)) {
    			$row = $this->modelStock();
	    		$row['kode'] = $kode;
	    		$row['factory'] = $l->factory;
	    		$row['wh'] = $l->wh;
		        array_push($lKode,$kode2);
		        $lData[$kode2] = $row;
    		}
    		foreach($lData as $key => $rw){
    			if($key == $kode2){
    				$qty_sa_out = $l->out_qtty * -1;
    				$nil_sa_out = $qty_sa_out * $lData[$key]['harga_conv'];
		    		$nil_sa_out_usd = $qty_sa_out * $lData[$key]['harga'];

		    		$lData[$key]['qty_sa'] = $lData[$key]['qty_sa'] + $qty_sa_out;
		    		$lData[$key]['nil_sa'] = $lData[$key]['nil_sa'] + $nil_sa_out;
		    		$lData[$key]['nil_sa_usd'] = $lData[$key]['nil_sa_usd'] + $nil_sa_out_usd;

		    		$lData = $this->calculateBalanceStok($lData, $kode2);
    			}
    		}
    	}

    	return $lData;
    }

    public function calculateBalanceStok($lData, $kode2){
    	foreach($lData as $key => $rw){
    		if($key == $kode2){
    			// if($lData[$key]['qty_sa'] + $lData[$key]['qty_in'] - $lData[$key]['qty_out'] !=0){
				// 	$lData[$key]['qty_bal'] = 0;
			    // 	$lData[$key]['nil_bal'] = 0;
			    // 	$lData[$key]['nil_bal_usd'] = 0;
    			// }else{
			    	$lData[$key]['qty_bal'] = $lData[$key]['qty_sa'] + $lData[$key]['qty_in'] - $lData[$key]['qty_out'];
			    	$lData[$key]['nil_bal'] = $lData[$key]['nil_sa'] + $lData[$key]['nil_in'] - $lData[$key]['nil_out'];
			    	$lData[$key]['nil_bal_usd'] = $lData[$key]['nil_sa_usd'] + $lData[$key]['nil_in_usd'] - $lData[$key]['nil_out_usd'];
		    	// }
    		}
    	}

    	return $lData;
	}

	public function calculatePriceAvg($lData){
    	foreach($lData as $key => $rw){
    		if(floatval($lData[$key]['qty_sa'] + $lData[$key]['qty_in']) != 0){
				$lData[$key]['priceAvg'] = floatval($lData[$key]['nil_sa_usd'] + $lData[$key]['nil_in_usd']) / floatval($lData[$key]['qty_sa'] + $lData[$key]['qty_in']);
			   	$lData[$key]['priceAvg_conv'] = floatval($lData[$key]['nil_sa'] + $lData[$key]['nil_in']) / floatval($lData[$key]['qty_sa'] + $lData[$key]['qty_in']);
		   }else{
		   		$lData[$key]['priceAvg'] = floatval(0);
			   	$lData[$key]['priceAvg_conv'] = floatval(0);
		   }
    	}

    	return $lData;
	}

    public function modelStock(){
    	$dStok = array( 'group' => '','group_name' => '','epte' => '','kode' => '','nama' => '','type' => '','spec' => '','wide' => '','color' => '','unit' => '',
    					'qty_sa' => 0,'nil_sa' => 0,'nil_sa_usd' => 0,'qty_in' => 0,'nil_in' => 0,'nil_in_usd' => 0,'qty_out' => 0,'nil_out' => 0,
    					'nil_out_usd' => 0,'qty_bal' => 0,'nil_bal' => 0,'nil_bal_usd' => 0,'harga' => 0,'harga_conv' => 0,'hargain' => 0,'hargain_conv' => 0,
    					'priceAvg' => 0,'priceAvg_conv' => 0,'factory' => '', 'wh' => ''
    		      	  );
    	return $dStok;
    }

    public function setNamaStokMaterial($lData){
    $kd = '';
    	foreach($lData as $key => $rw){
    		$this->setListMaterial($key);
    	}
		$this->loadMaterial();
		foreach($lData as $key => $rw){
			$this->fillMaterial($key);
	    	$lData[$key]['group'] =trim($this->getGroupCode()," ");
	    	$lData[$key]['group_name'] = trim($this->getGroupName()," ");
	    	$lData[$key]['epte'] = trim($this->getEpteCode()," ");
	    	$lData[$key]['nama'] = trim($this->getNama()," ");
	    	$lData[$key]['type'] = trim($this->getTipe()," ");
	    	$lData[$key]['wide'] = trim($this->getWide()," ");
	    	$lData[$key]['spec'] = trim($this->getSpec()," ");
	    	$lData[$key]['color'] = trim($this->getColor()," ");
	    	$lData[$key]['unit'] = trim($this->getUnitName()," ");
		}
		return $lData;
    }

    public function closingBarcodeMaterial($divi, $tgl1, $tgl2, $wh, $fact, $kode){
    // $divi = $this->uri->segment(3);
    // if($this->uri->segment(4) == 0){
	// 	$wh = '';	
    // }else{
    // 	$wh = $this->uri->segment(4);	
    // }
    // if($this->uri->segment(5) == 0){
	// 	$fact = '';	
    // }else{
    // 	$fact = $this->uri->segment(5);	
    // }
    // $tgl1 = $this->uri->segment(6);
    // $tgl2 = $this->uri->segment(7);
    $previousDay = date("Y-m-d", strtotime($tgl1 . " -1 day"));
    $status = array();
    	if($divi == 'close'){
    		$lData = $this->calculateStockBarcode($wh, $fact, $tgl1, $tgl2, $previousDay, $kode, $divi);
    		// $this->db->trans_begin();
    		$this->stock->saveBarcodeStockMonthly($lData, $tgl2, $wh);
    		// $this->stock->saveSerialBarcodeStockMonthly($previousDay, $tgl1, $tgl2, $wh, $fact);
	    	// if ($this->db->trans_status() === FALSE){
			// 	$this->db->trans_rollback();
		    // 	$status['stat'] = 'failed';
	    	// 	$status['message'] = 'failed, error while saving data!';
			// }else{
			// 	$this->db->trans_commit();
				$status['stat'] = 'success';
	    		$status['message'] = 'success, data has been saved!';
	    	// }
    	}else{
    		$lData = $this->calculateStockBarcode($wh, $fact, $tgl1, $tgl2, $previousDay, $kode, $divi);
    		$lData = $this->setNamaStokMaterial($lData);
    		$status['stat'] = 'success';
    		$status['message'] = 'success, data has been loaded';
    		$status['data'] = $lData;
    	}
    	return $status;
		// header('Content-Type: application/json; charset=utf-8');
		// echo json_encode($status);
    }

    public function autoClosing(){
   	$divi = $this->uri->segment(3);
    if($this->uri->segment(4) == 0){
		$wh = '';	
    }else{
    	$wh = $this->uri->segment(4);	
    }
    if($this->uri->segment(5) == 0){
		$fact = '';	
    }else{
    	$fact = $this->uri->segment(5);	
    }
    $tgl1 = $this->uri->segment(6);
    $tgl2 = $this->uri->segment(7);
    $kode = $this->uri->segment(8);
    if($kode == 0){
    	$kode = '';
    }

    	$sql = "select YEAR(CALM_DATE) TAHUN, MONTH(CALM_DATE) BULAN,MIN(CALM_DATE) TANGGAL
				FROM PRTMERP.PRTM.TP_CALENM
				WHERE CALM_DATE BETWEEN '". $tgl1 ."' AND '". $tgl2 ."'
				GROUP BY YEAR(CALM_DATE), MONTH(CALM_DATE)";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$tglB = $this->formatTgl($d->TANGGAL);
			$tglE = $this->lastDate($d->TANGGAL);
			$status = $this->closingBarcodeMaterial($divi, $tglB, $tglE, $wh, $fact, $kode);
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($status);
    }

    public function getStokDetailMatl(){
    	$kode = $this->uri->segment(3);
    	$dt1 = $this->uri->segment(4);
    	$dt2 = $this->uri->segment(5);
    	$fact = $this->uri->segment(6);
    	$wh = $this->uri->segment(7);

    	$lData = $this->calculateStockBarcode($wh, $fact, $tgl1, $tgl2, $previousDay);
    }

    public function getStokSubDetailMatl(){
		$kode = $this->uri->segment(3);
    	$dt1 = $this->uri->segment(4);
    	$dt2 = $this->uri->segment(5);
    	$fact = $this->uri->segment(6);
    	$wh = $this->uri->segment(7);

    	$lData = $this->calculateStockBarcode($wh, $fact, $tgl1, $tgl2, $previousDay);
    }

}

/* End of file  */
/* Location: ./application/controllers/ */