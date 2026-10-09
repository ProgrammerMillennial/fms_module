<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Master.php");
require_once BASEPATH.'../assets/phpqrcode/qrlib.php';

class Transaction extends Master {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function listPoDetail()
	{
		$pono = $_POST['pono'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$vend = $_POST['vend'];
		$fact = $_POST['fact'];
		$wh = $_POST['wh'];
		$p ='';
		$q =0;

		$list = $this->master->poDetail($pono, $dt1, $dt2, $vend, $fact, $wh);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
		$qPo = 0;
		$qMi = 0;
		$qIns = 0;
		$qBal = 0;
			if($p != $lst->POMH_PONO.$lst->RECH_MINO){
			$row = array();
			$row[] = $no++;
			$row[] = $lst->POMH_PONO;
			$row[] = $lst->RECH_MINO;
			$row[] = $lst->POMH_POID;
			$row[] = $lst->POMH_PART;
			$row[] = $lst->POMH_VEND;
			$row[] = $this->formatTgl($lst->RECH_DATE);
				foreach ($list as $lst2) {
					if($lst->POMH_PONO == $lst2->POMH_PONO){
						// if($q != $lst2->RECD_PO_SEQN ){
							$qPo = $qPo + floatval($lst2->POMD_QTTY);
							$qIns = $qIns + floatval($lst2->POMD_INSP_QTTY);
							$qBal = $qBal + floatval($lst2->BALANCE);
						// }
						// $q = $lst2->RECD_PO_SEQN;
					}

					if($lst->POMH_PONO == $lst2->POMH_PONO && $lst->RECH_MINO == $lst2->RECH_MINO){
					   	$qMi = $qMi + floatval($lst2->RECD_QTTY);
					}
				}
			$row[] = $this->formatNumber($qPo,2);
			$row[] = $this->formatNumber($qMi,2);
			$row[] = $this->formatNumber($qIns,2);
			$row[] = $this->formatNumber($qBal,2);

			$data[] = $row;
			}
			$p = $lst->POMH_PONO.$lst->RECH_MINO;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
	}

