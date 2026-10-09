<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Inspectionh_Model.php");

class Inspectiond_Model extends Inspectionh_Model {

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
							   A.INSD_BQTY QtyAkhir, B.INSH_DATE tanggal, B.INSH_VEND COLLATE Korean_Wansung_CI_AS vendorid,
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
		$sql.= ") A0";
		return $this->db->query($sql)->result();
	}

	public function getBarcodeline(){

		$sql = "select CONVERT(VARCHAR(10),GETDATE(), 112) bln, COUNT(INSD_LINE) NOMER
				FROM ". $this->inspectd2 ." WHERE LEFT(LTRIM((REPLACE(INSD_LINE,'B',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)";

		return $this->db->query($sql)->result();
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

	public function CalculateDetail($data, $id){
		$lineItem = 0;
		$details2 = array();
		$data2 = array();

		// $param = array();
		// $this->delete_by_barcode($id);
	    // if(count($lst_barc) != 0){
	    // 	foreach($lst_barc as $lb){
	    // 		$this->inspectd->delete_by_barcode($lb['barcode']);
	    // 	}
	    // }
		$bc = $this->getBarcodeline();
			foreach ($bc as $value) {
				$urut = $value->NOMER;
			}
	    foreach ($data as $lst) {
	    	$serial = '';
	    	$urut = $urut + 1;
	    	$line = 'B'. $value->bln . $urut;
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
		        $data1 = array(
					     'INSD_IDXX'=>$id,
					     'INSD_LINE'=>$line,
					     'INSD_LIN2'=>$line2,
					     'INSD_PONO'=>$lst['pono'],
					     'INSD_MINO'=>$lst['mino'],
					     'INSD_SEQN'=>$lst['seqn'],
					     'INSD_SEQH'=>$lst['seqh'],
					     'INSD_CODE'=>$lst['matcode'],
					     'INSD_NAME'=>$lst['matname'],
					     'INSD_UNIT'=>$lst['matunit'],
					     'INSD_KEMAS'=>$lst['kem'],
					     'INSD_TKEMAS'=>$lst['jmlkem'],
					     'INSD_IQTY'=>floatval($lst['qtty']),
					     'INSD_OQTY'=>0,
					     'INSD_RQTY'=>0,
					     'INSD_BQTY'=>floatval($lst['qtty']),
					     'INSD_PRNT'=>0,
						 'INSD_CKQC'=>0,
						 'INSD_PRIC'=>$lst['price'],
						 'INSD_UMCD'=>$lst['umcd'],
					     'INSD_PART'=>$style,
					     'INSD_NBRN'=>$release,
					     'INSD_SJNO'=>$sjno,
					     'INSD_VEND'=>$vend,
					     'INSD_LVND'=>$lineItem
					     );

		       	$this->db->set($data1);
				$this->db->insert($this->inspectd2);

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
	            'ACTION' => '<a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak" onclick="printBybarcode('."'".$line."'".')"><i class="fas fa-print"></i></a>',
	            'SEQH'=>$lst['seqh'],
	            'SEQN'=>$lst['seqn']
	        ));
	    }
	    return $details2;
	}

	public function delete_detail($id)
	{
		$this->db->where('INSD_IDXX', $id);
		$this->db->delete($this->inspectd2);
	}

	public function delete_by_barcode($id)
	{
		$this->db->where('INSD_LINE', $id);
		$this->db->delete($this->inspectd2);
	}

	public function getTotalQttyVend($id){
		$sql = "select SUM(INSD_IQTY) qtty
				FROM ".$this->inspectd2."
				WHERE INSD_IDXX = '". $id ."'";

		return $this->db->query($sql)->result();
	}

	public function detailBarcode($id, $vend_stat = 0){
		if($vend_stat == 0){
			if(substr($id,0,1) == 'B'){
				$sql = "select INSD_LINE, INSD_CODE, INSD_NAME, INSD_UNIT, INSD_BQTY, INSD_IQTY
				FROM ".$this->inspectd2."
				WHERE INSD_LINE = '". $id ."'";
			}else{
		$sql=  "select CONVERT(VARCHAR(255),line) line, headerMatCode, '' matname, unit COLLATE Korean_Wansung_CI_AS unit, QtyAkhir, qtty
				FROM ".$this->inspectd."
				WHERE CONVERT(VARCHAR(255),line) = '". $id ."'";
			}
		}else{
			if(substr($id,0,1) == 'B'){
				$sql = "select INSD_LINE, INSD_CODE, INSD_NAME, INSD_UNIT, INSD_BQTY, INSD_IQTY
				FROM ".$this->inspectd2."
				WHERE INSD_LINE = '". $id ."'";
			}else{
				$sql = "select INSD_LINE, INSD_CODE, INSD_NAME, INSD_UNIT, INSD_BQTY, INSD_IQTY
				FROM ".$this->inspectd2."
				WHERE INSD_LIN2 = '". $id ."'";
			}
		}
		return $this->db->query($sql)->result();
	}

	public function updateBarcodeqty($line,$qty, $stat, $proc, $vend_stat = 0){
	$qtty = floatval($qty);
	if($stat != 'd'){
		if($proc == 'RJT'){
			$details= array(
							'INSD_BQTY'=>floatval(0),
					        'INSD_RQTY'=>floatval($qtty)
					        );

		}elseif($proc == 'SPT'){
		$details= array(
			        'INSD_IQTY'=>floatval($qtty),
			        'INSD_BQTY'=>floatval($qtty)
			        );
		}else{
			$details= array(
					        'INSD_BQTY'=>floatval(0),
					        'INSD_OQTY'=>floatval($qtty)
					        );
		}
	}else{
		if($proc == 'RJT'){
		$details= array(
			        'INSD_BQTY'=>floatval($qtty),
			        'INSD_RQTY'=>floatval(0)
			        );
		}elseif($proc == 'SPT'){
		$details= array(
			        'INSD_IQTY'=>floatval($qtty),
			        'INSD_BQTY'=>floatval($qtty)
			        );
		}else{
		$details= array(
			        'INSD_BQTY'=>floatval($qtty),
			        'INSD_OQTY'=>floatval(0)
			        );
		}
	}
	if($vend_stat == 0){	
		$param= array(
				       'INSD_LINE'=>$line
				     );
	}else{
		$param= array(
			           'INSD_LIN2'=>$line
			     	);
	}
	$this->db->where($param);
	$this->db->update($this->inspectd2, $details);
	}

	public function getDetailBarcodeSplit($line, $vstat){
		if($vstat == 0){
			if(substr($line,0,1) == 'B'){
				$sql = "select A.INSH_IDXX, A.INSH_DATE, A.INSH_SJNO, A.INSH_WHID, A.INSH_VSAT, B.INSD_PONO, B.INSD_SEQN, B.INSD_LVND, B.INSD_CODE, B.INSD_UNIT, B.INSD_KEMAS, 
				               B.INSD_TKEMAS, B.INSD_PRIC, B.INSD_PART, B.INSD_NBRN, B.INSD_UMCD, B.INSD_SEQH, B.INSD_NAME, B.INSD_SJNO, B.INSD_VEND, B.INSD_LIN2, B.INSD_MINO
						FROM ".$this->inspecth2." A
						LEFT JOIN ".$this->inspectd2." B ON A.INSH_IDXX = B.INSD_IDXX
						WHERE B.INSD_LINE = '". $line ."'";
			}else{
				$sql= "select A1.Id COLLATE Korean_Wansung_CI_AS Id, B1.headerSEQN, B1.headerSEQH, B1.headerMatCode COLLATE Korean_Wansung_CI_AS headerMatCode, 
						      '' matname, B1.unit COLLATE Korean_Wansung_CI_AS unit, B1.headerPONumber COLLATE Korean_Wansung_CI_AS headerPONumber, 
						      A1.tanggal, A1.sjno COLLATE Korean_Wansung_CI_AS sjno, B1.release, B1.style, B1.harga, '' umcd, A1.vendorid COLLATE Korean_Wansung_CI_AS vendorid,
						      A1.warehouse COLLATE Korean_Wansung_CI_AS warehouse, '0' INSH_VSAT, B1.kemasan, '0' INSD_TKEMAS, B1.qtty, B1.line
						FROM ".$this->inspecth." A1
						LEFT JOIN ".$this->inspectd." B1 ON A1.Id = B1.headerID
						WHERE B1.line = '". $line ."'";
			}
		}else{
				$sql = "select A.INSH_IDXX, A.INSH_DATE, A.INSH_SJNO, A.INSH_WHID, A.INSH_VSAT, B.INSD_PONO, B.INSD_SEQN, B.INSD_LVND, B.INSD_CODE, B.INSD_UNIT, B.INSD_KEMAS, 
				               B.INSD_TKEMAS, B.INSD_PRIC, B.INSD_PART, B.INSD_NBRN, B.INSD_UMCD, B.INSD_SEQH, B.INSD_NAME, B.INSD_SJNO, B.INSD_VEND, B.INSD_LIN2, B.INSD_MINO
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
		$this->db->where('INSD_IQTY >', 0);
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
				AND INSD_IQTY > 0";
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
}

/* End of file  */
/* Location: ./application/models/ */