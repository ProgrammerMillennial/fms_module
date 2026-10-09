<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Cglobal.php");
require_once BASEPATH.'../assets/phpqrcode/qrlib.php';

class Transaction extends Cglobal {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('inspectiond_model','inspectd');
		$this->load->model('inspectionh_model','inspecth');
		$this->load->model('outinspectd_model','otspectd');
		$this->load->model('outinspecth_model','otspecth');
		$this->load->model('inspectiond_split_model','inspectds');
		// if($this->session->userdata('status') != "login"){
		// 	redirect(base_url());
		// }
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
						if($q != $lst2->RECD_PO_SEQN ){
							$qPo = $qPo + floatval($lst2->POMD_QTTY);
							$qIns = $qIns + floatval($lst2->POMD_INSP_QTTY);
							$qBal = $qBal + floatval($lst2->BALANCE);
						}
						$q = $lst2->RECD_PO_SEQN;
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
		$list = $this->master->poDetailVendor('', $wh, $ttl_mi);
		$data = array();
		$no = 1;
		$id = "";
		foreach ($list as $lst) {
			$row = array();
			if($id != $lst->linebarcode)
			{
			if($lst->namaitem == ''){
				$this->loadMaterial($lst->itemId);
				$nama = trim($this->getnamaFull()," ");
			}else{
				$nama = $lst->namaitem;
			}

				$row[] = $lst->linebarcode;
				$row[] = $nama;
				$row[] = $lst->satuan;
				$row[] = $this->formatNumber($lst->qtty,2);
				$row[] = $lst->stat;
				$row[] = $lst->itemId;
				$data[] = $row;
			}

			$id = $lst->linebarcode;
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

	    if ($this->uri->segment(4) != "" && $this->uri->segment(4) != "MAT" && $this->uri->segment(4) != "ENG" && substr($this->uri->segment(4),0,2) != "MI"){
    		$matcode = strval($this->uri->segment(4));
	    }else {
	    	$matcode = $matcode1;
	    }

	    // var_dump($wh);

		$list = $this->master->getMiDetailMaterial($po, $mino, $matcode, $wh);
		// var_dump($list);
		$data = array();
		$no = 1;
		$id = "";
		foreach ($list as $lst) {
			$row = array();
			$this->loadMaterial($lst->POMD_CODE);

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

    public function inspectionDetail($id){
    	$list = $this->master->inspectDataHeader($id);
    	$id2 = "";
    	$data1 = array();
    	$data2 = array();
    	$kode = "";
		foreach ($list as $lst) {
			$row2 = array();
			
			$row2['barc'] = $lst->line;
			$row2['kem'] = $lst->kemasan;
			$row2['jmlkem'] = floatval($lst->jmlKem);
			$row2['qtty'] = floatval($lst->qtty);

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
		echo json_encode($data1);
		// print_r($data1);
		// var_dump($data1);
		// $this->load->view('transaction/modalInspectDetail',$data1);
    }

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
    	$mParam = $_POST['params'];	
		$details = array();
		// $barc = array();
		$row = array();
		$cnt_line = 0;
		$tes = 0;
		$messages = '';
		$barcode = '';
		$dt = '';
		$wh = '';
		$totalBarcode = 0;
		$pono = '';
		$mino = '';
		$matl = '';
		$seqn = '';

	    foreach ($mParam as $lst) {
	    	$Ttlqtty = $Ttlqtty + $lst['qtty'];
	    	$transno = $lst['tranno'];
	    	$pono = $lst['pono'];
	    	$mino = $lst['mino'];
	    	$matl = $lst['matcode'];
	    	$seqn = $lst['seqn'];
	    	$wh = $lst['wh'];
	    	// $barc['line'] = $lst['serial'];
	    	if($lst['qtty'] > 0){
		    	if($lst['serial'] != ''){
		    		$cnt_line = $cnt_line + 1;
		    		$dt[] = $lst['serial'];
		    	}
	    	}
	    }
	    $this->db->trans_begin();
	    $lst_barc_act = $this->inspectd->cekTotalBarcode($transno);
	    if($lst_barc_act->CNT == 0){
	    	$id = $this->inspecth->CalculateHeader($mParam, $Ttlqtty);
	    	$list= $this->inspectd->CalculateDetail($mParam, $id, '');
			// $this->updateInspPO($pono, $matl, $seqn, $wh, $list);

	    	// $totalBarcode = $this->inspectd->TotalBarcodeInspect($pono, $matl, $seqn);
	    	// foreach ($totalBarcode as $tb) {
	    	// 	$this->master->updateInspectPO($list, $wh, $tb->QTTY, $pono, $matl, $seqn);
	    	// }
	    	$messages = 'Success, data has been saved !';
	    }else{
	    	if($cnt_line < $lst_barc_act->CNT){
		    	$lst_barc = $this->cekPrintStat($transno, $dt);
			    	if(count($lst_barc) != 0){
			    		foreach($lst_barc as $lb){
			    			$this->inspectd->delete_by_barcode($lb['barcode']);
			    			$code = $lb['barcode'];
			    			$barcode = $barcode . ", " . $code;
			    		}
			    	// $this->inspectd->delete_detail($transno);
					    if($Ttlqtty == 0){
					    		$this->inspecth->delete_header($transno);
					    	}
					    $messages = 'Success, data with barcode'. ltrim($barcode, ',') .' has been deleted';
			    	}else{
			    		$messages = 'Failed, barcode has been printed';
			    	}
	    	}else{
	    		$id = $this->inspecth->CalculateHeader($mParam, $Ttlqtty);
				$list= $this->inspectd->CalculateDetail($mParam, $id, '');
				// $this->updateInspPO($pono, $matl, $seqn, $wh, $list);

		    	// $totalBarcode = $this->inspectd->TotalBarcodeInspect($pono, $matl, $seqn);
		    	// foreach ($totalBarcode as $tb) {
		    	// 	$this->master->updateInspectPO($list, $wh, $tb->QTTY, $pono, $matl, $seqn);
		    	// }
	    		// $this->master->updateInspectPO($list, $wh, $totalBarcode, $pono, $matl, $seqn);

				$messages = 'Success, data has been saved !';
	    	}
	        // if($Ttlqtty > 0) {
	        // 	$list= $this->inspectd->CalculateDetail($mParam, $id, $lst_barc);
	    	// }
	    }
       	if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$row['id'] = '';
			$row['detail'] = '';
			$row['stat'] = 'N';
			$row['message'] = 'Failed, data cannot be saved, re-check again !';
		}else{
			$this->db->trans_commit();
			if($list == ''){
				$row['id'] = '';
			}else{
				$row['id'] = $id;
			}
			$row['detail'] = $list;
			$row['stat'] = 'Y';
			$row['message'] = $messages;
		}
	    // print_r($lst_barc);
		echo json_encode($row);
    }

    function updateInspPO($pono, $matl, $seqn, $wh, $list){
    	$totalBarcode = $this->inspectd->TotalBarcodeInspect($pono, $matl, $seqn);
		    foreach ($totalBarcode as $tb) {
		    	$this->master->updateInspectPO($list, $wh, $tb->QTTY, $pono, $matl, $seqn);
		    }
	    // $this->master->updateInspectPO($list, $wh, $totalBarcode, $pono, $matl, $seqn);
    }

    public function printNewBarcode(){
    	
    	$tipe = $this->uri->segment(3);
    	$line = $this->uri->segment(4);

    	$data = array();
    	$list = $this->inspectd->loadDataBarcode($tipe,$line);
    	foreach ($list as $lst) {
    		$this->loadMaterial($lst->headerMatCode);
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
		$data2 = array();

    	foreach ($mParam as $lst) {
	    	$tranno = $lst['tranno'];
	    	$barcode = $lst['barcode'];
	    	$tanggal = $lst['tgl_inspect'];
	    	$wh = $lst['wh'];
	    	$pono = $lst['pono'];
	    	$stat = $lst['vstat'];
	    	$user = $lst['user'];
	    }

	    // print_r($mParam);
		$list = $this->master->getdetailPoVend($barcode, $wh);
		$data=array();
		foreach ($list as $lst) {
			$this->loadMaterial($lst->itemId);

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
			$pono = $lst->Pono;
			$matl = $lst->itemId;

			$row['seqn'] = $seqn;
			$row['seqh'] = $seqh;
			$row['matcode'] = $lst->itemId;
			$row['matname'] = trim($this->getnamaFull()," ");
			$row['matunit'] = $lst->satuan;
			$row['pono'] = $lst->Pono;
			$row['tgl_inspect'] = $tanggal;
			// $row['tgl_inspect'] = $this->formatTgl($lst->RECH_DATE);
			$row['mino'] = '';
			$row['sjno'] = $lst->noSuratJalan;
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
    		$row['INSD_LINE'] = $lst->linebarcode;
    		$row['INSD_PONO'] = $lst->Pono;
    		$row['SEQH'] = $seqh;
    		$row['SEQN'] = $seqn;
    		$row['INSD_CODE'] = $lst->itemId;

    		$data2[] = $row2;
		}

		$this->db->trans_begin();
		$row = array();
		$l = $this->inspectd->cekBarcodeVendorExist($barcode);
		if($l->CNT == 0){
	        $id = $this->inspecth->CalculateHeader($data, $Ttlqtty);
	        $list= $this->inspectd->CalculateDetail($data, $id);
	        $tqty = $this->setTotalInspectVend($id);
	        // $this->updateInspPO($pono, $matl, $seqn, $wh, $data2);

	        $row['id'] = $id;
	        $row['pono'] = $pono;
	        $row['wh'] = $wh;
			$row['total'] = $tqty;
			$row['detail'] = $list;
			$row['status'] = 'success';
			$row['message'] = 'Success, the data has been saved !';

    	}else{

    		$id = '';
			$row['status'] = 'error';
			$row['message'] = 'Failed, duplicate scan !';
    	}

       	if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();

			echo json_encode($row);
		}
    }

    public function setTotalInspectVend($id){
    $total = 0;
    	$det= $this->inspectd->getTotalQttyVend($id);
        foreach($det as $dt){
        	$total = $dt->qtty;
        	$param= array(
			            'INSH_IDXX'=>$id
			        );
        	$details= array(
			            'INSH_TOTAL'=>floatval($dt->qtty)
			        );
        	$head = $this->inspecth->updateTotalHeader($param, $details);
        }
        return $total;
    }

    public function getTotalOutmalt($mrno, $matl){
    	$tout = 0;
		$list3 = $this->otspectd->totalOutbyMatl($mrno,$matl);
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

	public function loadDataMrByParam(){

    	$mrno = $_POST['mrno'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$fact = $_POST['fact'];
		$opcd = $_POST['opcd'];
		$wh = $_POST['wh'];
		$tipe = $_POST['tipe'];

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

			$data[] = $row;
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

		$matl = '';
		$list = $this->master->loadDataMRDetail($mrno, $matl, $tipe);
		$data = array();
		$no = 1;
		$tqtyo = 0;
		foreach ($list as $lst) {
			$row = array();
			$this->loadMaterial($lst->TRND_CODE);
			$list2 = $this->otspectd->totalOutbyMatl($mrno, $lst->TRND_CODE);
			foreach ($list2 as $lst2){
				$tqtyo =$lst2->OQTY;
			}
			$row[] = $lst->TRND_CODE;
    		$row[] = trim($this->getNamaFull()," ");
    		$row[] = trim($this->getUnitName()," ");
			$row[] = number_format(floatval($tqtyo),2);
			$row[] = number_format(floatval($lst->QTY_OUT),2);
			// $row[] = number_format(floatval($lst->REAL_QTTY),2);

			$data[] = $row;
		}
		$output = array(
						"data" => $data
				);
		echo json_encode($output);
    }

    public function transbarcodeOut(){
    	$Ttlqtty = 0;
    	$tout = 0;
		$appqty = 0;
		$consqty = 0;
		$qty_barc = 0;
    	$mParam = $_POST['params'];
    	foreach ($mParam as $ls) {
	    	$id =$ls['id'];
	    	$vend_stat =$ls['vend_stat'];
	    	$barcode =$ls['barcode'];
	    	$mrno =$ls['mrno'];
	    	$Ttlqtty = $ls['qtty'];
	    	$proc = $ls['opcd'];
	    }
	    $this->db->trans_begin();
	    $ttl = $this->otspectd->getLastSeqnOut($id);
	    $ttl =$ttl + 1;
    	$list = $this->inspectd->detailBarcode($barcode, $vend_stat);
    	$data = array();
    	foreach($list as $lst){
			$qty_barc = $lst->INSD_BQTY;
	    	if($qty_barc != 0){
	    		$row = array();
	    		if($proc == 'RJT'){
	    			$list2 = $this->master->loadDataRJDetail($mrno,$lst->INSD_CODE);
	    		}else{
	    			$list2 = $this->master->loadDataMRDetail($mrno,$lst->INSD_CODE);
	    		}
	    		if(count($list2) > 0){
	    			if($proc != 'RJT'){
			    		foreach($list2 as $lst2){
			    			$appqty = $lst2->TRND_QTTY;
			    			$consqty = $lst2->REAL_QTTY;
			    		}
		    		}else{
			    		foreach($list2 as $lst2){
			    			$appqty = $lst2->RECD_QTTY;
			    		}
		    		}
		    		$tout = $this->getTotalOutmalt($mrno,$lst->INSD_CODE);
		    		if($appqty > $tout){
		    			$balout = $appqty - $tout;
		    			if($balout >= $qty_barc){
		    				$Ttlqtty =$Ttlqtty + floatval($qty_barc);
					        $dh = $this->otspecth->outSavedH($mParam, $ttl, $Ttlqtty, $proc);
					        $dt = $this->otspectd->outSavedD($dh, $mParam, $list, $ttl);
					        $du = $this->inspectd->updateBarcodeqty($lst->INSD_LINE, $qty_barc, 's', $proc, $vend_stat);

					        $ck1 = $this->master->cekTranshErp($mrno);
					        if($ck1 == 0){
					        	$th = $this->master->saveTranshErp($mParam, $mrno, $list);
					        }
		    				$row['idHeader'] = $dh;
		    				$row['total'] = $Ttlqtty;
		    				$row['list'] = $dt;
		    				$row['message'] = 'Success, data has been saved !';
		    			}else{
		    				$row['idHeader'] = '';
		    				$row['total'] = $Ttlqtty;
		    				$row['list'] = '';
		    				$row['message'] = 'Failed, qtty barcode melebihi balance out !';
		    			}
		    		}else{
		    			$row['idHeader'] = '';
		    			$row['total'] = $Ttlqtty;
		    			$row['list'] = '';
		    			$row['message'] = 'Failed, total out melebihi batas qtty approve !';
		    		}
	    		}else{
	    			$row['idHeader'] = '';
		    		$row['total'] = $Ttlqtty;
		    		$row['list'] = '';
		    		$row['message'] = 'Failed, Material not match with barcode !';	
	    		}
	    	}else{
	    		$row['idHeader'] = '';
		    	$row['total'] = $Ttlqtty;
		    	$row['list'] = '';
		    	$row['message'] = 'Failed, Qtty barcode is empty !';	
	    	}

	    	// $data[]= $row;
    	}
    	if(count($list) == 0){
    		$row = array();
    		$row['idHeader'] = '';
		    $row['total'] = $Ttlqtty;
		    $row['list'] = '';
		    $row['message'] = 'Failed, barcode material not found !';	
    	}
    	if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
    		echo json_encode($row);
    	}
    }

    public function deleteOutByBarcode(){
    	$row = array();
    	$dparam=$_POST['params'];
    	$matl = '';
    	$tout = 0;
    	foreach($dparam as $d){
    		$id = $d['id'];
    		$mrno = $d['mrno'];
    		$vend_stat = $d['vend_stat'];
    		$line = $d['barcode'];
    		$proc = $d['opcd'];
    	}
    	$this->otspectd->delete_detail_out($dparam);
	    $ttl = $this->otspectd->getLastSeqnOut($id);
		$tout = $this->getTotalOutmalt($mrno,$matl);
		$list = $this->inspectd->detailBarcode($line, $vend_stat);
		foreach($list as $lst){
			$du = $this->inspectd->updateBarcodeqty($lst->INSD_LINE, $lst->INSD_IQTY, 'd', $proc, $vend_stat);
		}
	    if($ttl == 0){
	    	$this->otspecth->delete_H_all($id);
	    	$row['total'] = 0;
	    	$row['delAll'] = '1';
	    }else{
	    	$row['total'] = $tout;
	    	$row['delAll'] = '0';
	    }
    	echo json_encode($row);
    }

    public function deleteOutAll(){
    	$dparam=$_POST['params'];
    	foreach($dparam as $d){
    		$id = $d['id'];
    	}
    	$this->otspectd->delete_D_all($id);
    	$this->otspecth->delete_H_all($id);
    	$row = array();
    	$row['delAll'] = '1';
    	echo json_encode($row);
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
			$row = array();
			$this->loadMaterial($lst->RECD_MATL);
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
    	$id = '';
    	$dparam=$_POST['params'];
    	$matname='';
    	$sjno='';
    	$sisa = 0;
    	$data = array();

    	foreach($dparam as $d){
    		$barcode = $d['barcode'];
    		$vend_stat = floatval($d['vend_stat']);
    		$barc_nama = $d['bar_nama'];
    		$qty_barcode = floatval($d['qty_barcode']);
    		$qty_split = $qty_split + floatval($d['qty_split']);
    	}
    	$this->db->trans_begin();
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

		    		$id = $l->INSH_IDXX;
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
		    		$row['qtty']=floatval($d['qty_split']);
		    		$row['serial']=0;

		    		$data[]=$row;
		    	}
	    	}
    	}
    	$list2= $this->inspectd->CalculateDetail($data, $id);
    	$du = $this->inspectd->updateBarcodeqty($barcode, $sisa, 's', 'SPT', $vend_stat);
    	$ds = $this->inspectds->saveSplitBarcode($list2, $barcode, $id);
		$row2 = array();
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$row2['stat'] ='NO';
			$row2['message'] = 'Failed, data failed to saved, re-chek again !';
		}else{
			$this->db->trans_commit();
			$row2['stat'] = 'OK';
			$row2['qtty_sisa'] = $sisa;
			$row2['detail'] = $list2;
			$row2['message'] = 'Success, data split barcode has been saved !';
		}
		echo json_encode($row2);
    }

    public function dataDeletesplit(){
		$qty_split = 0;
    	$id = '';
    	$dparam=$_POST['params'];
    	$matname='';
    	$sisa = 0;
    	$this->db->trans_begin();
    	foreach($dparam as $d){
    		$barcode = $d['barcode'];
    		$barc_nama = $d['bar_nama'];
    		$qty_barcode = $d['qty_barcode'];
    		$vend_stat = floatval($d['vend_stat']);
    		$qty_split = $qty_split + $d['qty_split'];
    		$serial = $d['serial'];
    		$dt = $this->inspectd->delete_by_barcode($serial);
    		$ds = $this->inspectds->deleteSplitBarcode($serial);
    	}
    	$sisa = floatval($qty_split);
    	$du = $this->inspectd->updateBarcodeqty($barcode, $sisa, 'd', 'SPT', $vend_stat);
		$row2 = array();
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$row2['stat'] ='NO';
			$row2['message'] = 'Failed, data failed to saved, re-chek again !';
		}else{
			$this->db->trans_commit();
			$row2['stat'] = 'OK';
			$row2['qtty_sisa'] = $sisa;
			$row2['message'] = 'Success, data split barcode has been saved !';
		}
		echo json_encode($row2);
    }

    public function cekPrintStat($id, $param){
    	// $id = 'INH20231212-00001';
    	// $param = array('B202312121', 'B202312122', 'B202312193');
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

	public function loadTranOut(){

    	$mrno = $_POST['mrno'];
		$dt1 = $_POST['dt1'];
		$dt2 = $_POST['dt2'];
		$fact = $_POST['fact'];
		$opcd = $_POST['opcd'];
		$wh = $_POST['wh'];
		$list = $this->otspecth->loadDataOutBarcode('',$mrno, $dt1, $dt2, $opcd, $fact, $wh);
		$data = array();
		$no = 1;
		foreach ($list as $lst) {
			$row = array();
			$row[] = $no++;
			$row[] = $lst->ONSH_IDXX;
			$row[] = $lst->ONSD_MRNO;
			$row[] = date("Y-m-d", strtotime($lst->ONSH_DATE));
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
		echo json_encode($output);
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
			$row = array();
			$this->loadMaterial($lst->TRND_CODE);
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

    public function tes(){
    	$wh = 'MAT';
    	$ttl_mi = 0;
    	$data = array();
    	$list = $this->master->poDetailVendor('', $wh, $ttl_mi);
    	foreach($list as $lst){
    		$row = array();
    		$row[] = $lst->linebarcode;
    		$data[] = $row;
    	}
    	$t = $this->convertSqlIn($data);
    	print_r($t);
    }

}

/* End of file  */
/* Location: ./application/controllers/ */