	public function loadInspectBarcVendor(){
		$pono = $_POST['pono'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$vend = $_POST['vend'];
		$fact = $_POST['fact'];
		$wh = $_POST['wh'];
		$line = $_POST['line'];

		$list = $this->master->browseBarcodeVendor($pono, $dt1, $dt2, $vend, $wh, $line);
		$data = array();
		$r = array();
		$no = 1;
		$tgl_inspect = '';

		foreach ($list as $lst) {
			$tgl_inspect = $this->formatTgl($lst->INSH_DATE);
			$row = array();
			$row[] = $no++;
			$row[] = $lst->INSD_LIN2;
			$row[] = $lst->INSD_NAME;
			$row[] = $lst->INSD_UNIT;
			$row[] = $this->formatNumber($lst->INSD_IQTY,2);
			$row[] = $tgl_inspect;
			$row[] = $lst->INSD_PONO;
			$row[] = $lst->INSD_SJNO;
			$row[] = $lst->INSD_VEND;
			$row[] = $lst->VEND_DESC;
			$row[] = $lst->INSD_IDXX;
			$row[] = $lst->INSH_WHID;
			$row[] = '<a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak" onclick="printBarcVendor('."'".$lst->INSD_LIN2."'".')"><i class="fas fa-print"></i></a>
						<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_matl('."'".$lst->INSD_LIN2."', this,"."'".$lst->INSD_IDXX."',"."'".$lst->INSH_WHID."',"."'".$tgl_inspect."'".')"><i class="fas fa-trash"></i></a>';

			$data[] = $row;
		}

		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
	}

	public function listPoDetailVendor()
	{
		$pono = $_POST['pono'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$vend = $_POST['vend'];
		$fact = $_POST['fact'];
		$wh = $_POST['wh'];

		$p ='';
		$q =0;
		$lqty = 0;
		$balqty = 0;

		$list = $this->master->poVendor($pono, $dt1, $dt2, $vend, $fact, $wh);
		$data = array();
		$r = array();
		$no = 1;

		foreach ($list as $lst) {
		$qPo = 0;
		$qMi = 0;
		$qIns = 0;
		$qBal = 0;
			if($p != $lst->POMH_PONO){
			$row = array();
			$row[] = $no++;
			$row[] = $lst->POMH_PONO;
			// $row[] = $lst->RECH_MINO;
			$row[] = $lst->POMH_POID;
			$row[] = $lst->POMH_PART;
			$row[] = $lst->POMH_VEND;
			$row[] = $this->formatTgl($lst->RECH_DATE);
				foreach ($list as $lst2) {
					if($lst->POMH_PONO == $lst2->POMH_PONO){
						// if($q != $lst2->RECD_PO_SEQN ){
						$q = $lst2->RECD_PO_SEQN;
						if (!in_array($q,$r)){
							array_push($r,$q);
							// $qPo = $qPo + floatval($lst2->POMD_QTTY);
							$lqty = $qPo;
							$qIns = $qIns + floatval($lst2->POMD_INSP_QTTY);
						}
					}

					if($lst->POMH_PONO == $lst2->POMH_PONO){
						$qPo = $qPo + floatval($lst2->POMD_QTTY);
						$qMi = $qMi + floatval($lst2->RECD_QTTY);
						$qBal = $qBal + floatval($lst2->BALANCE);
						// $qPo = floatval($lqty);
					}
				}
			$row[] = $this->formatNumber($qPo,2);
			$row[] = $this->formatNumber($qMi,2);
			$row[] = $this->formatNumber($qIns,2);
			$row[] = $this->formatNumber($qBal,2);

			$data[] = $row;
			}
			$p = $lst->POMH_PONO;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
	}

	public function getDetailInspectVendor($wh)
    {
    	$ttl_mi = str_replace(",","",0);
    	$ttl_mi = floatval(0);

		$list = $this->master->poDetailVendor($wh);
		$data = array();
		$no = 1;
		$id = "";
		foreach ($list as $lst) {
			$row = array();
			if($id != $lst->itemId)
			{
				$barcode = "";
				$qtty = 0;
				$qty2 = 0;
				if($lst->namaitem == ''){
					$this->loadMaterial($lst->itemId);
					$nama = trim($this->getnamaFull()," ");
				}else{
					$nama = $lst->namaitem;
				}

				foreach ($list as $lst2) {
					if ($lst->itemId == $lst2->itemId){
						$code = strval($lst2->linebarcode);
						$barcode = $barcode . ", " . $code;
						$qtty = $qtty + $lst2->qtty;
					}
				}

				$row[] = ltrim($barcode, ',');
				$row[] = $nama;
				$row[] = $lst->satuan;
				$row[] = $this->formatNumber($qtty,2);
				$row[] = $lst->itemId;
				$data[] = $row;
			}

			$id = $lst->itemId;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

	public function getDataFromPO($po, $wh, $mino)
    {
		$list = $this->master->getDetailFormPO($po,$wh,$mino);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row['pono'] = $lst->POMH_PONO;
			$row['orderDate'] = $this->formatTgl($lst->POMH_ORDT);
			$row['rls'] = $lst->POMH_POID;
			$row['style'] = $lst->POMH_PART;
			$row['model'] = $lst->MODD_NAME;
			$row['vendid'] = $lst->POMH_VEND;
			$row['vend'] = $lst->VDESC1;
			$row['addr1'] = $lst->VADD1;
			$row['addr2'] = $lst->VADD2;
			$row['vend1'] = $lst->VDESC2;
			$row['addr3'] = $lst->VADD3;
			$row['addr4'] = $lst->VADD4;
			$row['mino'] = $lst->RECH_MINO;
			$row['miDate'] = $this->formatTgl($lst->RECH_DATE);
		}
		
		echo json_encode($row);
    }

    public function getDataMiHeader($po, $mino)
    {
		$list = $this->master->getMiheader($po, $mino);
		$data = array();
		$no = 1;
		$id = "";
		foreach ($list as $lst) {
			$row = array();
			if($id != $lst->Id)
			{
				$barcode = "";
				$qtty = 0;
				foreach ($list as $lst2) {
					if ($lst->Id == $lst2->Id){
						$code = strval($lst2->line);
						$barcode = $barcode . ", " . $code;
						$qtty = $qtty + $lst2->qtty;
					}
				}

				$row[] = $lst->Id;
				$row[] = $lst->mino;
				$row[] = $lst->sjno;
				$row[] = number_format($qtty,2);
				$row[] = ltrim($barcode, ',');
				$data[] = $row;
			}

			$id = $lst->Id;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

    public function loadDetailMI(){

    	$po = $this->uri->segment(3);
    	$mino = $this->uri->segment(4);
    	$wh = $this->uri->segment(5);
    	// $matcode = strval($this->uri->segment(5));
    	$matcode ='';

    	$list = $this->getDataMiDetail($po, $mino, $matcode,'',$wh);
    	$output = array(
						"data" => $list
				);
    	
    	echo json_encode($output);
    }

    public function getDataMiDetail($po1 = null, $mino = null, $matcode1 = null, $type = null, $wh = null)
    {
    	$po = $this->uri->segment(3);
    	$matcode = strval($this->uri->segment(4));

    	if (substr($this->uri->segment(3),0,3) == "INH"){
    		$po = $po1;
	    }
	    $tipe = $this->uri->segment(4);
	    if ($tipe != "" && $tipe != "MAT" && $tipe != "ENG" && substr($tipe,0,2) != "MI" && substr($tipe,0,2) != "MA" && substr($tipe,0,2) != "RJ"){
    		$matcode = strval($this->uri->segment(4));
	    }else {
	    	$matcode = $matcode1;
	    }

	    if($po1 !== ''){
	    	$po = $po1;
	    }

	    // var_dump($wh);

		$list = $this->master->getMiDetailMaterial($po, $mino, $matcode, $wh);
		$data = array();
		$no = 1;
		$id = "";
		$row = array();
		
		foreach ($list as $lst) {
			$this->setListMaterial($lst->POMD_CODE);
		}
		$this->loadMaterial();
		foreach ($list as $lst) {
			$row = array();
			$this->fillMaterial($lst->POMD_CODE);

			if ($type == 1){
				$row['seqn'] = floatval($lst->POMD_SEQN);
				$row['matcode'] = $lst->POMD_CODE;
				$row['matname'] = trim($this->getnamaFull()," ");
				$row['unit'] = trim($this->getUnitName()," ");
				$row['poqty'] = $this->formatNumber($lst->POMD_QTTY,2);
				$row['miqty'] = $this->formatNumber($lst->RECD_QTTY,2);
				$row['price'] = $this->formatNumber($lst->POMD_PRIC,2);
				$row['umcd'] = $lst->POMH_UMCD;
				$row['inqty'] = number_format($lst->IQTY,2);
				$row['balqty'] = $this->formatNumber($lst->BALANCE2,2);
			}else{
				$row[] = floatval($lst->POMD_SEQN);
				$row[] = $lst->POMD_CODE;
				$row[] = trim($this->getnamaFull()," ");
				$row[] = trim($this->getUnitName()," ");
				$row[] = $this->formatNumber($lst->POMD_QTTY,2);
				$row[] = $this->formatNumber($lst->RECD_QTTY,2);
				$row[] = $this->formatNumber($lst->POMD_PRIC,2);
				$row[] = $lst->POMH_UMCD;
				$row[] = $this->formatNumber($lst->IQTY,2);
				$row[] = $this->formatNumber($lst->BALANCE2,2);
			}

			$data[] = $row;
		}

		if ($type == 1){
			return $row;
		}else{
			return $data;
		}
    }

    public function inspectionDetail($id1 = ''){
    	if (!empty($_POST["params"])) {
    		$pr = $_POST['params'];	
    	}else{
    		$pr = array();
    	}
    	$id2 = "";
    	$data1 = array();
    	$data2 = array();
    	$kode = "";

    	$id = $id1;
    	$pono = "";
    	$mino = "";
    	$matcode = "";
    	$wh = "";

		foreach ($pr as $lst) {
			$id = $lst['id'];
			$pono = $lst['pono'];
	    	$mino = $lst['mino'];
	    	$matcode = strval($lst['matcode']);
	    	$wh = $lst['wh'];
			// $data1['inspect'] = $this->getDataMiDetail($lst['pono'], $lst['mino'], strval($lst['matcode']), 1, $lst['wh']);
		}

		$list = $this->master->inspectDataHeader($id);
		if(count($list) !== 0){
			foreach ($list as $lst) {
				$row2 = array();
				
				$row2['barc'] = $lst->line;
				$row2['kem'] = $lst->kemasan;
				$row2['jmlkem'] = floatval($lst->jmlKem);
				$row2['qtty'] = floatval($lst->qtty);
				$row2['action'] = '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_matl('."'".$lst->line."', this".')"><i class="fas fa-trash"></i></a>';

				if($id2 != $lst->headerID){
					$row = array();
					$row['trans'] = $lst->headerID;
					$row['pono'] = $lst->headerPONumber;
					$row['mino'] = $lst->mino;
					$tgl= strtotime($lst->tanggal);
	    			$tran_date = date("Y-m-d", $tgl);
					$row['tglinsp'] = $tran_date;
					$row['sjno'] = $lst->sjno;
					$row['seqn'] = $lst->headerSEQN;
					$row['matcode'] = $lst->headerMatCode;
					$row['price'] = $lst->harga;
					$row['umcd'] = $lst->umcd;
					$row['matunit'] = $lst->unit;
					$row['rls'] = $lst->release;
					$row['part'] = $lst->style;
					$row['wh'] = $lst->wh;
					$row['vend'] = $lst->vend;
					$row['sjno'] = $lst->sjno;

					$data1['header'] = $row;

					if($kode != $lst->headerMatCode){
						$pono = $lst->headerPONumber;
	    				$mCode = strval($lst->headerMatCode);
						$data1['inspect'] = $this->getDataMiDetail($pono, $lst->mino, $mCode, 1, $lst->wh);

						$kode = $lst->headerMatCode;
					}
				}

				$data2[] = $row2;
				$id2 = $lst->headerID;
			}
			$data1['barcode'] = $data2;
		}else{
			$data1['inspect'] = $this->getDataMiDetail($pono, $mino, $matcode, 1, $wh);
		}
		echo json_encode($data1);
    }

	// public function inspectionDetail2(){
	// 	$pr = $_POST['params'];
	// 	$data1 = array();
	// 	foreach ($pr as $lst) {
	// 	    $data1['inspect'] = $this->getDataMiDetail($lst['pono'], $lst['mino'], strval($lst['matcode']), 1, $lst['wh']);
	// 	}
	// 	echo json_encode($data1);
    // }

    public function getInspectEntry()
    {
    	$po = $this->uri->segment(3);
    	$mCode = strval($this->uri->segment(4));

		$data['inspect'] = $this->getDataMiDetail($po, $mCode, 1);
		$this->load->view('transaction/modalInspectEntryDetail',$data);
    }

    public function addTransaction()
    {
    	$Ttlqtty = 0;
    	$list = '';
    	$transno = '';
    	$cnt_barc = 0;
    	$pr = $_POST['params'];	
		$row = array();
		$cnt_line = 0;
		$tes = 0;
		$messages = '';
		$barcode = '';
		$dt = array();
		$wh = '';
		$totalBarcode = 0;
		$pono = '';
		$mino = '';
		$matl = '';
		$seqn = '';
		$bal =0;
		$bal2 =0;
		$stat = FALSE;
		foreach ($pr as $lst) {
			$bal = floatval($lst['bal']);
		}

		if($bal == 0){
		$mParam = $_POST['params'];	
		    foreach ($mParam as $lst) {
		    	$Ttlqtty = $Ttlqtty + floatval($lst['qtty']);
		    	$transno = $lst['tranno'];
		    	$pono = $lst['pono'];
		    	$mino = $lst['mino'];
		    	$matl = $lst['matcode'];
		    	$seqn = floatval($lst['seqn']);
		    	$seqh = floatval($lst['seqh']);
		    	$wh = $lst['wh'];
		    	// $barc['line'] = $lst['serial'];
		    	if($lst['qtty'] !== 0){
			    	if($lst['serial'] != ''){
			    		$cnt_line = $cnt_line + 1;
			    		$dt[] = $lst['serial'];
			    	}
		    	}
		    }
		}else{
			$mParam = array();
			$bal2 = 0;
			foreach ($pr as $lst) {
				$Ttlqtty = floatval($lst['bal']);
				$transno = $lst['tranno'];
		    	$pono = $lst['pono'];
		    	$mino = $lst['mino'];
		    	$matl = $lst['matcode'];
		    	$seqn = $lst['seqn'];
		    	$wh = $lst['wh'];
		    	$num = 1;
		    	$bal2 = floatval($lst['bal']) ;
				while ($num <= floatval($lst['jmlkem'])) {
					$row = array();

					$bal2 = floatval($bal2) - floatval($lst['qtty']);

				    $row['tranno'] = $lst['tranno'];
				    $row['seqn'] = floatval($lst['seqn']);
				    $row['seqh'] = floatval($lst['seqh']);
				    $row['matcode'] = $lst['matcode'];
				    $row['matname'] = $lst['matname'];
				    $row['matunit'] = $lst['matunit'];
				    $row['pono'] = $lst['pono'];
				    $row['mino'] = $lst['mino'];
				    $row['tgl_inspect'] = $lst['tgl_inspect'];
				    $row['sjno'] = $lst['sjno'];
				    $row['release'] = $lst['release'];
				    $row['style'] = $lst['style'];
				    $row['price'] = floatval($lst['price']);
				    $row['umcd'] = $lst['umcd'];
				    $row['vend'] = $lst['vend'];
				    $row['wh'] = $lst['wh'];
				    $row['user'] = $lst['user'];
				    $row['vstat'] = $lst['vstat'];
				    $row['lineItem'] = $lst['lineItem'];
				    $row['kem'] = $lst['kem'];
				    $row['jmlkem'] = $lst['jmlkem'];
				    if($bal2 > 0){
				    	$row['qtty'] = floatval($lst['qtty']);
				    }else{
						$row['qtty'] = floatval($lst['qtty']) + floatval($bal2);
					}
				    $row['serial'] = $lst['serial'];

				    $mParam[] = $row;
				    $num++;
				}
			}
		}

		$checkMI = $this->inspectd->getTotalQttyMI($mino, $matl);
		if(floatval($checkMI) == floatval($Ttlqtty)){
			$proc = array();
			$proc['id'] = '';
			$proc['detail'] = '';
			$proc['stat'] = 'N';
			$proc['message'] = 'Duplicate transaction, data cannot be saved, re-check again !';
			echo json_encode($proc);
			return;
		}
		

		// print_r($mParam);
		$details = array();
		$details['tranno'] = $transno;
		$details['Ttlqtty'] = $Ttlqtty;
		$details['pono'] = $pono;
		$details['matl'] = $matl;
		$details['seqn'] = $seqn;
		$details['wh'] = $wh;
		$details['cnt_line'] = $cnt_line;

		$this->db->trans_begin();

    	$proc =$this->transc->createBarcodeMatl($details, $mParam, $dt);
    	if($proc['stat'] == 'N'){
    		$stat = TRUE;
    	}

    	if ($this->db->trans_status() === FALSE || $stat){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
		}

    	echo json_encode($proc);
    }

    public function printRackLocation(){
    	$this->load->view('master/RackCode');
    }


    public function printNewBarcode(){
    	
    	$tipe = $this->uri->segment(3);
    	$line = $this->uri->segment(4);
    	$vend_stat = $this->uri->segment(5);

    	$data = array();
    	$list = $this->inspectd->loadDataBarcode($tipe,$line,$vend_stat);

		foreach ($list as $lst) {
			$this->setListMaterial($lst->headerMatCode);
		}
		$this->loadMaterial();
    	foreach ($list as $lst) {

			$this->fillMaterial($lst->headerMatCode);
    		$this->loadModel($lst->POMH_PART);
    		$this->loadVendor($lst->vendorid);

    		$row = array();
    		$row['line'] = $lst->line;
    		$row['headerMatCode'] = $lst->headerMatCode;
    		$row['headerPONumber'] = $lst->headerPONumber;
    		$row['QtyAkhir'] = number_format($lst->QtyAkhir,2);
    		$row['tanggal'] = $lst->tanggal;
    		$row['vend_desc'] = trim($this->getVendName()," ");
    		$row['nama'] = trim($this->getNama()," ");
    		$row['tipe'] = trim($this->getTipe()," ");
    		$row['wide'] = trim($this->getWide()," ");
    		$row['spec'] = trim($this->getSpec()," ");
    		$row['color'] = trim($this->getColor()," ");
    		$row['unit'] = trim($this->getUnitName()," ");
    		$row['model'] = trim($this->getmoddName()," ");
    		$row['style'] = trim($lst->POMH_PART," ");
    		$row['release'] = $lst->POMH_POID;
    		$row['fact'] = $lst->POMH_FACT;

    		$data[] = $row;

    	}
    	$data['print'] = $data;
    	// print_r($data);
    	$this->load->view('transaction/printBarcode',$data);
    }

    public function generateQRcode(){
		$param = $this->uri->segment(3);

	    ob_start("callback");
	    $codeText = $param;
	    $debugLog = ob_get_contents();
	    ob_end_clean();

	    QRcode::png($codeText);
    }

    public function scanMatlVendor()
    {
    	$mParam = $_POST['params'];
    	$pono = '';
    	$wh = '';
    	$wh = '';
		$matl = '';
		$seqn = '';
		$lineSJ = '';
		$lineItem = '';
		$Ttlqtty = 0;
		$id = '';
		$tqty = 0;
		$row3 = array();
		$status = FALSE;
    	foreach ($mParam as $lst) {
	    	$tranno = $lst['tranno'];
	    	$barcode = trim($lst['barcode']);
	    	$tanggal = $lst['tgl_inspect'];
	    	$wh = $lst['wh'];
	    	$pono = $lst['pono'];
	    	$stat = $lst['vstat'];
	    	$user = $lst['user'];
	    }

	    $close = $this->master->cekClosingBydate($tanggal,'MI');
	    // $closeErp = $this->master->cekClosingErp($tgl,'MI');
	    // if($close == 0 and $closeErp == 0){
	    if($close == 0 ){
			$list = $this->master->getdetailPoVend($barcode, $wh);
			if(count($list) > 0){
				$data=array();
				$data2 = array();
				foreach ($list as $lst) {
					$this->setListMaterial($lst->itemId);
				}
				$this->loadMaterial();
				foreach ($list as $lst) {
					$this->fillMaterial($lst->itemId);

					$row = array();
					$row['tranno'] = $tranno;
						$list2 = $this->master->getSeqnSeqhPo($lst->Pono, $lst->itemId);
						foreach ($list2 as $lst2) {
							$seqn =$lst2->POMD_SEQN;
							$seqh =$lst2->POMD_SEQH;
							$rls =$lst2->POMH_POID;
							$part =$lst2->POMH_PART;
							$pric =$lst2->POMD_PRIC;
							$umcd =$lst2->POMH_UMCD;
							// $mino =$lst2->RECH_MINO;
							// $midt =$lst2->RECH_DATE;
						}

					$pono = $lst->Pono;
					// $pono = $lst->Pono;
					$matl = $lst->itemId;
					$lineSJ = $lst->LineSj;
					$lineItem = $lst->lItem;

					$row['seqn'] = $seqn;
					$row['seqh'] = $seqh;
					$row['matcode'] = $lst->itemId;
					$row['matname'] = trim($this->getnamaFull()," ");
					$row['matunit'] = $lst->satuan;
					$row['pono'] = $lst->Pono;
					$row['tgl_inspect'] = $tanggal;
					// $row['tgl_inspect'] = $this->formatTgl($lst->RECH_DATE);
					$row['mino'] = '';
					if($lst->noSuratJalan == ''){
						$row['sjno'] = $pono;	
					}else{
						$row['sjno'] = $lst->noSuratJalan;	
					}
					$row['release'] = $rls;
					$row['style'] = $part;
					$row['price'] = $pric;
					$row['umcd'] = $umcd;
					$row['vend'] = $lst->vendor;
					$row['wh'] = $wh;
					$row['kem'] = $lst->Kemasan;
					$row['jmlkem'] = $lst->jmlKemasan;
					$row['qtty'] = $lst->qtty;
					$row['serial'] = $lst->linebarcode;
					$row['vstat'] = $stat;
					$row['lineItem'] = $lst->LineItem;
					$row['user'] = $user;
					$Ttlqtty = 1;
					$data[] = $row;

					$row2 = array();
		    		$row2['INSD_LINE'] = $lst->linebarcode;
		    		$row2['INSD_PONO'] = $lst->Pono;
		    		$row2['SEQH'] = $seqh;
		    		$row2['SEQN'] = $seqn;
		    		$row2['INSD_CODE'] = $lst->itemId;

		    		$data2[] = $row2;
				}

				$this->db->trans_begin();
				$detil= array();
				$detil['barcode'] = $barcode;
				$detil['Ttlqtty'] = $Ttlqtty;
				$detil['pono'] = $pono;
				$detil['matl'] = $matl;
				$detil['lineSJ'] = $lineSJ;
				$detil['lineItem'] = $lineItem;
				$detil['seqn'] = $seqn;
				$detil['wh'] = $wh;
				$detil['tqty'] = $tqty;
				$row3 =$this->transc->scanBarcodeVendor($data, $data2, $detil);
				if(!is_array($row3)) {
					$msg = $this->checkErrorVal($row3);
					if($msg == 'error'){
						$status = TRUE;
					}
				}
		       	if ($this->db->trans_status() === FALSE || $status){
					$this->db->trans_rollback();
					$row3['status'] = 'error';
					$row3['message'] = $row3['message'];
				}else{
					$this->db->trans_commit();
				}
			}else{
				$row3 = array();
				$row3['status'] = 'error';
				$row3['message'] = 'Failed, Barcode not found !';
			}
		}else{
				$row3['status'] = 'error';
				$row3['message'] = 'Failed, Tanggal sudah di close !';
		}

		echo json_encode($row3);
    }

    public function getTotalOutmalt($mrno, $matl, $flag = 0){
    	$tout = 0;
		$list3 = $this->otspectd->totalOutbyMatl($mrno,$matl,$flag);
		foreach($list3 as $lst3){
			    	$tout = $lst3->OQTY;
			    }
		return $tout;		    
    }

    public function loadDataPoVend(){
    	$pono = $_POST['pono'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$vend = $_POST['vend'];
		$serial = $_POST['serial'];
		$wh = $_POST['wh'];
		$p ='';

		$list = $this->inspectd->getDetailBarcVend($pono, $dt1, $dt2, $vend, $serial, $wh);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
		$qPo = 0;
		$qIns = 0;
		$qBal = 0;
			$row = array();
			$row[] = $lst->INSD_LINE;
			$row[] = $lst->INSD_LIN2;
			$row[] = $lst->INSD_CODE;
			$row[] = $lst->INSD_NAME;
			$row[] = $lst->INSD_UNIT;
			$row[] = $lst->INSD_BQTY;
			$row[] = $lst->INSD_PONO;
			$row[] = $lst->INSD_VEND;

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

    public function loadMRListHead(){
	$dh = array();
	$d = array();
	$dd = array();
	$data = array();
	$mParam = $_POST['params'];
	$barcode = '';

		foreach ($mParam as $lst2) {
			$code = $lst2['mrno'];
			$barcode = $barcode . ", '" . $code . "'";
		}

		$mrno = ltrim($barcode, ',');

	$flag = $this->master->cekStatusApprvIn($mrno, 'KT');
		if($flag == ''){
			return;
		}

	$lst_mr = $this->master->loadDataMRHeader($mrno);
		foreach($lst_mr as $lsm){
			$row = array();
			$row['mrno'] = $lsm->MRQH_MRNO;
			$row['tgl'] = $this->formatTgl($lsm->MRQH_REDT);
			$row['rls'] = $lsm->MRQH_IPWX;
			$row['style'] = $lsm->MRQH_PART;
			$row['fact'] = $lsm->MRQH_DEST;
			$row['opcd'] = $lsm->MRQH_OPCD;
			if($lsm->QTY2 !== 0){
				$qtty = $lsm->QTY2;
			}else{
				$qtty = $lsm->QTTY;
			}
			$row['tqty_erp'] = $this->formatNumber($qtty,2);
			$dh[] = $row;
		}

		$data['header'] = $dh;
		
		echo json_encode($data);
		
    }

    public function loadMRListDetil(){
	$dh = array();
	$d = array();
	$dd = array();
	$data = array();
	$mParam = $_POST['params'];
	$barcode = '';

		foreach ($mParam as $lst2) {
			$code = $lst2['mrno'];
			$barcode = $barcode . ", '" . $code . "'";
		}

		$mrno = ltrim($barcode, ',');

	$flag = $this->master->cekStatusApprvIn($mrno, 'KT');
		if($flag == ''){
			return;
		}

	$matl = "";
	$list = $this->master->loadDataMRDetail($mrno, $matl, $flag, 1);
	$data = array();
	$no = 1;

		foreach ($list as $lst) {
			$this->setListMaterial($lst->TRND_CODE);
		}
		$this->loadMaterial();

		foreach ($list as $lst) {
			$row = array();
			$this->fillMaterial($lst->TRND_CODE);
			$list2 = $this->otspectd->totalOutbyMatl($lst->KRND_MRNO, $lst->TRND_CODE);
			$tqtyo_barc = 0;
			foreach ($list2 as $lst2){
				$tqtyo_barc =$lst2->OQTY;
			}
			$tqtyo_erp = $this->master->cekTtlTransdErp($lst->KRND_MRNO, $lst->TRND_CODE);
			if(floatval($tqtyo_erp) == 0){
				$tqtyo_erp = $this->master->totalOutbyReqxxdErp($lst->KRND_MRNO, $lst->TRND_CODE);
			}
    		$row['name'] = trim($this->getNamaFull()," ");
    		$row['unit'] = trim($this->getUnitName()," ");
			$row['barc'] = number_format(floatval($tqtyo_barc),2);
			$row['erp'] = number_format(floatval($tqtyo_erp),2);
			$row['aprv'] = number_format(floatval($lst->QTTY_APPR),2);
			$row['code'] = $lst->TRND_CODE;
			// $row[] = number_format(floatval($lst->QTTY_APPR),2);

			$d[] = $row;
		}

		$k = '';

		foreach($d as $d0){
		  if($k !== $d0['code']){
			  $row = array();	
			  $qbarc = 0;
			  $qerp = 0;
			  $qaprv = 0;
			  $row[] = $d0['name'];
		      $row[] = $d0['unit'];
			  	foreach($d as $d1){
			  		if($d0['code'] == $d1['code']){
					  	$qbarc = $qbarc + floatval($d1['barc']);
						$qerp = $qerp + floatval($d1['erp']);
						$qaprv = $qaprv + floatval($d1['aprv']);		
			  		}
			  	}
				
			  $row[] = number_format(floatval($qbarc),2);
			  $row[] = number_format(floatval($qerp),2);
			  $row[] = number_format(floatval($qaprv),2);
			  $row[] = $d0['code'];
			  $dd[] = $row;
		  }
		  $k = $d0['code'];
		}

		$output = array(
						"data" => $dd
				);
		
		echo json_encode($output);
		
    }

	public function loadDataMrByParam(){

    	$mrno = $_POST['mrno'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$fact = $_POST['fact'];
		$opcd = $_POST['opcd'];
		$wh = $_POST['wh'];
		$tipe = $_POST['tipe'];
		$tqtyo = 0;

		$list = $this->master->loadDataMR($mrno, $dt1, $dt2, $opcd, $fact, $wh, $tipe);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row[] = $no++;
			$row[] = $lst->MRQH_MRNO;
			$row[] = date("Y-m-d", strtotime($lst->MRQH_REDT));
			$row[] = $lst->MRQH_IPWX;
			$row[] = $lst->MRQH_PART;
			$row[] = $lst->MRQH_OPCD;
			$row[] = $lst->ROTE_NAME;
			$row[] = $lst->MRQH_DEST;
			// $row[] = number_format(floatval($lst->MRQH_QTTY),2);
			$row[] = number_format(floatval($lst->QTTY_APPR),2);
			if($tipe == 'KT'){
				$list2 = $this->otspectd->totalOutbyMatl($lst->MRQH_MRNO,'');
				if(count($list2) > 0){
					foreach ($list2 as $lst2){
						$tqtyo =$lst2->OQTY;
					}
				}else{
					$tqtyo = 0;
				}
				$balqty = floatval($lst->QTTY_APPR) - floatval($tqtyo);
				$row[] = number_format(floatval($tqtyo),2);
				$row[] = number_format(floatval($balqty),2);
			}else{
				$row[] = number_format(floatval($lst->QTY_OUT),2);
				$row[] = number_format(floatval($lst->BALANCE),2);
			}
			$row[] = $lst->wh;
			$row[] = $lst->STT;

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

	public function loadDataMrInParam(){

    	$mrno = $_POST['mrno'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$fact = $_POST['fact'];
		$opcd = $_POST['opcd'];
		$wh = $_POST['wh'];
		$tipe = $_POST['tipe'];
		$key = '';
		$qty_apr = 0;
		$rls = '';
		$style = '';
		$opcd = '';
		$opcnm = '';
		$balqty = 0;
		$tqtyo = 0;

		$list = $this->master->loadDataMR($mrno, $dt1, $dt2, $opcd, $fact, $wh, $tipe);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			if($key !== $lst->MRQH_MRNO){
				$row = array();
				$row[] = $no++;
				$row[] = $lst->MRQH_MRNO;
				$row[] = date("Y-m-d", strtotime($lst->MRQH_REDT));
				foreach ($list as $lst2){
					// $rls = $rls .','.$lst->MRQH_IPWX;
					// $style = $style .','.$lst->MRQH_PART;
					// $opcd = $opcd .','.$lst->MRQH_OPCD;
					// $opcnm = $opcnm .','.$lst->ROTE_NAME;
					$rls = $lst->MRQH_IPWX;
					$style = $lst->MRQH_PART;
					$opcd = $lst->MRQH_OPCD;
					$opcnm = $lst->ROTE_NAME;
					$qty_apr = $qty_apr + floatval($lst->QTTY_APPR);
					
					if($tipe == 'KT2'){
						$list2 = $this->otspectd->totalOutbyMatl($lst->MRQH_MRNO,'');
						if(count($list2) > 0){
							foreach ($list2 as $lst2){
								$tqtyo = $tqtyo + $lst2->OQTY;
							}
						}else{
							$tqtyo = 0;
						}
						$balqty = $balqty + floatval($lst->QTTY_APPR) - floatval($tqtyo);

					}else{
						$tqtyo = $tqtyo + floatval($lst->QTY_OUT);
						$balqty = $balqty + floatval($lst->BALANCE);
					}
				}
				$row[] = $rls;
				$row[] = $style;
				$row[] = $opcd;
				$row[] = $opcnm;
				$row[] = $lst->MRQH_DEST;
				$row[] = number_format($qty_apr,2);
				$row[] = number_format(floatval($tqtyo),2);
				$row[] = number_format(floatval($balqty),2);
				$row[] = $lst->wh;
				$row[] = $lst->STT;

				$data[] = $row;
			}

			$key = $lst->MRQH_MRNO;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

    public function loadDataMrByMrno($mrno){

    	$mrno = $this->uri->segment(3);
		// $wh = $_POST['wh'];

		$list = $this->master->loadDataMR($mrno, "", "", "", "", "");
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row['mrno'] = $lst->TRNH_MRNO;
			$data[] = $row;
		}
		$data1['transaksi'] = $data;
		
		echo json_encode($data1);
    }

    	public function loadVendorList(){
		$list = $this->master->loadDataVendor('');
		$data = array();
		foreach ($list as $lst) {
			$row = array();
			$row['vend_id'] = $lst->VEND_VEND;
			$row['vend_nama'] = $lst->VEND_DESC;
			$data[] = $row;
		}
		$data1['vendor'] = $data;
		
		echo json_encode($data1);
	}

    public function loadDataMrByDetail(){

		$mrno = $this->uri->segment(3);
		$tipe = $this->uri->segment(4);
		$tqtyo_erp = 0;

		$flag = $this->master->cekStatusApprv($mrno);
		if($flag == ''){
			return;
		}

		$matl = "";
		$list = $this->master->loadDataMRDetail($mrno, $matl, $flag);
		$data = array();
		$no = 1;

		foreach ($list as $lst) {
			$this->setListMaterial($lst->TRND_CODE);
		}
		$this->loadMaterial();

		foreach ($list as $lst) {
			$row = array();
			$this->fillMaterial($lst->TRND_CODE);
			$list2 = $this->otspectd->totalOutbyMatl($mrno, $lst->TRND_CODE);
			$tqtyo_barc = 0;
			foreach ($list2 as $lst2){
				$tqtyo_barc =$lst2->OQTY;
			}
			if($tipe == 'KT2'){
				$dl = $this->master->getMultipleMRKK($mrno);
				$tqtyo_erp = 0;
				foreach ($dl as $d) {
					if($lst->TRND_CODE == $d->KRND_CODE){
						$ttlerp = $this->master->cekTtlTransdErp($d->KRND_MRNO, $d->KRND_CODE);
						$tqtyo_erp = $tqtyo_erp + $ttlerp;	
					}
				}
			}else{
				$tqtyo_erp = $this->master->cekTtlTransdErp($mrno, $lst->TRND_CODE);	
			}
			
			if(floatval($tqtyo_erp) == 0){
				$tqtyo_erp = $this->master->totalOutbyReqxxdErp($mrno, $lst->TRND_CODE);
			}
    		$row[] = trim($this->getNamaFull()," ");
    		$row[] = trim($this->getUnitName()," ");
			$row[] = number_format(floatval($tqtyo_barc),2);
			$row[] = number_format(floatval($tqtyo_erp),2);
			$row[] = number_format(floatval($lst->QTTY_APPR),2);
			$row[] = $lst->TRND_CODE;
			// $row[] = number_format(floatval($lst->QTTY_APPR),2);

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

	public function transMultipleOutBarc(){
    	
    	$Ttlqtty = 0;
    	$tout = 0;
		$appqty = 0;
		$pono = '';
		$consqty = 0;
		$qty_barc = 0;
		$tanggal='';
		$wh='';
    	$mParam = $_POST['params'];
    	$mMrnum = $_POST['mrcd'];
    	$mrno3 = '';
    	$stat = FALSE;
		$lProc = array();
		$mrno = '';
    	array_push($lProc,'KT');
    	array_push($lProc,'RJ');
    	foreach ($mParam as $ls) {
	    	$id =$ls['id'];
	    	$vend_stat =$ls['vend_stat'];
	    	$barcode =$ls['barcode'];
	    	foreach($mMrnum as $m){

	    		$mrnum = $m['mrno'];
	    		$mrno =$mrno . ",'" . $mrnum . "'";
	    	}
	    	$mrno = ltrim($mrno,",");
	    	$Ttlqtty = $ls['qtty'];
	    	$tanggal = $ls['tgl'];
	    	$proses = $ls['proses'];
	    	$wh= $ls['wh'];
	    	$fact= $ls['fact'];
	    	$stt =$ls['stt'];
	    }

	    $close = $this->master->cekClosingBydate($tanggal,'MO');
	    $ck1 = $this->master->cekTranshErp($mrno, $tanggal, 1);
	    if($ck1 == 0 and $wh == 'MAT'){
	    	$ck2 = $this->master->cekTranshErp($mrno,'', 1);
	    	if($ck2 == 1){
			    $row = array();
			    $row['idHeader'] = $id;
			    $row['total'] = $Ttlqtty;
			    $row['list'] = '';
	    		$row['status'] = 'error';
			    $row['message'] = 'Failed, MR sudah pernah di out di tanggal sebelumnya !';
		    	echo json_encode($row);	
	    		return;
	    	}
	    }
	    $closeErp = $this->master->cekClosingErp($tanggal,'MR');
	    if($close == 0 and $closeErp == 0){

	    $ttl = $this->otspectd->getLastSeqnOut($id);
	    $ttl =$ttl + 1;
    	$list = $this->inspectd->detailBarcode($barcode, $vend_stat, $fact);
    	$data = array();
    	$flag = $this->master->cekStatusApprvIn($mrno, 'KT');
    	if($flag == ''){
			$row['idHeader'] = '';
			$row['total'] = $Ttlqtty;
			$row['list'] = '';
			$row['status'] = 'error';
			$row['message'] = 'Failed, MR has not been approved !';
			echo json_encode($row);
    		return;
    	}
    	foreach($list as $lst){
    		if($lst->INSD_CODE == ''){
			    $row['idHeader'] = '';
			    $row['total'] = $Ttlqtty;
			    $row['list'] = '';
			    $row['status'] = 'error';
			    $row['message'] = 'Failed, Material code is empty !';
			    echo json_encode($row);
    			return;
    		}
	    	$barcOut = $this->otspectd->cekBarcodeOut($barcode, $lst->INSD_CODE);
	    	if($barcOut > 0){
			    $row = array();
				    $row['idHeader'] = $id;
				    $row['total'] = $Ttlqtty;
				    $row['list'] = '';
			    	$row['status'] = 'error';
					$row['message'] = 'The barcode is out, please look for another one !';
		    	echo json_encode($row);	
	    		return;
	    	}
			$qty_barc = $lst->INSD_BQTY;
	    	if($qty_barc != 0){
	    		$row = array();
	    		$list2 = $this->master->loadDataMRDetail($mrno,$lst->INSD_CODE, $flag, 1);
	    		if(count($list2) > 0){
			    	foreach($list2 as $lst2){
			    		$appqty = $appqty + $lst2->QTTY_APPR;
			    		$consqty = floatval(0);

			    		$tout = $this->getTotalOutmalt($mrno,$lst->INSD_CODE,1);
			    		if($appqty >= $tout){
			    			$balout = $appqty - $tout;
			    			if($balout >= $qty_barc){

			    				$qty_erp = $this->stock->cekStockERP($lst->INSD_CODE);
			    				$bal_barc = floatval($lst->INSD_BQTY) - floatval($tout);
			    				if($bal_barc > 0){

			    				}
			    				$erp_bal = floatval($lst->INSD_BQTY);


			    				if($erp_bal >= 0){

				    				$Ttlqtty =$Ttlqtty + floatval($qty_barc);
				    				$dtl = array();
				    				$dtl['ttl'] = $ttl;
				    				$dtl['Ttlqtty'] = $Ttlqtty;
				    				$dtl['proses'] = $proses;
				    				$dtl['pono'] = $pono;
				    				$dtl['barcode'] = $barcode;
				    				$dtl['INSD_BQTY'] = $lst->INSD_BQTY;
				    				$dtl['INSD_LINE'] = $lst->INSD_LINE;
				    				$dtl['qty_barc'] = $qty_barc;
				    				$dtl['vend_stat'] = $vend_stat;
				    				$dtl['mrno'] = $mrno;
				    				$dtl['tanggal'] = $tanggal;
				    				$dtl['id'] = $id;
				    				$dtl['wh'] = $wh;

				    				$this->db->trans_begin();
							    		$row =$this->transc->saveOutBarcode($mParam, $list, $dtl, $mMrnum);
						    		if($row['status'] == 'error'){
						    			$stat = TRUE;
						    		}
									if($this->db->trans_status()=== FALSE || $stat){
										$this->db->trans_rollback();
									}else{
										$this->db->trans_commit();	
									}

			    				}else{
				    				$row['idHeader'] = '';
				    				$row['total'] = $Ttlqtty;
				    				$row['list'] = '';
				    				$row['status'] = 'error';
				    				$row['message'] = 'Failed, qtty barcode melebihi qtty ERP !';
			    				}
			    			}else{
			    				$row['idHeader'] = '';
			    				$row['total'] = $Ttlqtty;
			    				$row['list'] = '';
			    				$row['status'] = 'error';
			    				$row['message'] = 'Failed, qtty barcode melebihi qtty approve !';
			    			}
			    		}else{
			    			$row['idHeader'] = '';
			    			$row['total'] = $Ttlqtty;
			    			$row['list'] = '';
			    			$row['status'] = 'error';
			    			$row['message'] = 'Failed, total out melebihi batas qtty approve !';
			    		}
			    	}
	    		}else{
	    			$row['idHeader'] = '';
		    		$row['total'] = $Ttlqtty;
		    		$row['list'] = '';
		    		$row['status'] = 'error';
		    		$row['message'] = 'Failed, Material not match with barcode !';	
	    		}
	    	}else{
	    		$row['idHeader'] = '';
		    	$row['total'] = $Ttlqtty;
		    	$row['list'] = '';
		    	$row['status'] = 'error';
		    	$row['message'] = 'Failed, Qtty barcode is empty !';	
	    	}

	    	// $data[]= $row;
    	}
    	if(count($list) == 0){
    		$row = array();
    		$row['idHeader'] = '';
		    $row['total'] = $Ttlqtty;
		    $row['list'] = '';
		    $row['status'] = 'error';
		    $row['message'] = 'Failed, Factory barcode not match with factory Request !';	
    	}
    	}else{
    		$row = array();
    		$row['status'] = 'error';
		    $row['message'] = 'Failed, Tanggal MR sudah di close !';
    	}

    	echo json_encode($row);
    }


    public function transbarcodeOut(){
    	
    	$Ttlqtty = 0;
    	$tout = 0;
		$appqty = 0;
		$pono = '';
		$consqty = 0;
		$qty_barc = 0;
		$balout = 0;
		$tanggal='';
		$wh='';
    	$mParam = $_POST['params'];
    	$mrno3 = '';
    	$stat = FALSE;
		$lProc = array();
    	array_push($lProc,'KT');
    	array_push($lProc,'KT2');
    	array_push($lProc,'RJ');
    	foreach ($mParam as $ls) {
	    	$id =$ls['id'];
	    	$vend_stat =$ls['vend_stat'];
	    	$barcode =$ls['barcode'];
	    	// if($ls['stt'] == 'MO'){
			// 	$fact ='';	
	    	// }else{
	    		$fact =$ls['fact'];	
	    	// }
	    	$mrno =$ls['mrno'];
	    	$mrold =$ls['mrno'];
	    	$Ttlqtty = $ls['qtty'];
	    	$proc = $ls['opcd'];
	    	$tanggal = $ls['tgl'];
	    	$proses = $ls['proses'];
	    	$wh= $ls['wh'];
	    	$mono= $ls['mono'];
	    	$stt =$ls['stt'];
	    }

	    $flag = $this->master->cekStatusApprv($mrno);
		$close = $this->master->cekClosingBydate($tanggal,'MO');
		if($flag == 'KK2'){
			$dl = $this->master->getMultipleMRKK2($mrno);
			foreach ($dl as $v) {
				$ck1 = $this->master->cekTranshErp($v->KRNH_MRNO, $tanggal);
			    if($ck1 == 0 and $wh == 'MAT'){
			    	$ck2 = $this->master->cekTranshErp($v->KRNH_MRNO,'');
			    	if($ck2 == 1){
					    $row = array();
					    $row['idHeader'] = $id;
					    $row['total'] = $Ttlqtty;
					    $row['list'] = '';
			    		$row['status'] = 'error';
					    $row['message'] = 'Failed, MR sudah pernah di out di tanggal sebelumnya !';
				    	echo json_encode($row);	
			    		return;
			    	}
			    }
			}
		}else{
			$ck1 = $this->master->cekTranshErp($mrno, $tanggal);
		    if($ck1 == 0 and $wh == 'MAT'){
		    	$ck2 = $this->master->cekTranshErp($mrno,'');
		    	if($ck2 == 1){
				    $row = array();
				    $row['idHeader'] = $id;
				    $row['total'] = $Ttlqtty;
				    $row['list'] = '';
		    		$row['status'] = 'error';
				    $row['message'] = 'Failed, MR sudah pernah di out di tanggal sebelumnya !';
			    	echo json_encode($row);	
		    		return;
		    	}
		    }
		}

	    $closeErp = $this->master->cekClosingErp($tanggal,'MR');
	    $closeErpEng = $this->master->cekClosingErpEng($tanggal,'MR');
	    if($close == 0 and $closeErp == 0 and $closeErpEng == 0){
	    $ttl = $this->otspectd->getLastSeqnOut($id);
	    $ttl =$ttl + 1;
    	$list = $this->inspectd->detailBarcode($barcode, $vend_stat, $fact);
    	$data = array();
    	
    	foreach($list as $lst){
    		if($lst->INSD_CODE == ''){
			    $row['idHeader'] = '';
			    $row['total'] = $Ttlqtty;
			    $row['list'] = '';
			    $row['status'] = 'error';
			    $row['message'] = 'Failed, Material code is empty !';
			    echo json_encode($row);
    			return;
    		}
	    	$barcOut = $this->otspectd->cekBarcodeOut($barcode, $lst->INSD_CODE);
	    	if($barcOut > 0){
			    $row = array();
				    $row['idHeader'] = $id;
				    $row['total'] = $Ttlqtty;
				    $row['list'] = '';
			    	$row['status'] = 'error';
					$row['message'] = 'Barcode sudah di out !';
		    	echo json_encode($row);	
	    		return;
	    	}
			$qty_barc = floatval($lst->INSD_BQTY);
	    	if($qty_barc !== 0){
	    		$row = array();
	    		if($proses == 'RJT'){
	    			$list2 = $this->master->loadDataRJDetail($mrno,$lst->INSD_CODE);
	    		}else{
	    			$list2 = $this->master->loadDataMRDetail($mrno,$lst->INSD_CODE, $flag);
	    		}
	    		if(count($list2) > 0){
	    			if($proses != 'RJT'){
			    		foreach($list2 as $lst2){
			    			$appqty = $appqty + $lst2->QTTY_APPR;
			    			$consqty = floatval(0);
			    		}
		    		}else{
			    		foreach($list2 as $lst2){
			    			$appqty = $appqty + $lst2->RECD_QTTY;
			    			$pono = $lst2->RECD_PONO;
			    		}
		    		}
		    		$tout = $this->getTotalOutmalt($mrno,$lst->INSD_CODE);
		    		if($appqty >= $tout){
		    			$balout = round(floatval($appqty) - floatval($tout),4);
		    			if($balout >= floatval($qty_barc)){
		    				$qty_erp = $this->stock->cekStockERP($lst->INSD_CODE);
		    				if(substr($mrno,0,2)!='KT' && substr($mrno,0,2)!='RJ' && substr($mrno,0,2)!='TM'){
								$erp_bal = floatval($qty_erp) - floatval($lst->INSD_BQTY);
		    				}else{
		    					$erp_bal = floatval($lst->INSD_BQTY);
		    				}
		    				if($erp_bal >= 0){

			    				$Ttlqtty =$Ttlqtty + floatval($qty_barc);
			    				$dtl = array();
			    				$dtl['ttl'] = $ttl;
			    				$dtl['Ttlqtty'] = $Ttlqtty;
			    				$dtl['proses'] = $proses;
			    				$dtl['pono'] = $pono;
			    				$dtl['barcode'] = $barcode;
			    				$dtl['INSD_BQTY'] = $lst->INSD_BQTY;
			    				$dtl['INSD_LINE'] = $lst->INSD_LINE;
			    				$dtl['qty_barc'] = $qty_barc;
			    				$dtl['vend_stat'] = $vend_stat;
			    				$dtl['mrno'] = $mrno;
			    				$dtl['mrold'] = $mrold;
			    				$dtl['tanggal'] = $tanggal;
			    				$dtl['id'] = $id;
			    				$dtl['wh'] = $wh;

			    				$this->db->trans_begin();
						    		$row =$this->transc->saveOutBarcode($mParam, $list, $dtl);
					    		if($row['status'] == 'error'){
					    			$stat = TRUE;
					    		}
								if($this->db->trans_status()=== FALSE || $stat){
									$this->db->trans_rollback();
								}else{
									$this->db->trans_commit();	
								}

		    				}else{
			    				$row['idHeader'] = '';
			    				$row['total'] = $Ttlqtty;
			    				$row['list'] = '';
			    				$row['status'] = 'error';
			    				$row['message'] = 'Failed, qtty barcode melebihi qtty ERP !';
		    				}
		    			}else{
		    				$row['idHeader'] = '';
		    				$row['total'] = $Ttlqtty;
		    				$row['list'] = '';
		    				$row['status'] = 'error';
		    				$row['message'] = 'Failed, qtty barcode melebihi qtty approve !';

		    			}
		    		}else{
		    			$row['idHeader'] = '';
		    			$row['total'] = $Ttlqtty;
		    			$row['list'] = '';
		    			$row['status'] = 'error';
		    			$row['message'] = 'Failed, total out melebihi batas qtty approve !';
		    		}
	    		}else{
	    			$row['idHeader'] = '';
		    		$row['total'] = $Ttlqtty;
		    		$row['list'] = '';
		    		$row['status'] = 'error';
		    		$row['message'] = 'Failed, Material not match with barcode !';	
	    		}
	    	}else{
	    		$row['idHeader'] = '';
		    	$row['total'] = $Ttlqtty;
		    	$row['list'] = '';
		    	$row['status'] = 'error';
		    	$row['message'] = 'Failed, Qtty barcode is empty !';	
	    	}

	    	// $data[]= $row;
    	}
    	if(count($list) == 0){
    		$row = array();
    		$row['idHeader'] = '';
		    $row['total'] = $Ttlqtty;
		    $row['list'] = '';
		    $row['status'] = 'error';
		    $row['message'] = 'Failed, Factory barcode not match with factory Request !';	
    	}
    	}else{
    		$row = array();
    		$row['status'] = 'error';
		    $row['message'] = 'Failed, Tanggal MR sudah di close !';
    	}

    	echo json_encode($row);
    }

	public function deleteInByBarcode(){
    	$row = array();
    	$dparam=$_POST['params'];
    	$matl = '';
    	$tout = 0;
    	$ok = 0;
    	$proc = '';
    	$lProc = array();
    	$stat = FALSE;
    	array_push($lProc,'KT');
    	array_push($lProc,'RJ');
    	foreach($dparam as $d){
    		$id = $d['id'];
    		$line = $d['barcode'];
    		$wh = $d['wh'];
    		$tgl_inspect = $d['tgl_inspect'];
    		$vend_stat = $d['vend'];
    	}
		$this->db->trans_begin();

    	$close = $this->master->cekClosingBydate($tgl_inspect,'MI');
	    if($close == 0 ){
	    	$ck_ot = $this->otspectd->cekBarcodeOut($line,'');
	    	if($ck_ot == 0 ){
	    		$detil = array();
				$detil['id'] = $id;
				$detil['line'] = $line;
				$detil['tanggal'] = $tgl_inspect;
				$detil['wh'] = $wh;
				$detil['vend_stat'] = $vend_stat;
	    		$row =$this->transc->deleteInBarcode($dparam, $detil);
	    		if($row['status'] == 'error'){
	    			$stat = TRUE;
	    		}
				if($this->db->trans_status()=== FALSE || $stat){
					$this->db->trans_rollback();
				}else{
					$this->db->trans_commit();	
				}
			}else{
				$row['status'] = 'error';
		    	$row['title'] = 'Error!';
		    	$row['message'] = 'Tidak bisa hapus, barcode sudah di out';
			}
		}else{
		    	$row['status'] = 'error';
		    	$row['title'] = 'Error!';
		    	$row['message'] = 'Tidak bisa hapus, tanggal MR sudah close';
		}

    	echo json_encode($row);
    }

    public function deleteInAll(){
    	$mParam = $_POST['params'];
    	$id = '';
    	foreach($mParam as $d){
    		$id = $d['id'];
			$chk_out = $this->inspectd->cekOutbyIdxx($id);
			if($chk_out > 0){
				$row = array();
				$row['status'] = 'error';
		    	$row['title'] = 'Error!';
		    	$row['message'] = 'Tidak bisa hapus, barcode sudah di out';

		    	echo json_encode($row);
		    	return;
			}
    	}
    	$stat = FALSE;
    	$this->db->trans_begin();
    	
    	$proc =$this->transc->deleteAllDataIn($mParam);
    	if($proc['status'] == 'error'){
    		$stat = TRUE;
    	}
		if($this->db->trans_status()=== FALSE || $stat){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();	
		}
    	echo json_encode($proc);
    }

    public function deleteOutByBarcode(){
    	$row = array();
    	$dparam=$_POST['params'];
    	$matl = '';
    	$tout = 0;
    	$ok = 0;
    	$proc = '';
    	$lProc = array();
    	$stat = FALSE;
    	array_push($lProc,'KT');
    	array_push($lProc,'RJ');
    	foreach($dparam as $d){
    		$id = $d['id'];
    		$mrno = $d['mrno'];
    		$mrno2 = $d['mono'];
    		$vend_stat =$d['vend_stat'];
    		$line = $d['barcode'];
    		$wh = $d['wh'];
    		$proc = $d['opcd'];
    		$mrold = '';
    		$tanggal = '';
    	}
		$this->db->trans_begin();
    	$tgl = $this->otspecth->getTanggalMrOut($mrno, '');
    	$close = $this->master->cekClosingBydate($tgl,'MO');
    	$closeErp = $this->master->cekClosingErp($tgl,'MR');
	    if($close == 0 and $closeErp == 0){
    		$detil = array();
			$detil['id'] = $id;
			$detil['mrno'] = $mrno;
			$detil['matl'] = $matl;
			$detil['line'] = $line;
			$detil['vend_stat'] = $vend_stat;
			$detil['proc'] = $proc;
			$detil['mrold'] = $mrold;
			$detil['mrno2'] = $mrno2;
			$detil['tanggal'] = $tanggal;
			$detil['wh'] = $wh;
    		$row =$this->transc->deleteOutBarcode($dparam, $detil);
    		if($row['status'] == 'error'){
    			$stat = TRUE;
    		}
			if($this->db->trans_status()=== FALSE || $stat){
				$this->db->trans_rollback();
			}else{
				$this->db->trans_commit();	
			}
		}else{
		    	$row['status'] = 'error';
		    	$row['title'] = 'Error!';
		    	$row['message'] = 'Tidak bisa hapus, tanggal MR sudah close';
		}

    	echo json_encode($row);
    }

    public function deleteOutAll(){
		$stat = FALSE;
    	$mParam = $_POST['params'];
    	$this->db->trans_begin();
    	$proc =$this->transc->deleteAllDataOut($mParam);
    	if($proc['status'] == 'error'){
    			$stat = TRUE;
    	}
		if($this->db->trans_status()=== FALSE || $stat){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();	
		}
    	echo json_encode($proc);
    }

	public function loadDataRjByParam(){

    	$pono = $_POST['pono'];
    	$rjno = $_POST['rjno'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$fact = $_POST['fact'];
		$wh = $_POST['wh'];

		$list = $this->master->loadDataRJ($pono, $rjno, $dt1, $dt2, $fact, $wh);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row[] = $no++;
			$row[] = $lst->RECH_PONO;
			$row[] = $lst->RECH_MINO;
			$row[] = date("Y-m-d", strtotime($lst->RECH_DATE));
			$row[] = floatval($lst->TOTAL_QTTY * -1);
			$row[] = $lst->RECH_VEND;
			// $row[] = $lst->RECH_SJNO;
			// $row[] = $lst->RECH_NTRE;
			$row[] = $lst->RECH_RJNO;
			$row[] = $lst->RECH_PART;
			$row[] = $lst->RECH_POID;
			$row[] = $lst->POMH_FACT;
			$row[] = $lst->wh;

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

    public function loadDataRjByDetail(){

		$rjno = $this->uri->segment(3);
		$matl = '';
		$list = $this->master->loadDataRJDetail($rjno, $matl);
		$data = array();
		$no = 1;
		$tqtyo = 0;
		
		foreach ($list as $lst) {
			$this->setListMaterial($lst->RECD_MATL);
		}
		$this->loadMaterial();
		foreach ($list as $lst) {
			$row = array();

			$this->fillMaterial($lst->RECD_MATL);
			$list2 = $this->otspectd->totalOutbyMatl($rjno, $lst->RECD_MATL);
			foreach ($list2 as $lst2){
				$tqtyo =$lst2->OQTY;
			}
			$row[] = $lst->RECD_MATL;
    		$row[] = trim($this->getNamaFull()," ");
    		$row[] = trim($this->getUnitName()," ");
			$row[] = number_format(floatval($tqtyo),2);
			$row[] = number_format(floatval($lst->RECD_QTTY),2);

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

    public function dataSavesplit(){
    	$qty_split = 0;
    	$old_id = '';
    	$id = '';
    	$dparam=$_POST['params'];
    	$matname='';
    	$sjno='';
    	$sisa = 0;
    	$user ='';
    	$total = 0;
    	$data = array();
    	$ok = '';
    	$row2 = array();
		$stat = FALSE;

    	foreach($dparam as $d){
    		$barcode = $d['barcode'];
    		$vend_stat = floatval($d['vend_stat']);
    		$barc_nama = $d['bar_nama'];
    		$qty_barcode = floatval($d['qty_barcode']);
    		$user = $d['user'];
    		$qty_split = $qty_split + floatval($d['qty_split']);
    	}

    	if($barcode == ''){
    		$row2 = array();
    		$row2['stat'] ='NO';
			$row2['message'] = 'Failed, data failed to saved, re-chek again !';
    		echo json_encode($row2);
    	}

    	$sisa = floatval($qty_barcode) - floatval($qty_split);
    	if($qty_split <= $qty_barcode){
	    	$list = $this->inspectd->getDetailBarcodeSplit($barcode, $vend_stat);
	    	foreach($dparam as $d){
		    	foreach($list as $l){
		    		if($l->INSD_NAME != ''){
		    			$matname = $l->INSD_NAME;
		    		}else{
		    			$matname = $barc_nama;
		    		}
		    		if($l->INSH_SJNO != ''){
		    			$sjno = $l->INSH_SJNO;
		    		}else{
		    			$sjno = $l->INSD_SJNO;
		    		}
		    		$row = array();
		    		$old_id = $l->INSH_IDXX;
		    		$total = $l->INSH_TOTAL;
		    		$row['tranno']= $l->INSH_IDXX;
		    		$row['sjno']=$sjno;
		    		$row['tgl_inspect']=$l->INSH_DATE;
		    		$row['wh']=$l->INSH_WHID;
		    		$row['vstat']=$l->INSH_VSAT;
		    		$row['pono']=$l->INSD_PONO;
		    		$row['lineItem'] = $l->INSD_LVND;
		    		$row['matcode']=$l->INSD_CODE;
		    		$row['matunit']=$l->INSD_UNIT;
		    		$row['kem']=$l->INSD_KEMAS;
		    		$row['jmlkem']=$l->INSD_TKEMAS;
		    		$row['price']=$l->INSD_PRIC;
		    		$row['style']=$l->INSD_PART;
		    		$row['release']=$l->INSD_NBRN;
		    		$row['umcd']=$l->INSD_UMCD;
		    		$row['matname']=$matname;
		    		$row['seqn']=$l->INSD_SEQN;
		    		$row['seqh']=$l->INSD_SEQH;
		    		$row['vend']=$l->INSD_VEND;
		    		$row['mino']=$l->INSD_MINO;
		    		$row['user']=$user;
		    		$row['qtty']=floatval($d['qty_split']);
		    		$row['serial']=0;

		    		$data[]=$row;
		    	}
	    	}
    	}
		$this->db->trans_begin();
		$detil = array();
		$detil['old_id'] = $old_id;
		$detil['total'] = $total;
		$detil['barcode'] = $barcode;
		$detil['sisa'] = $sisa;
		$detil['vend_stat'] = $vend_stat;
		$detil['qty_barcode'] = $qty_barcode;

    	$row2 = $this->transc->addSplitBarcode($data, $detil);
    	if($row2['stat'] == 'NO'){
    		$stat = TRUE;
    	}
		if ($this->db->trans_status() === FALSE || $stat){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
		}

		echo json_encode($row2);
    }

    public function dataDeletesplit(){
    	$dparam=$_POST['params'];
    	$stat = FALSE;
    	$this->db->trans_begin();
    	$row2 = $this->transc->DeletesplitBarc($dparam);
    	if($row2['status'] == 'error'){
    		$stat = TRUE;
    	}
		if ($this->db->trans_status() === FALSE || $stat){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
		}
    	echo json_encode($row2);
    }

    public function cekPrintStat($id, $param){
    	$list= $this->inspectd->cekPrintBarcode($id, $param);
    	$data = array();
    	foreach($list as $l){
    		if($l->INSD_PRNT == 0){
    			$row = array();
    			$row['barcode'] = $l->INSD_LINE;
    			$row['qtty'] = $l->INSD_IQTY;
    			$data[]=$row;
    		}
    	}
    	return $data;
    }

    public function loadTranOutDetail(){
		$mrno = $this->uri->segment(3);
		$trno = $this->uri->segment(4);
		$matl = '';
		$list = $this->master->loadDataMRDetail($mrno, $matl);
		$data = array();
		$no = 1;
		$tqtyo = 0;

		foreach ($list as $lst) {
			$this->setListMaterial($lst->TRND_CODE);
		}
		$this->loadMaterial();
		foreach ($list as $lst) {
			$row = array();
			$this->fillMaterial($lst->TRND_CODE);
			$list2 = $this->otspectd->totalOutbyMatl($mrno, $lst->TRND_CODE);
			foreach ($list2 as $lst2){
				$tqtyo =$lst2->OQTY;
			}
			$row[] = $lst->TRND_CODE;
    		$row[] = trim($this->getNamaFull()," ");
    		$row[] = trim($this->getUnitName()," ");
			$row[] = number_format(floatval($tqtyo),2);
			$row[] = number_format(floatval($lst->TRND_QTTY),2);
			$row[] = number_format(floatval($lst->REAL_QTTY),2);

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

	public function loadTodayLamScan(){
		$obj = json_decode($this->getDateServerdt());

		$mchn = $this->uri->segment(3);
		$tgl1 = $obj->tgl;
		$tgl2 = $obj->tgl;

		$n = "";
		$nama="";
		$comp = "";
		$no = 1;
		
		$list = $this->lammod->loadScanLam($mchn, $tgl1, $tgl2);
		foreach ($list as $lst) {
			$this->setListMaterial($lst->COND_CODE);
		}
		$this->loadMaterial();
		foreach ($list as $lst) {
		$nama = "";
				foreach ($list as $lst2) {
					if ($lst->LMQB_CODE.$lst->LMQB_COMP == $lst2->LMQB_CODE.$lst2->LMQB_COMP){
						$this->fillMaterial($lst2->COND_CODE);
						$n = trim($this->getNamaFull()," ");
						$nama = $nama . "+ " . $n;
					}
				}

			if($comp != $lst->LMQB_CODE.$lst->LMQB_COMP){
				$row = array();
				$row[] = $no++;
				$row[] = $lst->LMQB_STYL;
				$row[] = $lst->LMQB_COMP;
				$row[] = $lst->CODD_DESC;
	    		$row[] = ltrim($nama, '+');
	    		$row[] = "M";
				$row[] = number_format(floatval($lst->LMQB_QTTY),2);
				$row[] = $lst->LMQB_CODE;

				$data[] = $row;
			}
			$comp = $lst->LMQB_CODE.$lst->LMQB_COMP;
		}

		$output = array(
						"data" => $data
				);
		
		echo json_encode($output);
    }

    public function updateTesTransaction(){
    	$proc =$this->transc->updateTest();
    	echo json_encode($proc);
    }

}

/* End of file  */
/* Location: ./application/controllers/ */