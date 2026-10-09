<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Inspectionh_Model.php");

class Inspectiond_model extends Inspectionh_Model {

	// var $table1 =  'PRTMMRP.PRTM.TM_MI_INSPECTD';
	// var $table2 =  'PRTMMRP.PRTM.TM_MI_INSPECTH';

	public function __construct()
	{
		parent::__construct();
	}

	public function loadDataBarcode($tipe, $line){
		$sql = "select A0.*
				FROM (
						SELECT A.INSD_IDXX COLLATE Korean_Wansung_CI_AS headerID, A.INSD_LINE line, A.INSD_CODE COLLATE Korean_Wansung_CI_AS headerMatCode, 
							   A.INSD_PONO COLLATE Korean_Wansung_CI_AS headerPONumber, A.INSD_SEQN headerSEQN, A.INSD_UNIT COLLATE Korean_Wansung_CI_AS unit, 
							   A.INSD_IQTY QtyAkhir, B.INSH_DATE tanggal, B.INSH_VEND COLLATE Korean_Wansung_CI_AS vendorid,
							   COALESCE(C.POMH_FACT,D.POMH_FACT) POMH_FACT, COALESCE(C.POMH_PART,D.POMH_PART) POMH_PART, COALESCE(C.POMH_POID,D.POMH_POID) POMH_POID
						FROM ".$this->inspectd2." A
						LEFT JOIN ".$this->inspecth2." B ON A.INSD_IDXX = B.INSH_IDXX
						LEFT JOIN ".$this->pomxxh." C ON C.POMH_PONO = A.INSD_PONO
						LEFT JOIN ".$this->pomexh." D ON D.POMH_PONO = A.INSD_PONO
						WHERE A.INSD_LINE != ''";
						if ($tipe == 'line'){
							$sql.="AND A.INSD_LINE = '". $line ."'";
						}
						if ($tipe == 'head'){
							$sql.="AND A.INSD_IDXX = '". $line ."'";
						}
		$sql.="			UNION ALL
						select A.headerID, CONVERT(VARCHAR(255),A.line) line, A.headerMatCode, A.headerPONumber, A.headerSEQN, A.unit, A.QtyAkhir, B.tanggal, 
						B.vendorid, COALESCE(C.POMH_FACT,G.POMH_FACT) POMH_FACT, COALESCE(C.POMH_PART,G.POMH_PART) POMH_PART, 
						COALESCE(C.POMH_POID,G.POMH_POID) POMH_POID
						FROM ".$this->inspectd." A
						LEFT JOIN ".$this->inspecth." B ON A.headerID = B.Id
						LEFT JOIN ".$this->pomxxh." C ON C.POMH_PONO = A.headerPONumber
		                LEFT JOIN ".$this->pomexh." G ON G.POMH_PONO = A.headerPONumber
						WHERE line != ''";
						if ($tipe == 'line'){
							$sql.="AND CONVERT(VARCHAR(255),A.line) = '". $line ."'";
						}
						if ($tipe == 'head'){
							$sql.="AND A.headerID = '". $line ."'";
						}
		$sql.= ") A0
				ORDER BY A0.POMH_PART, A0.vendorid";
		return $this->db->query($sql)->result();
	}

	public function getBarcodeline(){

		$sql = "select CONVERT(VARCHAR(10),GETDATE(), 112) bln, COUNT(INSD_LINE) NOMER
				FROM ". $this->inspectd2 ." WHERE LEFT(LTRIM((REPLACE(INSD_LINE,'B',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)";

		// $sql = "select TOP 1 CONVERT(VARCHAR(10),GETDATE(), 112) bln, SUBSTRING(INSD_LINE,10,9) NOMER
		// 		FROM ". $this->inspectd2 ." WHERE LEFT(LTRIM((REPLACE(INSD_LINE,'B',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)
		// 		ORDER BY INSD_LINE ASC";

		return $this->db->query($sql)->result();
	}

	public function getBarcodeLineNew(){
	$this->db->trans_start();
	$nomor=0;
	$barcode='';

		$sql =" select TOP 1 CONVERT(VARCHAR(10),periode, 112) periode, prefix, barcode
				FROM ".$this->seriBarcode."
				WHERE periode = CONVERT(VARCHAR(11),GETDATE(), 111)
				ORDER BY barcode DESC";
		$dt = $this->db->query($sql)->result();

		if(count($dt) != 0){
			foreach($dt as $d){
			$nomor=floatval($d->barcode) + 1;
			$data1 = array(
						     'periode'=>date("Y-m-d"),
						     'prefix'=>'B',
						     'barcode'=>floatval($nomor)
						     );
			$this->db->set($data1);
			$this->db->insert($this->seriBarcode);
			$barcode = $d->prefix.$d->periode.$nomor;
			}
		}else{
			$nomor = floatval($nomor) + 1;
			$data1 = array(
						     'periode'=>date("Y-m-d"),
						     'prefix'=>'B',
						     'barcode'=>floatval($nomor)
						     );
			$this->db->set($data1);
			$this->db->insert($this->seriBarcode);
			$barcode = 'B'.date("Ymd").$nomor;
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
	    	return $barcode;
		}

	}

	public function getDetailBarcVend($pono, $tgl1, $tgl2, $vend, $line, $wh){

		$sql = "select A.INSD_LINE, A.INSD_LIN2, A.INSD_CODE, A.INSD_NAME, A.INSD_UNIT, A.INSD_BQTY, A.INSD_PONO, A.INSD_VEND
				FROM ".$this->inspectd2." A
				LEFT JOIN ".$this->inspecth2." B ON A.INSD_IDXX = B.INSH_IDXX
				WHERE B.INSH_VSAT = '1'";
				if ($pono != ''){
					$sql.="AND A.INSD_PONO LIKE '". $pono ."%'";
				}
				if ($tgl1 != '' && $tgl2 != ''){
					$sql.="AND B.INSH_DATE BETWEEN '". $tgl1 ."' AND '". $tgl2 ."'";
				}
				if ($vend != ''){
					$sql.="AND A.INSD_VEND LIKE '". $vend ."%'";
				}
				if ($line != ''){
					$sql.="AND A.INSD_LINE LIKE '". $line ."%'";
				}
				if ($wh != ''){
					$sql.="AND B.INSH_WHID LIKE '". $wh ."%'";
				}

		return $this->db->query($sql)->result();
	}

	private function tableHasColumn($tableName, $columnName)
	{
		if (empty($tableName) || empty($columnName)) {
			return false;
		}
		try {
			$fields = $this->db->field_data($tableName);
			if (empty($fields)) {
				return false;
			}
			foreach ($fields as $field) {
				if (strtoupper($field->name) == strtoupper($columnName)) {
					return true;
				}
			}
		} catch (Exception $e) {
			return false;
		}
		return false;
	}

	public function CalculateDetail($data, $id){
		$lineItem = 0;
		$release ='';
		$style ='';
		$vend ='';
		$sjno ='';
		$lotField = '';
		$expField = '';

		$detailLotCandidates = array('INSD_LOTNUMBER', 'INSD_LOTX', 'INSD_LOT_NO', 'LOTNUMBER', 'LOT_NO');
		$detailExpCandidates = array('INSD_EXP_DATE', 'INSD_EXPIRED_DATE', 'INSD_EXPIRE_DATE', 'EXP_DATE', 'EXPIRED_DATE');
		foreach ($detailLotCandidates as $candidate) {
			if ($this->tableHasColumn($this->inspectd2, $candidate)) {
				$lotField = $candidate;
				break;
			}
		}
		foreach ($detailExpCandidates as $candidate) {
			if ($this->tableHasColumn($this->inspectd2, $candidate)) {
				$expField = $candidate;
				break;
			}
		}

		$details2 = array();
		$data2 = array();

		// $param = array();
		// $this->delete_by_barcode($id);
	    // if(count($lst_barc) != 0){
	    // 	foreach($lst_barc as $lb){
	    // 		$this->inspectd->delete_by_barcode($lb['barcode']);
	    // 	}
	    // }
	    // $this->db->trans_start();
		// $bc = $this->getBarcodeline();
			// foreach ($bc as $value) {
			// 	$urut = $value->NOMER;
			// }
	    foreach ($data as $lst) {
	    	if(floatval($lst['qtty']) < 0 && substr($lst['mino'], 0,2) != 'RJ'){
	    		return;
	    	}

	    	if(substr($lst['serial'], 0, 1) !== 'B' || $lst['serial'] == ''){
			    $bc = $this->getBarcodeLineNew();
			    if($bc == false){
			    	return;
			    }
		    	$serial = '';
		    	// $urut = $urut + 1;
		    	// $line = 'B'. $value->bln . $urut;
		    	$line = $bc;
		    	$seri = $lst['serial'];
		    	if($seri != '' || $seri = 0){
		    		$line2 = $lst['serial'];
		    		// $serial = $lst['serial'];
		    	}else{
		    		$line2 = 0;
		    	}
	    	// if($serial == ''){
	    		// if($lst['vstat']==0){
			    //     $release ='';
			    //     $style ='';
			    //     $vend ='';
			    //     $sjno ='';
			    //     $lineItem = '';
			    // }else{
			        $release =$lst['release'];
			        $style =$lst['style'];
			        $vend =$lst['vend'];
			        $sjno =$lst['sjno'];
			        $lineItem =$lst['lineItem'];
			    // }
			    if(substr($lst['mino'], 0,2) == 'RJ'){
			    	$qtty = floatval(0);
			    	$qtty_rj = floatval($lst['qtty']) * -1;
			    }else{
					$qtty = floatval($lst['qtty']);
					$qtty_rj = floatval(0);
			    }
		        $lotValue = isset($lst['lotnumber']) ? $lst['lotnumber'] : '';
		        $expiredValue = isset($lst['tgl_expired']) ? $lst['tgl_expired'] : '';
		        $data1 = array(
					     'INSD_IDXX'=>$id,
					     'INSD_LINE'=>$line,
					     'INSD_LIN2'=>$line2,
					     'INSD_PONO'=>$lst['pono'],
					     'INSD_MINO'=>$lst['mino'],
					     'INSD_SEQN'=>floatval($lst['seqn']),
					     'INSD_SEQH'=>floatval($lst['seqh']),
					     'INSD_CODE'=>$lst['matcode'],
					     'INSD_NAME'=>$lst['matname'],
					     'INSD_UNIT'=>$lst['matunit'],
					     'INSD_KEMAS'=>$lst['kem'],
					     'INSD_TKEMAS'=>$lst['jmlkem'],
					     'INSD_IQTY'=>floatval($lst['qtty']),
					     'INSD_OQTY'=>0,
					     'INSD_RQTY'=>floatval($qtty_rj),
					     'INSD_BQTY'=>floatval($qtty),
					     'INSD_PRNT'=>0,
						 'INSD_CKQC'=>0,
						 'INSD_PRIC'=>floatval($lst['price']),
						 'INSD_UMCD'=>$lst['umcd'],
					     'INSD_PART'=>$style,
					     'INSD_NBRN'=>$release,
					     'INSD_SJNO'=>$sjno,
					     'INSD_VEND'=>$vend,
					     'INSD_LVND'=>$lineItem
					     );
				if($lotField != ''){
					$data1[$lotField] = $lotValue;
				}
				if($expField != ''){
					$data1[$expField] = $expiredValue;
				}

				if (array_key_exists('INSD_LOTNUMBER', $data1) && $data1['INSD_LOTNUMBER'] === '') {
					unset($data1['INSD_LOTNUMBER']);
				}
				if (array_key_exists('INSD_EXP_DATE', $data1) && $data1['INSD_EXP_DATE'] === '') {
					unset($data1['INSD_EXP_DATE']);
				}

		       	$this->db->set($data1);
				$insert = $this->db->insert($this->inspectd2);

                if ($this->db->trans_status() === FALSE) {
                    $msg = 'error';
					return $msg;
                }
				
				$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
				    return $msg;
				}

		        array_push($details2,array(
		            'INSD_LINE'=>$line,
		            'INSD_LIN2'=>$line2,
		            'INSD_CODE'=>$lst['matcode'],
		            'INSD_NAME'=>$lst['matname'],
		            'INSD_PONO'=>$lst['pono'],
		            'INSD_VEND'=>$lst['vend'],
		            'INSD_KEMAS'=>$lst['kem'],
		            'INSD_TKEMAS'=>$lst['jmlkem'],
		            'INSD_IQTY'=>$lst['qtty'],
	            'lotnumber' => $lotValue,
	            'tgl_expired' => $expiredValue,
	            'ACTION' => '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_matl(\''.$line.'\', this)"><i class="fas fa-trash"></i></a>',
		            'SEQH'=>floatval($lst['seqh']),
		            'SEQN'=>floatval($lst['seqn'])
		        ));
	    	}
		}
	    return $details2;
	}

	public function delete_detail($id)
	{

		$this->db->where('INSD_IDXX', $id);
		$this->db->delete($this->inspectd2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function delete_by_barcode($id)
	{

		$this->db->where('INSD_LINE', $id);
		$this->db->delete($this->inspectd2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';	
		}else{
			$msg = 'ok';
		}

		return $msg;
	}

	public function delete_by_barcode_vendor($id)
	{

		$this->db->where('INSD_LIN2', $id);
		$this->db->delete($this->inspectd2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';	
		}else{
			$msg = 'ok';
		}

		return $msg;
	}

	public function getTotalQttyVend($id){
		$sql = "select SUM(INSD_IQTY) qtty
				FROM ".$this->inspectd2."
				WHERE INSD_IDXX = '". $id ."'";

		return $this->db->query($sql)->result();
	}

	public function getTotalQttyMI($id, $code){
	$ttl = 0;
		$sql = "select SUM(INSD_IQTY) qtty
				FROM ".$this->inspectd2."
				WHERE INSD_MINO = '". $id ."'
				AND   INSD_CODE = '". $code ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$ttl = $d->qtty;	
		}
		return $ttl;
	}

	public function getTotalQttyBarcode($id){
	$ttl = 0;
		$sql = "select SUM(INSD_IQTY) qtty
				FROM ".$this->inspectd2."
				WHERE INSD_IDXX = '". $id ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$ttl = $d->qtty;	
		}
		return $ttl;
	}

	public function loadSqnInmatl($id){
		$sql="select COUNT(*) SEQN
			  FROM ".$this->inspectd2."
			  WHERE INSD_IDXX = '".$id."'";
		return $this->db->query($sql)->result();
	}

	public function getLastSeqnIn($id){
		$dtl = $this->loadSqnInmatl($id);
		foreach($dtl as $dt){
			$seqn = $dt->SEQN;
		}
		return $seqn;
	}

	public function cekOutbyIdxx($id){
	$ttl = 0;
		$sql = "select count(*) CNT
				FROM ".$this->inspectd2."
				WHERE INSD_IDXX = '". $id ."'
				AND INSD_OQTY != 0";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$ttl = $d->CNT;	
		}
		return $ttl;
	}

	public function detailBarcode($id, $vend_stat = 0, $fact = ''){
		if(floatval($vend_stat) == 0){
			if(substr($id,0,1) == 'B'){
				$sql = "select INSD_LINE, INSD_CODE, INSD_NAME, INSD_UNIT, INSD_BQTY, INSD_IQTY
						FROM ".$this->inspectd2." A
						LEFT JOIN ".$this->pomxxh." B ON A.INSD_PONO = B.POMH_PONO
						LEFT JOIN ".$this->pomexh." C ON A.INSD_PONO = C.POMH_PONO
						WHERE INSD_LINE = '". $id ."'
						AND COALESCE(B.POMH_FACT, C.POMH_FACT) LIKE '". $fact ."%'";
			}else{
				$sql=  "select CONVERT(VARCHAR(255),line) INSD_LINE, headerMatCode INSD_CODE, '' INSD_NAME, unit COLLATE Korean_Wansung_CI_AS INSD_UNIT, QtyAkhir INSD_BQTY, qtty INSD_IQTY
						FROM ".$this->inspectd." A
						LEFT JOIN ".$this->pomxxh." B ON A.headerPONumber = B.POMH_PONO
						LEFT JOIN ".$this->pomexh." C ON A.headerPONumber = C.POMH_PONO
						WHERE CONVERT(VARCHAR(255),line) = '". $id ."'
						AND COALESCE(B.POMH_FACT, C.POMH_FACT) LIKE '". $fact ."%'";
			}
		}else{
			if(substr($id,0,1) == 'B'){
				$sql = "select INSD_LINE, INSD_CODE, INSD_NAME, INSD_UNIT, INSD_BQTY, INSD_IQTY
						FROM ".$this->inspectd2." A
						LEFT JOIN ".$this->pomxxh." B ON A.INSD_PONO = B.POMH_PONO
						LEFT JOIN ".$this->pomexh." C ON A.INSD_PONO = C.POMH_PONO
						WHERE INSD_LINE = '". $id ."'
						AND COALESCE(B.POMH_FACT, C.POMH_FACT) LIKE'". $fact ."%'";
			}else{
				$sql = "select INSD_LIN2 INSD_LINE, INSD_CODE, INSD_NAME, INSD_UNIT, INSD_BQTY, INSD_IQTY
						FROM ".$this->inspectd2." A
						LEFT JOIN ".$this->pomxxh." B ON A.INSD_PONO = B.POMH_PONO
						LEFT JOIN ".$this->pomexh." C ON A.INSD_PONO = C.POMH_PONO
						WHERE INSD_LIN2 = '". $id ."'
						AND COALESCE(B.POMH_FACT, C.POMH_FACT) LIKE'". $fact ."%'";
			}
		}
		return $this->db->query($sql)->result();
	}

	public function updateBarcodeqty($line,$qty, $stat, $proc, $vend_stat = 0){
		$qtty = floatval($qty);
		if($stat != 'd'){
			if($proc == 'RJT'){
				$details= array('INSD_BQTY'=>floatval(0),
						        'INSD_RQTY'=>floatval($qtty)
						        );

			}elseif($proc == 'SPT'){
			$details= array('INSD_IQTY'=>floatval($qtty),
				        	'INSD_BQTY'=>floatval($qtty)
				        	);
			}else{
				if($vend_stat == 0){
					if(substr($line,0,1) != 'B'){
					$details= array('QtyAkhir'=>floatval(0),
							        'QtyOut'=>floatval($qtty)
							        );
					}else{
					$details= array('INSD_BQTY'=>floatval(0),
						        'INSD_OQTY'=>floatval($qtty)
						        );
					}
				}else{
				$details= array('INSD_BQTY'=>floatval(0),
						        'INSD_OQTY'=>floatval($qtty)
						        );
				}
			}
		}else{
			if($proc == 'RJT'){
			$details= array('INSD_BQTY'=>floatval($qtty),
				        	'INSD_RQTY'=>floatval(0)
				        	);
			}elseif($proc == 'SPT'){
			$details= array('INSD_IQTY'=>floatval($qtty),
				        	'INSD_BQTY'=>floatval($qtty)
				        	);
			}else{
			$details= array('INSD_BQTY'=>floatval($qtty),
				        	'INSD_OQTY'=>floatval(0)
				        	);
			}
		}
		if($vend_stat == 0){
			if(substr($line,0,1) != 'B'){
				$param= array('line'=>$line);
				$up = $this->updateQtyOutOld($param, $details);
			}else{
				$param= array('INSD_LINE'=>$line);
				$up = $this->updateQtyOut($param, $details);
			}
		}else{
			if(substr($line,0,1) != 'B'){
				$param= array('INSD_LIN2'=>$line);
			}else{
				$param= array('INSD_LINE'=>$line);
			}
			$up = $this->updateQtyOut($param, $details);
		}

		return $up ;
	}

	public function updateQtyOut($param, $details){
		$this->db->where($param);
		$update = $this->db->update($this->inspectd2, $details);

        if ($this->db->trans_status() === FALSE) {
            $msg = 'error';
			return $msg;
        }

		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function updateQtyOutOld($param, $details){
		$this->db->where($param);
		$this->db->update($this->inspectd, $details);

	    if ($this->db->trans_status() === FALSE) {
	        $msg = 'error';
			return $msg;
	    }

		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function updateOldBarcodeqty($line,$qtty,$stat){
		if($stat == 'S'){
			$details= array(
				        	'Qtty'=>floatval($qtty),
				        	'QtyAkhir'=>floatval($qtty)
				        	);

			$param= array(
				           'line'=>$line
				     	 );
		}elseif($stat == 'O'){
			$sisa = $qtty - $qtty;
			$details= array(
				        	'QtyAkhir'=>floatval($sisa),
				        	'QtyOut'=>floatval($qtty)
				        	);

			$param= array(
				           'line'=>$line
				     	 );
		}elseif($stat == 'D'){
			$details= array(
				        	'QtyOut'=>floatval(0),
				        	'QtyAkhir'=>floatval($qtty)
				        	);

			$param= array(
				           'line'=>$line
				     	 );
		}
	// $qtty = floatval($qty);

		$this->db->where($param);
		$this->db->update($this->inspectd, $details);

	    if ($this->db->trans_status() === FALSE) {
	        $msg = 'error';
			return $msg;
	    }

		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function getDetailBarcodeSplit($line, $vstat){
		if($vstat == 0){
			if(substr($line,0,1) == 'B'){
				$sql = "select A.INSH_IDXX, A.INSH_DATE, A.INSH_SJNO, A.INSH_WHID, A.INSH_VSAT, B.INSD_PONO, B.INSD_SEQN, B.INSD_LVND, B.INSD_CODE, B.INSD_UNIT, B.INSD_KEMAS, 
				               B.INSD_TKEMAS, B.INSD_PRIC, B.INSD_PART, B.INSD_NBRN, B.INSD_UMCD, B.INSD_SEQH, B.INSD_NAME, B.INSD_SJNO, B.INSD_VEND, B.INSD_LIN2, B.INSD_MINO, A.INSH_TOTAL
						FROM ".$this->inspecth2." A
						LEFT JOIN ".$this->inspectd2." B ON A.INSH_IDXX = B.INSD_IDXX
						WHERE B.INSD_LINE = '". $line ."'";
			}else{
				$sql = "select A.Id COLLATE Korean_Wansung_CI_AS INSH_IDXX, A.tanggal INSH_DATE, A.sjno INSH_SJNO, A.warehouse INSH_WHID, 0 INSH_VSAT, 
							   B.headerPONumber INSD_PONO, CASE WHEN B.headerSEQN = 0 THEN MAX(COALESCE(C.POMD_SEQN, D.POMD_SEQN)) ELSE B.headerSEQN END INSD_SEQN, 
							   0 INSD_LVND, B.headerMatCode INSD_CODE, B.unit INSD_UNIT,
							   B.kemasan INSD_KEMAS, 0 INSD_TKEMAS, MAX(COALESCE(C.POMD_PRIC, D.POMD_PRIC)) INSD_PRIC, COALESCE(E.POMH_PART, F.POMH_PART) INSD_PART, 
							   COALESCE(E.POMH_POID, F.POMH_NBRN) INSD_NBRN, COALESCE(E.POMH_UMCD, F.POMH_UMCD) INSD_UMCD, B.headerSEQH INSD_SEQH, '' INSD_NAME, 
							   A.sjno INSD_SJNO, A.vendorid INSD_VEND, 0 INSD_LIN2, '' INSD_MINO, A.total INSH_TOTAL
						FROM ".$this->inspecth." A
						LEFT JOIN ".$this->inspectd." B ON A.Id = B.headerID
						LEFT JOIN ".$this->pomxxd." C ON C.POMD_PONO = B.headerPONumber AND C.POMD_CODE = B.headerMatCode
						LEFT JOIN ".$this->pomexd." D ON D.POMD_PONO = B.headerPONumber AND D.POMD_CODE = B.headerMatCode
						LEFT JOIN ".$this->pomxxh." E ON E.POMH_PONO = C.POMD_PONO
						LEFT JOIN ".$this->pomexh." F ON F.POMH_PONO = D.POMD_PONO
						WHERE B.line = '". $line ."'
						GROUP BY A.Id, A.tanggal, A.sjno, A.warehouse, B.headerPONumber, B.headerSEQN, B.headerMatCode, B.unit, B.kemasan, 
								 COALESCE(E.POMH_PART, F.POMH_PART), COALESCE(E.POMH_POID, F.POMH_NBRN),COALESCE(E.POMH_UMCD, F.POMH_UMCD), 
								 B.headerSEQH, A.vendorid, A.total";
				// $sql= "select A1.Id COLLATE Korean_Wansung_CI_AS Id, B1.headerSEQN, B1.headerSEQH, B1.headerMatCode COLLATE Korean_Wansung_CI_AS headerMatCode, 
				// 		      '' matname, B1.unit COLLATE Korean_Wansung_CI_AS unit, B1.headerPONumber COLLATE Korean_Wansung_CI_AS headerPONumber, 
				// 		      A1.tanggal, A1.sjno COLLATE Korean_Wansung_CI_AS sjno, B1.release, B1.style, B1.harga, '' umcd, A1.vendorid COLLATE Korean_Wansung_CI_AS vendorid,
				// 		      A1.warehouse COLLATE Korean_Wansung_CI_AS warehouse, '0' INSH_VSAT, B1.kemasan, '0' INSD_TKEMAS, B1.qtty, B1.line
				// 		FROM ".$this->inspecth." A1
				// 		LEFT JOIN ".$this->inspectd." B1 ON A1.Id = B1.headerID
				// 		WHERE B1.line = '". $line ."'";
			}
		}else{
				$sql = "select A.INSH_IDXX, A.INSH_DATE, A.INSH_SJNO, A.INSH_WHID, A.INSH_VSAT, B.INSD_PONO, B.INSD_SEQN, B.INSD_LVND, B.INSD_CODE, B.INSD_UNIT, B.INSD_KEMAS, 
				               B.INSD_TKEMAS, B.INSD_PRIC, B.INSD_PART, B.INSD_NBRN, B.INSD_UMCD, B.INSD_SEQH, B.INSD_NAME, B.INSD_SJNO, B.INSD_VEND, B.INSD_LIN2, B.INSD_MINO, A.INSH_TOTAL
						FROM ".$this->inspecth2." A
						LEFT JOIN ".$this->inspectd2." B ON A.INSH_IDXX = B.INSD_IDXX
						WHERE B.INSD_LIN2 = '". $line ."'";
		}
		// $sql = "select A.INSH_IDXX, B.INSD_SEQN, B.INSD_SEQH, B.INSD_CODE, B.INSD_NAME, B.INSD_UNIT, B.INSD_PONO, A.INSH_DATE, A.INSH_SJNO, B.INSD_NBRN, B.INSD_PART, 
		// 		       B.INSD_PRIC, B.INSD_UMCD, B.INSD_VEND, A.INSH_WHID, A.INSH_VSAT, B.INSD_KEMAS, B.INSD_TKEMAS, B.INSD_BQTY, B.INSD_LINE
		// 		FROM ".$this->inspecth2." A
		// 		LEFT JOIN ".$this->inspectd2." B ON A.INSH_IDXX = B.INSD_IDXX
		// 		WHERE B.INSD_LINE = '". $line ."'";
		// if(substr($line,0,1) != 'B'){
		// $sql.= "		UNION ALL
		// 		SELECT A1.Id COLLATE Korean_Wansung_CI_AS Id, B1.headerSEQN, B1.headerSEQH, B1.headerMatCode COLLATE Korean_Wansung_CI_AS headerMatCode, '' matname, B1.unit COLLATE Korean_Wansung_CI_AS unit, 
		// 			   B1.headerPONumber COLLATE Korean_Wansung_CI_AS headerPONumber, A1.tanggal, A1.sjno COLLATE Korean_Wansung_CI_AS sjno, B1.release, B1.style,
		// 			   B1.harga, '' umcd, A1.vendorid COLLATE Korean_Wansung_CI_AS vendorid, A1.warehouse COLLATE Korean_Wansung_CI_AS warehouse, '0' INSH_VSAT, B1.kemasan, '0' INSD_TKEMAS, B1.qtty, B1.line
		// 		FROM ".$this->inspecth." A1
		// 		LEFT JOIN ".$this->inspectd." B1 ON A1.Id = B1.headerID
		// 		WHERE B1.line = '". $line ."'";
		// }
		return $this->db->query($sql)->result();
	}

	public function cekPrintBarcode($id, $param){
		$this->db->select('INSD_LINE, INSD_PRNT, INSD_IQTY');
		$this->db->from($this->inspectd2);
		$this->db->where('INSD_IDXX', $id);
		// $this->db->where('INSD_IQTY >', 0);
		if($param != ''){
		$this->db->where_not_in('INSD_LINE', $param);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function cekTotalBarcode($id){
		$sql = "select COUNT(*) CNT
				FROM ".$this->inspectd2."
				WHERE INSD_IDXX = '". $id ."'
				AND INSD_IQTY != 0";
		return $this->db->query($sql)->row();
	}

	public function TotalBarcodeInspect($pono, $matlcode, $seqn){
		$sql = "select SUM(INSD_IQTY) QTTY
				FROM ".$this->inspectd2."
				WHERE INSD_PONO = '". $pono ."'
				AND INSD_CODE = '". $matlcode ."'
				AND INSD_SEQN = '". $seqn ."'
				AND INSD_IQTY > 0";
		return $this->db->query($sql)->result();
	}

	public function cekBarcodeVendorExist($id){
		$sql = "select COUNT(*) CNT
				FROM ".$this->inspectd2."
				WHERE INSD_LIN2 = '". $id ."'";
		return $this->db->query($sql)->row();
	}

	public function getListMaterialInbyLine($line){
	$tranno = '';
		$sql="select A0.INSD_IDXX
			  FROM (
						SELECT INSD_IDXX
						FROM ".$this->inspectd2."
						WHERE INSD_LINE = '". $line ."'
						UNION ALL
						SELECT INSD_IDXX
						FROM ".$this->inspectd2."
						WHERE convert(varchar(200),INSD_LIN2) = '". $line ."'
					) A0
			  GROUP BY A0.INSD_IDXX";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$tranno = $d->INSD_IDXX;	
		}
		return $tranno;
	}

	public function listTransBarcodeIn($pono, $vend, $dt1, $dt2, $wh, $tranno){
		$sql="	select A.INSH_IDXX, A.INSH_DATE, B.INSD_PONO, B.INSD_NBRN, B.INSD_PART, B.INSD_VEND, C.VEND_DESC, B.INSD_SJNO, SUM(B.INSD_BQTY) QTTY
				FROM ".$this->inspecth2." A
				LEFT JOIN ".$this->inspectd2." B ON B.INSD_IDXX = A.INSH_IDXX
				LEFT JOIN ".$this->vendor." C ON C.VEND_VEND = B.INSD_VEND
				WHERE B.INSD_IDXX != ''";
		if ($tranno != ""){
			$sql.= "AND INSD_IDXX = '". $tranno ."'";
		}
		if ($dt1 != "" and $dt2 != ""){
			$sql.= "AND A.INSH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
		}
		if ($pono != ""){
			$sql.= "AND B.INSD_PONO = '". $pono ."'";
		}
		if ($vend != ""){
			$sql.= "AND B.INSD_VEND = '". $vend ."'";
		}
		if ($wh != ""){
			$sql.= "AND A.INSH_WHID = '". $wh ."'";
		}
		$sql.="GROUP BY A.INSH_IDXX, A.INSH_DATE, B.INSD_PONO, B.INSD_NBRN, B.INSD_PART, B.INSD_VEND, C.VEND_DESC, B.INSD_SJNO";
		return $this->db->query($sql)->result();
	}

	public function getTransactionInBacode($tgl1, $tgl2, $wh){
		$sql = "select A0.kode, max(a0.warehouse) as wh
					  ,max(a0.unit) as UNIT    
				      ,sum(a0.qtty) as in_qtty
				      ,round(sum(a0.qtty * a0.harga * a0.rate),0) as in_amount
				      ,round(sum(case when a0.kurs='001' then case when rate0=0 then 0 else a0.qtty * a0.harga/rate0 end  
				                      when a0.kurs='002' then a0.qtty * a0.harga
				                      when a0.kurs='004' then a0.qtty * a0.harga else 0 END),2) as in_amount_usd
				from(
						SELECT A.INSD_VEND AS vendorid, B.INSH_WHID AS warehouse, A.INSD_PONO AS poNumber, A.INSD_CODE as kode, A.INSD_UNIT AS unit, 
							   A.INSD_IQTY AS qtty, A.INSD_UMCD AS kurs, A.INSD_PRIC as harga, B.INSH_DATE AS tanggal,
							   CASE WHEN A.INSD_UMCD = '001' THEN 1 ELSE r0.EXRA_RATE end as rate, case when v0.vend_imex='N' then 1 else 2 end as import,
							   r0.EXRA_RATE as rate0
						FROM ". $this->inspectd2 ." A
						LEFT JOIN ".$this->inspecth2." B ON A.INSD_IDXX = B.INSH_IDXX
						left join ".$this->exraxm." as r0 on r0.EXRA_CURR='IDR' and r0.EXRA_DIVI='1' and r0.EXRA_STDT=B.INSH_DATE                        
						left join ".$this->vendor." as v0 on v0.vend_vend=A.INSD_VEND collate database_default   
						WHERE B.INSH_DATE BETWEEN '". $tgl1 ."' AND '". $tgl2 ."'
						AND B.INSH_WHID = '". $wh ."'
					)a0
				where 1=1
				AND a0.warehouse = '". $wh ."'
				GROUP BY a0.kode";
		return $this->db->query($sql)->result();
	}
}

/* End of file  */
/* Location: ./application/models/ */