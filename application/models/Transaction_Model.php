<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Barcodestock_model.php");

class Transaction_model extends BarcodeStock_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function saveOutBarcode($mParam, $list, $dtl, $dtl_d = '')
	{
		$Ttlqtty = 0;
    	$tout = 0;
		$appqty = 0;
		$pono = '';
		$consqty = 0;
		$qty_barc = 0;
		$tanggal='';
		$wh='';
    	$mrno3 = '';
    	$row = array();
		$lProc = array();
    	array_push($lProc,'KT');
    	array_push($lProc,'TM');
    	array_push($lProc,'RJ');

		$this->db->trans_begin();

			try {
					$dh = $this->otspecth->outSavedH($mParam, $dtl['ttl'], $dtl['Ttlqtty'], $dtl['proses'], $dtl['mrno']);
					$msg = $this->checkErrorSave($dh);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(1));
					}
					$dt = $this->otspectd->outSavedD($dh, $mParam, $list, $dtl['ttl'], $dtl['pono'], $dtl['mrno']);
					$msg = $this->checkErrorSave($dt);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(2));
					}

					$mrtype = substr($dtl['mrno'],0,2);
					// if($mrtype == 'KT' || $mrtype == 'TM'){
					// 	$ishd = $this->master->saveInspecthDetail($dh, $dtl_d);
					// 	$msg = $this->checkErrorSave($ishd);
					// 	if ($msg == 'error'){
					// 		throw new Exception($this->listMessage(19));
					// 	}
					// }      

					if($dtl['vend_stat'] == '0'){
						if(substr($dtl['barcode'],0,1) != 'B'){
							// $du = $this->inspectd->updateOldBarcodeqty($dtl['barcode'], $dtl['INSD_BQTY'], 'O');
							$du = $this->updateOldBarcodeqty($dtl['barcode'], $dtl['INSD_BQTY'], 'O');
						}else{
							// $du = $this->inspectd->updateBarcodeqty($dtl['INSD_LINE'], $dtl['qty_barc'], 's', $dtl['proses'], $dtl['vend_stat']);
							$du = $this->updateBarcodeqty($dtl['INSD_LINE'], $dtl['qty_barc'], 's', $dtl['proses'], $dtl['vend_stat']);
						}
						
					}else{
							// $du = $this->inspectd->updateBarcodeqty($dtl['INSD_LINE'], $dtl['qty_barc'], 's', $dtl['proses'], $dtl['vend_stat']);
						$du = $this->updateBarcodeqty($dtl['INSD_LINE'], $dtl['qty_barc'], 's', $dtl['proses'], $dtl['vend_stat']);
					}

					$msg = $this->checkErrorSave($du);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(3));
					}

					
					if (!in_array($mrtype, $lProc)) {
							if($dtl['wh'] == 'MAT'){
								$cte = $this->master->cekTranshErp($dtl['mrno'],'');
								$th = $this->master->saveTranshErp($mParam, $dtl['mrno'], $list, $cte, $dtl['Ttlqtty']);
								$msg = $this->checkErrorSave($th);
								if ($msg == 'error'){
									throw new Exception($this->listMessage(4));
								}
							}else{
									foreach ($mParam as $ls) {
										$l0 = $this->master->getHeaderDataMO($dtl['mrno']);
										$dlist = array();
										foreach($l0 as $val){
											$r['tgl'] =$ls['tgl'];
											$r['dept'] =$val->MRQH_OPCD;
											$r['user'] =$ls['user'];
											$r['user2'] =$val->MRQH_EMPL;
											$r['comp'] ='S004';
											$r['rmks'] =$val->MRQH_TITLE;
											$r['mrno'] =$dtl['mrno'];
											$r['fact'] =$val->MRQH_DEST;
											$dlist[]= $r;

										}
									}
											
									$mrno3 = $this->master->saveReqxxhErp($dlist, $dtl['mrold'], $list, $dtl['tanggal']);
									$msg = $this->checkErrorSave($mrno3);
									if ($msg == 'error'){
									    throw new Exception($this->listMessage(4));
									}
							}
					}

				    if ($this->db->trans_status() === FALSE) {
    					throw new Exception('DB Error');
					}else{
						$this->db->trans_commit();
					    $row['idHeader'] = $dh;
					    $row['total'] = $dtl['Ttlqtty'];
					    $row['list'] = $dt;
					    $row['mrno3'] = $mrno3;
					    $row['status'] ='success';
					    $row['message'] = 'Success, data has been saved !';
						return $row;
					}

			}catch (Exception $e){
				$this->db->trans_rollback();
				$row = array();
				$row['idHeader'] = $dtl['id'];
				$row['total'] = $dtl['Ttlqtty'];
				$row['list'] = '';
				$row['mrno3'] = '';
				$row['status'] = 'error';
				$row['message'] = $e->getMessage();
				return $row;
			}

    	return $row;
	}

	public function addSplitBarcode($data, $dtl){
    	// $qty_split = 0;
    	$old_id = $dtl['old_id'];
    	$id = '';
    	$total = $dtl['total'];
    	$barcode = $dtl['barcode'];
    	$vend_stat = $dtl['vend_stat'];
    	$qty_barcode = $dtl['qty_barcode'];
    	$sisa = $dtl['sisa'];
    	$this->db->trans_begin();

		try 
		{
	    	if(substr($old_id,0,4)=="INH-"){
	    		// $cnt = $this->inspecth->checkIdHeader($old_id);
				$cnt = $this->checkIdHeader($old_id);
	    		if($cnt == 0){
					// $id = $this->inspecth->CalculateHeader($data, $total);
					$id = $this->CalculateHeader($data, $total);
					$msg = $this->checkErrorSave($id);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(5));
					}
	    		}else{
	    			$id = $old_id;
	    		}
	    	}else{
	    		$id = $old_id;
	    	}

	    	// $list2= $this->inspectd->CalculateDetail($data, $id);
	    	$list2= $this->CalculateDetail($data, $id);
			$msg = $this->checkErrorSave($id);
			if ($msg == 'error'){
				throw new Exception($this->listMessage(6));
			}

	    	if(substr($old_id,0,4)=='INH-'){
	    		if(substr($barcode,0,1) == 'B'){
	    			// $du = $this->inspectd->updateBarcodeqty($barcode, $sisa, 's', 'SPT', $vend_stat);
	    			$du = $this->updateBarcodeqty($barcode, $sisa, 's', 'SPT', $vend_stat);
	    		}else{
					// $du = $this->inspectd->updateOldBarcodeqty($barcode, $sisa, 'S');
					$du = $this->updateOldBarcodeqty($barcode, $sisa, 'S'); 
	    		}
	    	}else{
	    		// $du = $this->inspectd->updateBarcodeqty($barcode, $sisa, 's', 'SPT', $vend_stat);  
				$du = $this->updateBarcodeqty($barcode, $sisa, 's', 'SPT', $vend_stat);
	    	}

	    	$msg = $this->checkErrorSave($du);
			if ($msg == 'error'){
					throw new Exception($this->listMessage(3));
			}

	    	// $ds = $this->inspectd->saveSplitBarcode($list2, $barcode, $id);
			$ds = $this->saveSplitBarcode($list2, $barcode, $id);
	    	$msg = $this->checkErrorSave($ds);
			if ($msg == 'error'){
				throw new Exception($this->listMessage(7));
			}
			
			if ($this->db->trans_status() === FALSE){
				// $this->db->trans_rollback();
				$row2['stat'] ='NO';
				$row2['qtty_sisa'] = $qty_barcode;
				$row2['detail'] = '';
				$row2['message'] = 'Failed, data failed to saved, re-chek again !';
				throw new Exception('DB Error');
			}else{
				$this->db->trans_commit();
				$row2['stat'] = 'OK';
				$row2['qtty_sisa'] = $sisa;
				$row2['detail'] = $list2;
				$row2['message'] = 'Success, data split barcode has been saved !';
			}

			return $row2;
		}catch (Exception $e){
			$this->db->trans_rollback();
			$row2['stat'] ='NO';
			$row2['message'] = $e->getMessage();
			return $row2;
		}
    }

	public function DeletesplitBarc($param){
		$qty_split = 0;
    	$id = '';
    	$dparam=$param;
    	$matname='';
    	$sisa = 0;
    	$cnt_out = 0;
    	$row2 = array();

    	$this->db->trans_begin();

		try 
		{

	    	foreach($dparam as $d){
	    		$barcode = $d['barcode'];
	    		$barc_nama = $d['bar_nama'];
	    		$qty_barcode = $d['qty_barcode'];
	    		$vend_stat = floatval($d['vend_stat']);
	    		$qty_split = $qty_split + $d['qty_split'];
	    		$serial = $d['serial'];
	    		// $cek_out = $this->otspectd->cekBarcodeOut($serial);
	    		$cek_out = $this->cekBarcodeOut($serial);
	    		if($cek_out == 0){
		    		// $dt = $this->inspectd->delete_by_barcode($serial);
		    		$dt = $this->delete_by_barcode($serial);
			    	$msg = $this->checkErrorSave($dt);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(8));
					}
		    		// $ds = $this->inspectds->deleteSplitBarcode($serial);
		    		$ds = $this->inspectds->deleteSplitBarcode($serial);
			    	$msg = $this->checkErrorSave($ds);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(11));
					}
	    		}else{
					$row2['title'] ='Failed !';
					$row2['status'] ='error';
					$row2['message'] = 'Failed, barcode is out !';
					echo json_encode($row2);
					return;
	    		}
	    	}

	    	if($barcode == ''){
	    		throw new Exception($this->listMessage(9));
	    	}
	    	
	    	$sisa = floatval($qty_barcode) + floatval($qty_split);
	    	if($vend_stat == 0){
	    		if(substr($barcode,0,1) == 'B'){
	    			// $du = $this->inspectd->updateBarcodeqty($barcode, $sisa, 'd', 'SPT', $vend_stat);
	    			$du = $this->updateBarcodeqty($barcode, $sisa, 'd', 'SPT', $vend_stat);
	    		}else{
	    			// $du = $this->inspectd->updateOldBarcodeqty($barcode, $sisa, 'S');
					$du = $this->updateOldBarcodeqty($barcode, $sisa, 'S');
	    		}
	    	}else{
				// $du = $this->inspectd->updateBarcodeqty($barcode, $sisa, 'd', 'SPT', $vend_stat);
				$du = $this->updateBarcodeqty($barcode, $sisa, 'd', 'SPT', $vend_stat);
	    	}
	    	
			$msg = $this->checkErrorSave($du);
			if ($msg == 'error'){
				throw new Exception($this->listMessage(3));
			}

			if ($this->db->trans_status() === FALSE){
				throw new Exception('DB Error');
				// $this->db->trans_rollback();
				$row2['title'] ='Failed !';
				$row2['status'] ='error';
				$row2['message'] = 'Failed, data failed to delete, re-chek again !';
			}else{
				$this->db->trans_commit();
				$row2['title'] ='Success !';
				$row2['status'] ='success';
				$row2['qtty_sisa'] = $sisa;
				$row2['message'] = 'Success, data split barcode has been deleted !';
			}
		}catch (Exception $e){
			$this->db->trans_rollback();
			$row2['title'] ='Failed !';
			$row2['status'] ='error';
			$row2['message'] = $e->getMessage();
			
		}

		return $row2;
    }

	public function deleteInBarcode($mParam, $dtl){
    	$dparam=$mParam;
    	$matl = '';
    	$tout = 0;
    	$ok = 0;
    	$proc = '';
    	$lProc = array();
    	$mrtype='';
    	$stat = FALSE;

	    	$this->db->trans_begin();
			try 
			{
		    	// $m= $this->otspectd->delete_detail_out($dparam);
		    	if($dtl['line'] == 0){
		    		$m= $this->delete_by_barcode($dtl['line']);	
		    	}else{
		    		$m= $this->delete_by_barcode_vendor($dtl['line']);
		    	}
				$msg = $this->checkErrorSave($m);
				if ($msg == 'error'){
					throw new Exception($this->listMessage(10));
				}

				if($dtl['line'] == 1){
					$ms = $this->master->SimpansuratJalanVendorDetail($barcode, $pono, $matl, $lineSJ, $lineItem, 0);
					$msg = $this->checkErrorSave($ms);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(15));
					}
				}
				
			    $ttl = $this->getLastSeqnIn($dtl['id']);

			    if($ttl == 0){
			    	$m = $this->delete_header($dtl['id']);
					$msg = $this->checkErrorSave($m);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(12));
					}
			    	$row = array();
			    	$row['total'] = 0;
			    	$row['delAll'] = '1';
			    	$row['status'] = 'success';
			    	$row['title'] = 'Deleted!';
			    	$row['message'] = 'Data has been deleted !';
			    }else{
			    	$rt = $this->setTotalInspectVend($dtl['id']);
					$msg = $this->checkErrorSave($rt);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(14));
					}
					$row = array();
					$row['total'] = $tout;
			    	$row['delAll'] = '0';
			    	$row['status'] = 'success';
			    	$row['title'] = 'Deleted!';
			    	$row['message'] = 'Data has been deleted !';
			    }

				if ($this->db->trans_status() === FALSE || $stat){
					$this->db->trans_rollback();
						$row = array();
						$row['status'] = 'error';
				    	$row['title'] = 'Error!';
				    	$row['message'] = 'Failed, data cannot be deleted, re-check again !';
				}else{
					$this->db->trans_commit();
				}

			}catch (Exception $e){
				$this->db->trans_rollback();
				$row['status'] = 'error';
				$row['title'] = 'Error!';
				$row['message'] = $e->getMessage();
			}

		return $row;
    }

	public function deleteAllDataIn($param){
    	$dparam=$param;
    	$id = '';
    	foreach($dparam as $d){
    		$id = $d['id'];
    	}
	    $this->db->trans_begin();
		try 
			{
		    	$odd = $this->delete_detail($id);
				$msg = $this->checkErrorSave($odd);
				if ($msg == 'error'){
					throw new Exception($this->listMessage(10));
				}
		    	$odh = $this->delete_header($id);
				$msg = $this->checkErrorSave($odh);
				if ($msg == 'error'){
					throw new Exception($this->listMessage(12));
				}

		    	$row = array();
		    	$row['delAll'] = '1';
		    	$row['status'] = 'success';
				$row['title'] = 'Deleted!';
				$row['message'] = 'Data berhasil dihapus';

				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
						$row = array();
						$row['status'] = 'error';
				    	$row['title'] = 'Error!';
				    	$row['message'] = 'Failed, data cannot be deleted, re-check again !';
				    	echo json_encode($row);
				}else{
					$this->db->trans_commit();
		    	}
			}catch (Exception $e){
				$this->db->trans_rollback();
				$row['status'] = 'error';
				$row['title'] = 'Error!';
				$row['message'] = $e->getMessage();
			}
    	return $row;
    }

	public function deleteOutBarcode($mParam, $dtl){
    	$dparam=$mParam;
    	$matl = '';
    	$tout = 0;
    	$ok = 0;
    	$proc = '';
    	$lProc = array();
    	$mrtype='';
    	$stat = FALSE;
		$ck1 = 0;
		$ck2 = 0;

    	array_push($lProc,'KT');
    	array_push($lProc,'RJ');
		array_push($lProc,'TM');
	    	$this->db->trans_begin();
			try 
			{
		    	// $m= $this->otspectd->delete_detail_out($dparam);
		    	$m= $this->delete_detail_out($dparam);
				$msg = $this->checkErrorSave($m);
				if ($msg == 'error'){
					throw new Exception($this->listMessage(10));
				}
				// $list = $this->inspectd->detailBarcode($dtl['line'], $dtl['vend_stat']);
				$list = $this->detailBarcode($dtl['line'], $dtl['vend_stat']);
				foreach($list as $lst){
					if(floatval($dtl['vend_stat']) == 0){
						$ok = 1;
						if(substr($dtl['line'],0,1) != 'B'){
							// $du =$this->inspectd->updateOldBarcodeqty($dtl['line'], $lst->INSD_IQTY, 'D');
							$du =$this->updateOldBarcodeqty($dtl['line'], $lst->INSD_IQTY, 'D');
						}else{
							// $du = $this->inspectd->updateBarcodeqty($lst->INSD_LINE, $lst->INSD_IQTY, 'd', $dtl['proc'], $dtl['vend_stat']);
							$du = $this->updateBarcodeqty($lst->INSD_LINE, $lst->INSD_IQTY, 'd', $dtl['proc'], $dtl['vend_stat']);
						}
					}else{
						// $du = $this->inspectd->updateBarcodeqty($lst->INSD_LINE, $lst->INSD_IQTY, 'd', $dtl['proc'], $dtl['vend_stat']);
						$du = $this->updateBarcodeqty($lst->INSD_LINE, $lst->INSD_IQTY, 'd', $dtl['proc'], $dtl['vend_stat']);
					}

			    	$msg = $this->checkErrorSave($du);
					if ($msg == 'error'){
							throw new Exception($this->listMessage(3));
					}
				}

			    // $ttl = $this->otspectd->getLastSeqnOut($dtl['id']);
			    $ttl = $this->getLastSeqnOut($dtl['id']);
				$tout = $this->getTotalOutmalt($dtl['mrno'],$dtl['matl']);


				if($tout == 0){

				}else{
					$mrtype = substr($dtl['mrno'],0,2);
					if (!in_array($mrtype, $lProc)) {
						if($dtl['wh'] == 'MAT'){
							$ck1 = $this->cekTranshErp($dtl['mrno']);
							$th = $this->saveTranshErp($dparam, $dtl['mrno'], $list, $ck1, $tout);
							$msg = $this->checkErrorSave($th);
							if ($msg == 'error'){
								throw new Exception($this->listMessage(4));
							}
						}else{
							foreach ($mParam as $ls) {
							$l0 = $this->getHeaderDataMO($dtl['mrno']);
							$dlist = array();
								foreach($l0 as $val){
									$r = array();
									$r['tgl'] =$ls['tgl'];
									$r['dept'] =$val->MRQH_OPCD;
									$r['user'] =$ls['user'];
									$r['user2'] =$val->MRQH_EMPL;
									$r['comp'] ='S004';
									$r['rmks'] =$val->MRQH_TITLE;
									$r['mrno'] =$dtl['mrno'];
									$r['fact'] =$val->MRQH_DEST;
									$dlist[]= $r;

								}
							}
							$mrno3 = $this->saveReqxxhErp($dlist, $dtl['mrold'], $list, $dtl['tanggal']);
							$msg = $this->checkErrorSave($mrno3);
							if ($msg == 'error'){
								throw new Exception($this->listMessage(4));
							}
						}

					}
				}
			    if($ttl == 0){
			    	$m = $this->delete_H_all($dtl['id']);
					$msg = $this->checkErrorSave($m);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(12));
					}
			    	$mrtype = substr($dtl['mrno'],0,2);
					if (!in_array($mrtype, $lProc)) {
			    		if($dtl['wh'] == 'MAT'){
							$ck1 = $this->cekTranshErp($dtl['mrno']);
							$ck2 = $this->cekTransdErp($dtl['mrno'], $dtl['matl']);
							if($ck1 != 0 and $ck2 != 0){
								$dth = $this->deleteTransdBymr($dtl['mrno']);
								$dtd = $this->deleteTranshBymr($dtl['mrno']);
							}else{
								$dth = '';
								$dtd = '';
							}
				    	}else{

				    		if(substr($dtl['matl'],0,4) == 'X076' || substr($dtl['matl'],0,1) !== 'X'){
					    		$ck1 = $this->cekTranshErp($dtl['mrno']);
								$ck2 = $this->cekTransdErp($dtl['mrno'], $dtl['matl']);
								if($ck1 != 0 and $ck2 != 0){
									$dth = $this->deleteTransdBymr($dtl['mrno']);
									$dtd = $this->deleteTranshBymr($dtl['mrno']);
								}else{
									$dth = '';
									$dtd = '';
								}
				    		}else{
					    		$stat = $this->cekReqxxdErp($dtl['mrno'], $dtl['matl']);
					    		if($stat >= 0){
						    		$dth = $this->deleteREQDBymr($dtl['mrno2']);
							    	$dtd = $this->deleteREQHBymr($dtl['mrno2']);
						    	}				    			
				    		}
				    	}

						    $msg = $this->checkErrorSave($dth);
							if ($msg == 'error'){
								$stat = TRUE;
								$this->db->trans_rollback();
								throw new Exception($this->listMessage(13));
							}

						    $msg = $this->checkErrorSave($dtd);
							if ($msg == 'error'){
								$stat = TRUE;
								$this->db->trans_rollback();
								throw new Exception($this->listMessage(13));
							}
			    	}
			    	$row = array();
			    	$row['total'] = 0;
			    	$row['delAll'] = '1';
			    	$row['status'] = 'success';
			    	$row['title'] = 'Deleted!';
			    	$row['message'] = 'Data berhasil dihapus';
			    }else{
			    	$rt = $this->updateTtlOut2($dtl['id'], $tout);
					$msg = $this->checkErrorSave($rt);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(14));
					}
					$row = array();
					$row['total'] = $tout;
			    	$row['delAll'] = '0';
			    	$row['status'] = 'success';
			    	$row['title'] = 'Deleted!';
			    	$row['message'] = 'Data berhasil dihapus';
			    }

				if ($this->db->trans_status() === FALSE || $stat){
					$this->db->trans_rollback();
						$row = array();
						$row['status'] = 'error';
				    	$row['title'] = 'Error!';
				    	$row['message'] = 'Failed, data cannot be deleted, re-check again !';
				}else{
					$this->db->trans_commit();
				}

			}catch (Exception $e){
				$this->db->trans_rollback();
				$row['status'] = 'error';
				$row['title'] = 'Error!';
				$row['message'] = $e->getMessage();
			}

		return $row;
    }

    public function deleteAllDataOut($param){
    	$dparam=$param;
    	$id = '';
    	foreach($dparam as $d){
    		$id = $d['id'];
    	}
    	
    	$tgl = $this->otspecth->getTanggalMrOut('', $id);
    	$close = $this->master->cekClosingBydate($tgl,'MO');
    	$closeErp = $this->master->cekClosingErp($tgl,'MR');
    	if($close == 0 and $closeErp == 0){
    	// if($close == 0){
	    	$this->db->trans_begin();
			try 
			{
		    	$odd = $this->otspectd->delete_D_all($id);
				$msg = $this->checkErrorSave($odd);
				if ($msg == 'error'){
					throw new Exception($this->listMessage(10));
				}
		    	$odh = $this->otspecth->delete_H_all($id);
				$msg = $this->checkErrorSave($odh);
				if ($msg == 'error'){
					throw new Exception($this->listMessage(12));
				}

		    	$row = array();
		    	$row['delAll'] = '1';
		    	$row['status'] = 'success';
				$row['title'] = 'Deleted!';
				$row['message'] = 'Data berhasil dihapus';

				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
						$row = array();
						$row['status'] = 'error';
				    	$row['title'] = 'Error!';
				    	$row['message'] = 'Failed, data cannot be deleted, re-check again !';
				    	echo json_encode($row);
				}else{
					$this->db->trans_commit();
		    	}
			}catch (Exception $e){
				$this->db->trans_rollback();
				$row['status'] = 'error';
				$row['title'] = 'Error!';
				$row['message'] = $e->getMessage();
			}
    	}else{
		   $row['status'] = 'error';
		   $row['title'] = 'Error!';
		   $row['message'] = 'Tidak bisa hapus, tanggal MR sudah close';
    	}

    	return $row;
    }

	public function scanBarcodeVendor($data, $data2, $detil)
    {
    	// $mParam = $param;
    	$pono = $detil['pono'];
    	$wh = $detil['wh'];
    	// $wh = '';
		$matl = $detil['matl'];
		$seqn = $detil['seqn'];
		$lineSJ = $detil['lineSJ'];
		$lineItem = $detil['lineItem'];
		$Ttlqtty = $detil['Ttlqtty'];
		$tqty = $detil['tqty'];
		$barcode = $detil['barcode'];
		$row = array();
 
		$this->db->trans_begin();
		try {
				// $l = $this->inspectd->cekBarcodeVendorExist($barcode);
				$l = $this->cekBarcodeVendorExist($barcode);
				if($l->CNT == 0){
				// $id = $this->inspecth->CalculateHeader($data, $Ttlqtty); 
					$id = $this->CalculateHeader($data, $Ttlqtty);
					$msg = $this->checkErrorSave($id);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(5));
					}
				    // $list= $this->inspectd->CalculateDetail($data, $id);
					$list= $this->CalculateDetail($data, $id);
					$msg = $this->checkErrorSave($id);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(6));
					}
				    $tqty = $this->setTotalInspectVend($id);
					$msg = $this->checkErrorSave($tqty);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(14));
					}
				    $ms = $this->master->SimpansuratJalanVendorDetail($barcode, $pono, $matl, $lineSJ, $lineItem, 1);
					$msg = $this->checkErrorSave($ms);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(15));
					}
				    $mss = $this->updateInspPO($pono, $matl, $seqn, $wh, $data2);
					$msg = $this->checkErrorSave($mss);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(16));
					}
				    // $this->updateInspPO($pono, $matl, $seqn, $wh, $data2);

			    }else{
					$row['status'] = 'error';
					$row['message'] = 'Failed, Barcode duplicate !';
			    }

			    if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$row['status'] = 'error';
					$row['message'] = 'Failed, Barcode error while saving !';
				}else{
					$this->db->trans_commit();
				    $row['id'] = $id;
				    $row['dt'] = $data;
				    $row['wh'] = $wh;
					$row['total'] = $tqty;
					$row['detail'] = $list;
					$row['data'] = $data2;
					$row['status'] = 'success';
					$row['message'] = 'Success, the data has been saved !';
				}
		}catch (Exception $e){
				$this->db->trans_rollback();
				$row['status'] = 'error';
				$row['message'] = $e->getMessage();
		}

		return $row;
    }

    public function setTotalInspectVend($id){
    $total = 0;
    	// $det= $this->inspectd->getTotalQttyVend($id);
    	$det= $this->getTotalQttyVend($id);
        foreach($det as $dt){
        	$total = $dt->qtty;
        	$param= array(
			            'INSH_IDXX'=>$id
			        );
        	$details= array(
			            'INSH_TOTAL'=>floatval($dt->qtty)
			        );
        	// $head = $this->inspecth->updateTotalHeader($param, $details);
        	$head = $this->updateTotalHeader($param, $details);
			$msg = $this->checkErrorSave($head);
			if ($msg == 'error'){
				return $msg;
			}
        }
        return $total;
    }

    function updateInspPO($pono, $matl, $seqn, $wh, $list){
    	// $totalBarcode = $this->inspectd->TotalBarcodeInspect($pono, $matl, $seqn);
    	if(substr($matl, 0,1) == 'M' || substr($matl, 0,1) == 'W' || substr($matl, 0,1) == 'T'){
    		$wh = 'ENG';
    	}
    	$totalBarcode = $this->TotalBarcodeInspect($pono, $matl, $seqn);
		    foreach ($totalBarcode as $tb) {
		    	$msg = $this->master->updateInspectPO($list, $wh, $tb->QTTY, $pono, $matl, $seqn);
		    }
		return $msg;
    }

	public function createBarcodeMatl($detil, $param, $dt)
    {

		$barcode = '';

			$this->db->trans_begin();
			try 
			{
				// $lst_barc_act = $this->inspectd->cekTotalBarcode($transno);
				$lst_barc_act = $this->cekTotalBarcode($detil['tranno']);
			    if($lst_barc_act->CNT == 0){
			    	// $id = $this->inspecth->CalculateHeader($mParam, $Ttlqtty);
			    	$id = $this->CalculateHeader($param, $detil['Ttlqtty']);
					$msg = $this->checkErrorSave($id);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(5));
					}

			    	// $list= $this->inspectd->CalculateDetail($mParam, $id, '');
			    	$list= $this->CalculateDetail($param, $id, '');
					$msg = $this->checkErrorSave($list);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(6));
					}
			    	$mss = $this->updateInspPO($detil['pono'], $detil['matl'], $detil['seqn'], $detil['wh'], $list);
					$msg = $this->checkErrorSave($mss);
					if ($msg == 'error'){
						throw new Exception($this->listMessage(16));
					}
			    	$messages = 'Success, data has been saved !';
			    }else{
			    	if($detil['cnt_line'] < $lst_barc_act->CNT){
				    	$lst_barc = $this->cekPrintStat($detil['tranno'], $dt);
					    	if(count($lst_barc) != 0){
					    		foreach($lst_barc as $lb){
					    			$dt = $this->inspectd->delete_by_barcode($lb['barcode']);
							    	$msg = $this->checkErrorSave($dt);
									if ($msg == 'error'){
										throw new Exception($this->listMessage(8));
									}
					    			$code = $lb['barcode'];
					    			$barcode = $barcode . ", " . $code;
					    		}
					    	$delD = $this->inspectd->delete_detail($detil['tranno']);
							$msg = $this->checkErrorSave($delD);
							if ($msg == 'error'){
								throw new Exception($this->listMessage(18));
							}
							if($Ttlqtty == 0){
								$delH = $this->inspecth->delete_header($l->transno);
								$msg = $this->checkErrorSave($delH);
								if ($msg == 'error'){
									throw new Exception($this->listMessage(17));
								}
							}
							    // $messages = 'Success, data with barcode'. ltrim($barcode, ',') .' has been deleted';
							    $messages = 'Success, data barcode has been deleted';
					    	}else{
					    		$messages = 'Failed, barcode has been printed';
					    	}
			    	}else{
			    		// $id = $this->inspecth->CalculateHeader($param, $detil['Ttlqtty']);
			    		$id = $this->CalculateHeader($param, $detil['Ttlqtty']);
						$msg = $this->checkErrorSave($id);
						if ($msg == 'error'){
							throw new Exception($this->listMessage(5));
						}
						// $list= $this->inspectd->CalculateDetail($param, $id, '');
						$list= $this->CalculateDetail($param, $id, '');
						$msg = $this->checkErrorSave($list);
						if ($msg == 'error'){
							throw new Exception($this->listMessage(6));
						}
						$mss = $this->updateInspPO($detil['pono'], $detil['matl'], $detil['seqn'], $detil['wh'], $list);
						$msg = $this->checkErrorSave($mss);
						if ($msg == 'error'){
							throw new Exception($this->listMessage(16));
						}

						$messages = 'Success, data has been saved !';
			    	}

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
			}catch (Exception $e){
				$this->db->trans_rollback();
				$row['id'] = '';
				$row['detail'] = '';
				$row['stat'] = 'N';
				$row['message'] = $e->getMessage();
			}

		return $row;
    }

    public function checkErrorSave($p){
		$val = '';
		if(!is_numeric($p)){
			$val = $p;
		}else{
			$val = chr($p);
		}
		return $val;
	}

    public function getTotalOutmalt($mrno, $matl){
    	$tout = 0;
		$list3 = $this->otspectd->totalOutbyMatl($mrno,$matl);
		foreach($list3 as $lst3){
			    	$tout = $lst3->OQTY;
			    }
		return $tout;		    
    }


}

/* End of file Transaction_Model.php */
/* Location: ./application/models/Transaction_Model.php */