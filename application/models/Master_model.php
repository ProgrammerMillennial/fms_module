<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/RdoDic_Master.php");

class Master_model extends RdoDic_Master {

	// public $variable;

	public function __construct()
	{
		parent::__construct();
		// $this->db2 = $this->load->database('connir', TRUE);
		
	}

	function getLogin($user, $pass){		
		$sql = "select EMPL_NMBR, CASE WHEN EMPL_NMBR = 'ADMIN' THEN 'ADMIN' ELSE B.EMPL_ENME END EMPL_ENME
				FROM ". $this->emp02m ." A
				LEFT JOIN ". $this->emplxm ." B ON A.EMPL_NMBR = B.EMPL_EPCD
				WHERE A.EMPL_NMBR = '".$user."' AND A.EMPL_PSWD = '".$pass."'";
		return $this->db->query($sql)->result();
	}

	public function getWarehouselist(){

		return $this->db->query("select A.ROTE_OPCD, A.ROTE_NAME
			  FROM ". $this->routem ." A
			  LEFT JOIN ". $this->whakses ." B ON B.OPCD = A.ROTE_OPCD
			  WHERE B.OPCD IS NOT NULL
			  GROUP BY A.ROTE_OPCD, A.ROTE_NAME")->result();
	}
	

	public function getWarehouserack($type){

		return $this->db->query("select LOCATION_ROW, LOCATION_TYPE FROM ". $this->location ." 
								 WHERE LOCATION_TYPE IN ('".$type."') GROUP BY LOCATION_ROW, LOCATION_TYPE
								 ORDER BY LOCATION_TYPE DESC, LOCATION_ROW")->result();
	}

	public function getHeadTransBarcode($barcode, $stat){
		if($stat == 0){
			if(substr($barcode,0,1) == 'B'){
			$sql = "select INSD_LINE line, INSD_CODE mat_code, INSD_BQTY qtyakhir, INSD_NAME matname
					FROM ".$this->inspectd2."
					WHERE INSD_LINE = '".$barcode."'";
			}else{
			$sql = "select CONVERT(VARCHAR(255),a.line) line, a.headerMatCode COLLATE Korean_Wansung_CI_AS mat_code,  a.qtyakhir, '' matname
					FROM ".$this->inspectd." A
					WHERE CONVERT(VARCHAR(255),a.line) = '".$barcode."'";
			}
		}else{
			$sql = "select CONVERT(VARCHAR(255),INSD_LIN2) line, INSD_CODE mat_code, INSD_BQTY qtyakhir, INSD_NAME matname
					FROM ".$this->inspectd2."
					WHERE CONVERT(VARCHAR(255),INSD_LIN2) = '".$barcode."'";
		}


		return $this->db->query($sql)->result();
	}

	public function getHeadTransBarcodeDetail($barcode, $stat){
		if($stat == 0){
			$sql = "select ISSD_LINE, ISSD_NLINE, ISSD_QTTY
					FROM ".$this->inspectSplit2."
					WHERE ISSD_LINE = '".$barcode."'
					UNION ALL
					select CONVERT(VARCHAR(225),header_line) ISSD_LINE, CONVERT(VARCHAR(225),new_line) ISSD_NLINE, qtty ISSD_QTTY
					FROM ".$this->inspectSplit."
					WHERE CONVERT(VARCHAR(255),header_line) = '".$barcode."'";
		}else{
			$sql = "select ISSD_LINE, ISSD_NLINE, ISSD_QTTY
					FROM ".$this->inspectSplit2."
					WHERE ISSD_LINE = '".$barcode."'";
		}
		return $this->db->query($sql)->result();
	}

	public function getTransactionBarcode($barcode){

		$sql = "select CONVERT(VARCHAR(200),a.line) line, b.Id id_in, a.headerMatCode mat_code, b.tanggal tgl_in, b.sjno, a.headerPONumber pono, a.qtty qty_in, 
					   COALESCE(C.headerID, E.ONSD_IDXX COLLATE DATABASE_DEFAULT) id_out, COALESCE(D.tanggal, F.ONSH_DATE) tgl_out, COALESCE(C.header_MR_NO, E.ONSD_MRNO COLLATE DATABASE_DEFAULT) mr_no, COALESCE(C.Qtty, E.ONSD_QTTY) qty_out, a.qtyakhir
				FROM ".$this->inspectd." A
				LEFT JOIN ".$this->inspecth." B ON B.ID = A.headerID
				LEFT JOIN ".$this->outbarcoded." C ON C.InspectionD_line = A.line
				LEFT JOIN ".$this->outbarcodeh." D ON D.id = C.headerID
				LEFT JOIN ".$this->outbarcoded2." E ON E.ONSD_LINE = CONVERT(VARCHAR(50),a.line)
				LEFT JOIN ".$this->outbarcodeh2." F ON F.ONSH_IDXX = E.ONSD_IDXX
				WHERE CONVERT(VARCHAR(50),a.line) = '".$barcode."'
				UNION ALL
				SELECT a.INSD_LINE COLLATE DATABASE_DEFAULT, b.INSH_IDXX id_in, a.INSD_CODE mat_code, b.INSH_DATE tgl_in, CASE WHEN b.INSH_SJNO = '' THEN a.INSD_SJNO ELSE b.INSH_SJNO END COLLATE DATABASE_DEFAULT, 
					   a.INSD_PONO pono, 
                       a.INSD_IQTY qty_in, C.ONSD_IDXX COLLATE DATABASE_DEFAULT id_out, D.ONSH_DATE tgl_out, C.ONSD_MRNO COLLATE DATABASE_DEFAULT mr_no, 
                       C.ONSD_QTTY qty_out, a.INSD_BQTY 
				FROM ".$this->inspectd2." A
				LEFT JOIN ".$this->inspecth2." B ON B.INSH_IDXX = A.INSD_IDXX
				LEFT JOIN ".$this->outbarcoded2." C ON C.ONSD_LINE = A.INSD_LINE
				LEFT JOIN ".$this->outbarcodeh2." D ON D.ONSH_IDXX = C.ONSD_IDXX
				WHERE a.INSD_LIN2 = 0
				AND a.INSD_LINE = '".$barcode."'
				UNION ALL
				SELECT  CONVERT(VARCHAR(200),a.INSD_LIN2) INSD_LIN2 , b.INSH_IDXX id_in, a.INSD_CODE mat_code, b.INSH_DATE tgl_in, CASE WHEN b.INSH_SJNO = '' THEN a.INSD_SJNO ELSE b.INSH_SJNO END COLLATE DATABASE_DEFAULT, 
					   a.INSD_PONO pono, 
                       a.INSD_IQTY qty_in, C.ONSD_IDXX COLLATE DATABASE_DEFAULT id_out, D.ONSH_DATE tgl_out, C.ONSD_MRNO COLLATE DATABASE_DEFAULT mr_no, 
                       C.ONSD_QTTY qty_out, a.INSD_BQTY 
				FROM ".$this->inspectd2." A
				LEFT JOIN ".$this->inspecth2." B ON B.INSH_IDXX = A.INSD_IDXX
				LEFT JOIN ".$this->outbarcoded2." C ON C.ONSD_LINE = COALESCE(A.INSD_LINE, CONVERT(VARCHAR(50),a.INSD_LIN2))
				LEFT JOIN ".$this->outbarcodeh2." D ON D.ONSH_IDXX = C.ONSD_IDXX
				WHERE a.INSD_LIN2 != 0
				AND CONVERT(VARCHAR(50),a.INSD_LIN2) = '".$barcode."'";
		// var_dump($sql);
		return $this->db->query($sql)->result();
	}

	public function get_wh_by_id($id){

		return $this->db->query("select A.ROTE_OPCD, A.ROTE_NAME
								 FROM ".$this->routem." A
								 LEFT JOIN ".$this->whakses." B ON B.OPCD = A.ROTE_OPCD
								 WHERE B.OPCD IS NOT NULL
								 AND B.USERID LIKE '".$id."'
								 GROUP BY A.ROTE_OPCD, A.ROTE_NAME
								 ORDER BY A.ROTE_OPCD DESC")->result();
	}

	public function loadDataMaterial($dMatl){
	$tb = "";
	$t = "";
	$lkode="";
	$p = 0;
		if(count($this->dMatl) > 50) {
			$tb = "##T" . $this->createTableTempID();
			$this->DeleteTempTabel($tb);

	        $s = "create table ". $tb ." (kode varchar(30) collate korean_wansung_ci_as)";
	        $this->db->query($s);

	        foreach ($this->dMatl as $val) {
	        	$si = "insert INTO ". $tb ." (kode) \n";
	        	$si.= "select '". $val['kode'] ."'";
	        	$this->db->query($si);
	        }
	        $t = $tb;
		}else{
        	$t = "( \n";
        	if(count($this->dMatl) > 0) {
		        foreach ($this->dMatl as $val) {
		        	if($p == 0){
		        		$t.= "select '". $val['kode'] ."' as kode \n";
		        	}else{
						$t.= "union all \n";
		        		$t.= "select '". $val['kode'] ."' as kode \n";
		        	}
		        	$p = $p + 1;
		        }
        	}else{
        		$t.= "select '' as kode \n";
        	}
        	$t.= ")";
		}

		$sql = "select  a0.* 
			    , COALESCE(b0.PARD_NME1,G.PARD_NME1) AS mat_name
			    ,C.PARD_NME1 AS tipe_name
			    ,D.PARD_NME1 AS wide_name
			    ,COALESCE(e.PARD_NME1,H.PARD_NME1) AS spec_name
			    ,f.CODD_DESC AS colo_name
			    ,COALESCE(b0.PARD_UNIT,g.PARD_UNIT) AS mat_unit, COALESCE(F2.PARH_DIVI,'C') as PARH_DIVI,f2.PARH_NAME as groupName
			    ,F3.PARM_MCSN AS PARM_MCSN, coalesce(B0.PARD_ACCX,G.PARD_ACCX) AS PARD_ACCX, F2.PARH_HSNO, f.CODD_DATA1 AS colo_remark
			    ,F2.PARH_GROP groupCode, coalesce(B0.PARD_EPTE,G.PARD_EPTE) AS epte_code
			from  ". $t ."  as a0
			LEFT JOIN PRTMMRP.PRTM.TO_PARTXD AS B0 ON B0.PARD_GROP=SUBSTRING(kode, 1, 1)
			                        AND B0.PARD_CODE = SUBSTRING(kode, 2, 2)
			                        AND B0.PARD_DIVI = '1'
			LEFT JOIN PRTMMRP.PRTM.TO_PARTXG AS G ON  G.PARD_GROP= SUBSTRING(kode, 1, 1)
			                        AND G.PARD_DIVI = '2'
			                        AND G.PARD_CODE= SUBSTRING(kode, 5, 3)
			                        AND G.PARD_DEPT=SUBSTRING(kode, 2, 3)
			LEFT JOIN PRTMMRP.PRTM.TO_PARTXD AS C ON C.PARD_GROP = SUBSTRING(kode, 1, 1)
			                        AND C.PARD_CODE = SUBSTRING(kode, 4, 2)
			                        AND C.PARD_DIVI = '2'
			LEFT JOIN PRTMMRP.PRTM.TO_PARTXD AS D ON D.PARD_GROP = SUBSTRING(kode, 1, 1)
			                        AND D.PARD_CODE = SUBSTRING(kode, 6, 1)
			                        AND D.PARD_DIVI = '3'
			LEFT JOIN  PRTMMRP.PRTM.TO_PARTXD AS E ON E.PARD_DIVI = '4'
			                        AND E.PARD_GROP = SUBSTRING(kode, 1, 1)
			                        AND E.PARD_CODE = SUBSTRING(kode, 7, 2)
			LEFT JOIN PRTMMRP.PRTM.TO_PARTXG AS H ON H.PARD_GROP = SUBSTRING(kode, 1, 1)
			                        AND H.PARD_DIVI = '4'
			                        AND H.PARD_CODE = SUBSTRING(kode, 8, 3)
			                        AND H.PARD_DEPT = SUBSTRING(kode, 2, 3)
			LEFT JOIN PRTMERP.PRTM.TB_CODEXD AS F ON  F.CODD_FLNM = 'ITEM_COLO'
			                        AND F.CODD_VALU = SUBSTRING(kode, 9, 4)
			LEFT JOIN PRTMMRP.PRTM.TO_PARTXH AS F2 ON F2.PARH_GROP=SUBSTRING(kode, 1, 1)
			LEFT JOIN PRTMMRP.PRTM.TO_PARTXM AS F3 ON F3.PARM_CODE=a0.kode
			WHERE 1=1";

			$lkode = $this->db->query($sql)->result();

			if($tb != ""){
				$this->DeleteTempTabel($tb);
			}

			return $lkode;
	}

	public function loadGropMaterial(){
		$sql = "select *
				from  PRTMMRP.PRTM.TO_PARTXH";
		return $this->db->query($sql)->result();
	}

	public function poDetail($pono, $dt1, $dt2, $vend, $fact, $wh){
		if($wh == 'MAT'){
			$listPo = $this->poDetailCommon($pono, $dt1, $dt2, $vend, $fact);
			if(count($listPo) == 0){
				$listPo = $this->poDetailGeneral($pono, $dt1, $dt2, $vend, $fact, $wh);
			}
		}else{
			$listPo = $this->poDetailGeneral($pono, $dt1, $dt2, $vend, $fact, $wh);
		}
		return $listPo;
	}

	public function poVendor($pono, $dt1, $dt2, $vend, $fact, $wh){
		if($wh == 'MAT'){
			$listPo = $this->poCommonVend($pono, $dt1, $dt2, $vend, $fact);
		}else{
			$listPo = $this->poGeneralVend($pono, $dt1, $dt2, $vend, $fact);
		}
		return $listPo;
	}

	public function poDetailVendor($wh){
		// if($wh == 'MAT'){
		// 	$listPo = $this->poCommonDetailVend($pono, $ttl_mi, $d);
		// }else{
		// 	$listPo = $this->poGeneralDetailVend($pono, $ttl_mi, $d);
		// }
		$listPo = $this->poBarcodeDetailVend($wh);
		return $listPo;
	}

	function poCommonVend($pono, $dt1, $dt2, $vend, $fact){

		$sql = "select A0.Pono POMH_PONO, A0.RECH_MINO, A0.POMH_POID, A0.POMH_PART, A0.vendor POMH_VEND, A0.RECH_DATE, 
		               SUM(A0.POMD_QTTY) POMD_QTTY, SUM(A0.RECD_QTTY) RECD_QTTY, 
					   SUM(A0.INSD_IQTY) POMD_INSP_QTTY, SUM(A0.RECD_QTTY)-SUM(A0.INSD_IQTY) BALANCE, A0.RECD_PO_SEQN, A0.POMH_ORDT
				FROM (
						SELECT A.Pono, D.RECH_MINO, G.POMD_CODE, F.POMH_POID, F.POMH_PART, B.vendor, D.RECH_DATE, G.POMD_QTTY POMD_QTTY, 
							   C.RECD_QTTY, ISNULL(E.INSD_IQTY,0) INSD_IQTY, C.RECD_QTTY-ISNULL(E.INSD_IQTY,0) BALANCE, C.RECD_PO_SEQN, F.POMH_ORDT
						FROM ".$this->sjvendord." A
						LEFT JOIN ".$this->sjvendor." B ON B.LineSj = A.LineSj
						LEFT JOIN ".$this->recxxd." C ON C.RECD_PONO = A.Pono
												  		  AND C.RECD_MATL = A.itemId
						LEFT JOIN ".$this->recxxh." D ON D.RECH_MINO = C.RECD_MINO
						LEFT JOIN (SELECT INSD_MINO, INSD_CODE, INSD_SEQN, SUM(INSD_IQTY) INSD_IQTY
								   FROM ".$this->inspectd2."
								   WHERE INSD_PONO LIKE '" . $pono ."%'
								   GROUP BY INSD_MINO, INSD_CODE, INSD_SEQN) E ON E.INSD_CODE = C.RECD_MATL
																			  AND E.INSD_SEQN = C.RECD_PO_SEQN
						LEFT JOIN ".$this->pomxxh." F ON F.POMH_PONO = D.RECH_PONO
						LEFT JOIN ".$this->pomxxd." G ON G.POMD_PONO = F.POMH_PONO
												          AND G.POMD_CODE = C.RECD_MATL
												          AND G.POMD_SEQN = C.RECD_PO_SEQN
				WHERE A.Pono LIKE '" . $pono ."%'
				AND C.RECD_QTTY IS NOT NULL
				AND C.RECD_MINO NOT LIKE 'RJ%'";
				if ($dt1 != "" and $dt2 != ""){ 
					// $sql.= "AND A.POMH_ORDT BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
					$sql.= "AND D.RECH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
				}
				if ($vend != ""){
					$sql.= "AND A.POMH_VEND LIKE '". $vend ."'";
				}
				if ($fact != ""){
					$sql.= "AND F.POMH_FACT = '". $fact ."'";
				}
				$sql .= "	GROUP BY A.Pono, D.RECH_MINO, F.POMH_POID, G.POMD_CODE, F.POMH_PART, B.vendor, D.RECH_DATE, F.POMH_QTTY, 
				            C.RECD_QTTY, ISNULL(E.INSD_IQTY,0), C.RECD_PO_SEQN, F.POMH_ORDT, G.POMD_QTTY
						 ) A0 
						GROUP BY A0.Pono, A0.RECH_MINO, A0.POMH_POID, A0.POMH_PART, A0.vendor, A0.RECH_DATE, A0.RECD_PO_SEQN, A0.POMH_ORDT
						ORDER BY A0.RECH_MINO, A0.RECD_PO_SEQN";

		return $this->db->query($sql)->result();
	}

	function poGeneralVend($pono, $dt1, $dt2, $vend, $fact){
		$sql = "select A0.Pono POMH_PONO, A0.RECH_MINO, A0.POMH_POID, A0.POMH_PART, A0.vendor POMH_VEND, A0.RECH_DATE, A0.POMD_QTTY, SUM(A0.RECD_QTTY) RECD_QTTY, 
					   SUM(A0.INSD_IQTY) POMD_INSP_QTTY, SUM(A0.RECD_QTTY)-SUM(A0.INSD_IQTY) BALANCE, A0.RECD_PO_SEQN, A0.POMH_ORDT
				FROM (
						SELECT A.Pono, D.RECH_MINO, G.POMD_CODE, F.POMH_POID, F.POMH_PART, B.vendor, D.RECH_DATE, SUM(G.POMD_QTTY) POMD_QTTY, 
							   C.RECD_QTTY, ISNULL(E.INSD_IQTY,0) INSD_IQTY, C.RECD_QTTY-ISNULL(E.INSD_IQTY,0) BALANCE, C.RECD_PO_SEQN, F.POMH_ORDT
						FROM ".$this->sjvendord." A
						LEFT JOIN ".$this->sjvendor." B ON B.LineSj = A.LineSj
						LEFT JOIN ".$this->recexd." C ON C.RECD_PONO = A.Pono
												  		  AND C.RECD_MATL = A.itemId
						LEFT JOIN ".$this->recexh." D ON D.RECH_MINO = C.RECD_MINO
						LEFT JOIN (SELECT INSD_MINO, INSD_CODE, INSD_SEQN, SUM(INSD_IQTY) INSD_IQTY
								   FROM ".$this->inspectd2."
								   WHERE INSD_PONO LIKE '" . $pono ."%'
								   GROUP BY INSD_MINO, INSD_CODE, INSD_SEQN) E ON E.INSD_CODE = C.RECD_MATL
																			  AND E.INSD_SEQN = C.RECD_PO_SEQN
						LEFT JOIN ".$this->pomexh." F ON F.POMH_PONO = D.RECH_PONO
						LEFT JOIN ".$this->pomexd." G ON G.POMD_PONO = F.POMH_PONO
												          AND G.POMD_CODE = C.RECD_MATL
												          AND G.POMD_SEQN = C.RECD_PO_SEQN
				WHERE A.Pono LIKE '" . $pono ."%'
				AND C.RECD_QTTY IS NOT NULL
				AND C.RECD_MINO NOT LIKE 'RJ%'";
				if ($dt1 != "" and $dt2 != ""){ 
					// $sql.= "AND A.POMH_ORDT BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
					$sql.= "AND D.RECH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
				}
				if ($vend != ""){
					$sql.= "AND A.POMH_VEND LIKE '". $vend ."'";
				}
				if ($fact != ""){
					$sql.= "AND F.POMH_FACT = '". $fact ."'";
				}
				$sql .= "	GROUP BY A.Pono, D.RECH_MINO, F.POMH_POID, G.POMD_CODE, F.POMH_PART, B.vendor, D.RECH_DATE, F.POMH_QTTY, 
				            C.RECD_QTTY, ISNULL(E.INSD_IQTY,0), C.RECD_PO_SEQN, F.POMH_ORDT
						 ) A0 
						GROUP BY A0.Pono, A0.RECH_MINO, A0.POMH_POID, A0.POMH_PART, A0.vendor, A0.RECH_DATE, A0.POMD_QTTY, A0.RECD_PO_SEQN, A0.POMH_ORDT
						HAVING SUM(A0.RECD_QTTY)-SUM(A0.INSD_IQTY) > 0
						ORDER BY A0.RECH_MINO, A0.RECD_PO_SEQN";
		return $this->db->query($sql)->result();
	}

	function poBarcodeDetailVend($wh){

		$sql = "select a0.*
				FROM (
						SELECT a.linebarcode, b.itemId, b.namaitem, b.satuan, a.qtty, CASE WHEN D.POMH_PONO IS NULL THEN 'ENG' ELSE 'MAT' END wh
						FROM ITERP.PRTM.suratJalanVendorDetailBarcode a
						LEFT JOIN ITERP.PRTM.suratJalanVendorDetail b ON a.LineSj = b.LineSj AND a.LineItem = b.LineItem
						LEFT JOIN ITERP.PRTM.suratJalanVendor c ON C.LineSj = A.LineSj
						LEFT JOIN PRTMMRP.PRTM.TO_POMXXH d ON d.POMH_PONO = b.Pono
						LEFT JOIN PRTMMRP.PRTM.TO_POMXXH e ON e.POMH_PONO = b.Pono
						WHERE ISNULL(a.lineBarcodeScan,0) = 0
						AND a.qtty > 0
						AND ISNULL(a.lineBarcodeScan,0) = 0
						AND LEN(b.itemId) > 10
						AND c.tanggal < CONVERT(VARCHAR(10),GETDATE(),111)
					 ) a0
				WHERE a0.wh = '". $wh ."'
				ORDER BY a0.itemId";

		return $this->db->query($sql)->result();
	}

	// function poCommonDetailVend($pono, $ttl_mi, $d){
	// 	$sql="  select top 4 C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, '' stat
	// 			FROM ".$this->sjvendord." B 
	// 			LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj AND B.LineItem = C.LineItem 
	// 			LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode 
	// 			LEFT JOIN ".$this->sjvendor." F ON B.LineSj = F.LineSj 
	// 			WHERE ISNULL(C.linebarcode,'') != ''
	// 			AND ISNULL(E.INSD_LIN2,'') = ''
	// 			AND B.Pono NOT IN (". $d .")";
	// 	return $this->db->query($sql)->result();
	// }

	// function poGeneralDetailVend($pono, $ttl_mi, $d){
	// 	$sql="  select C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, '' stat
	// 			FROM ".$this->sjvendord." B
	// 			LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
	// 											 AND B.LineItem = C.LineItem
	// 			LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
	// 			LEFT JOIN ".$this->sjvendor." F ON B.LineSj = F.LineSj
	// 			LEFT JOIN ".$this->pomexh." G ON G.POMH_PONO = B.Pono
	// 			WHERE G.POMH_PONO IS NOT NULL
	// 			AND E.INSD_LIN2 IS NULL
	// 			AND C.linebarcode IS NOT NULL
	// 			AND B.Pono NOT IN (SELECT headerPONumber FROM ".$this->inspectd." GROUP BY headerPONumber)
	// 			GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty
	// 			ORDER BY B.itemId";

	// 	return $this->db->query($sql)->result();
	// }

	public function browseBarcodeVendor($pono, $dt1, $dt2, $vend, $wh, $line){

		$sql = "select INSD_LIN2, INSD_NAME, INSD_UNIT, INSD_IQTY, INSH_DATE, INSD_PONO, INSD_SJNO, INSD_VEND, VEND_DESC, INSD_IDXX, INSH_WHID
				from ".$this->inspecth2." tmh
				inner join ".$this->inspectd2." tmd on tmh.INSH_IDXX = tmd.INSD_IDXX
				left join ".$this->vendor." tc on tc.VEND_VEND = INSD_VEND
				where INSD_LIN2 != 0";
		if($wh !== ''){
			$sql.="and INSH_WHID = '". $wh ."'";
		}
		if ($dt1 != "" and $dt2 != ""){
			$sql.= " and INSH_DATE between '". $dt1 ."' AND '". $dt2 ."'";
		}
		if ($vend != ""){
			$sql.= "AND INSD_VEND LIKE '". $vend ."'";
		}
		if ($line != ""){
			$sql.= "AND INSD_LIN2 = '". $line ."'";
		}
		return $this->db->query($sql)->result();
	}

	function poDetailCommon($pono, $dt1, $dt2, $vend, $fact){

		$sql = "select A.POMH_PONO, D.RECH_MINO, D.RECH_DATE, A.POMH_POID, A.POMH_PART, A.POMH_ORDT, A.POMH_VEND, SUM(ISNULL(B.POMD_QTTY,0)) POMD_QTTY, 
					   ISNULL(SUM(C.RECD_QTTY),0) RECD_QTTY, B1.INSPECT POMD_INSP_QTTY, 
					   (SUM(ISNULL(B.POMD_QTTY,0)) - b1.INSPECT) BALANCE
				FROM ".$this->pomxxh." A
				LEFT JOIN ".$this->pomxxd." B ON A.POMH_PONO = B.POMD_PONO
				LEFT JOIN ".$this->recxxd." C ON C.RECD_PONO = B.POMD_PONO AND C.RECD_MATL = B.POMD_CODE AND C.RECD_PO_SEQH = B.POMD_SEQH AND C.RECD_PO_SEQN = B.POMD_SEQN
				LEFT JOIN (SELECT INSD_PONO, INSD_CODE, CONVERT(DECIMAL(10,2),INSD_SEQN) INSD_SEQN, SUM(INSD_IQTY) INSPECT
		   				   FROM ".$this->inspectd2."
		                   GROUP BY INSD_PONO, INSD_CODE, CONVERT(DECIMAL(10,2),INSD_SEQN)) B1 ON B1.INSD_PONO = B.POMD_PONO AND B1.INSD_CODE = B.POMD_CODE AND B.POMD_SEQH = B1.INSD_SEQN
				LEFT JOIN ".$this->recxxh." D ON D.RECH_MINO = C.RECD_MINO
				WHERE A.POMH_PONO LIKE '" . $pono ."%'
				AND D.RECH_MINO NOT LIKE 'RJ%'";
		if ($dt1 != "" and $dt2 != ""){ 
			// $sql.= "AND A.POMH_ORDT BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
			$sql.= "AND D.RECH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
		}
		if ($vend != ""){
			$sql.= "AND A.POMH_VEND LIKE '". $vend ."'";
		}
		if ($fact != ""){
			$sql.= "AND A.POMH_FACT = '". $fact ."'";
		}
		$sql .= "GROUP BY A.POMH_PONO, A.POMH_POID, A.POMH_PART, A.POMH_ORDT, A.POMH_VEND, D.RECH_MINO, D.RECH_DATE, B1.INSPECT
				 ORDER BY A.POMH_PONO";


		// print_r($sql);

		return $this->db->query($sql)->result();
	}

	function poDetailGeneral($pono, $dt1, $dt2, $vend, $fact, $wh){

		$sql = "select A.POMH_PONO, D.RECH_MINO, D.RECH_DATE, A.POMH_POID, A.POMH_PART, A.POMH_ORDT, A.POMH_VEND, SUM(ISNULL(B.POMD_QTTY,0)) POMD_QTTY, 
					   ISNULL(SUM(C.RECD_QTTY),0) RECD_QTTY, B1.INSPECT POMD_INSP_QTTY, 
					   (SUM(ISNULL(B.POMD_QTTY,0)) - B1.INSPECT) BALANCE
				FROM ".$this->pomexh." A
				LEFT JOIN ".$this->pomexd." B ON A.POMH_PONO = B.POMD_PONO
				LEFT JOIN ".$this->recexd." C ON C.RECD_PONO = B.POMD_PONO AND C.RECD_MATL = B.POMD_CODE AND C.RECD_PO_SEQH = B.POMD_SEQH AND C.RECD_PO_SEQN = B.POMD_SEQN
				LEFT JOIN (SELECT INSD_PONO, INSD_CODE, CONVERT(DECIMAL(10,2),INSD_SEQN) INSD_SEQN, SUM(INSD_IQTY) INSPECT
		   				   FROM ".$this->inspectd2."
		                   GROUP BY INSD_PONO, INSD_CODE, CONVERT(DECIMAL(10,2),INSD_SEQN)) B1 ON B1.INSD_PONO = B.POMD_PONO AND B1.INSD_CODE = B.POMD_CODE AND B.POMD_SEQH = B1.INSD_SEQN
				LEFT JOIN ".$this->recexh." D ON D.RECH_MINO = C.RECD_MINO
				WHERE A.POMH_PONO LIKE '" . $pono ."%'
				AND D.RECH_MINO NOT LIKE 'RJ%'";
		if ($dt1 != "" and $dt2 != ""){ 
			$sql.= "AND A.POMH_ORDT BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
		}
		if ($vend != ""){
			$sql.= "AND A.POMH_VEND LIKE '". $vend ."'";
		}
		if ($fact != ""){
			$sql.= "AND A.POMH_FACT = '". $fact ."'";
		}
		if($wh == 'MAT'){
			$sql.= "AND (LEFT(B.POMD_CODE,1) IN ('M','W','T') OR LEFT(B.POMD_CODE,4)='X076')";	
		}elseif($wh == 'ENG'){
			$sql.= "AND LEFT(B.POMD_CODE,1) IN ('X')";	
		}
		$sql .= "GROUP BY A.POMH_PONO, A.POMH_POID, A.POMH_PART, A.POMH_ORDT, A.POMH_VEND, D.RECH_MINO, D.RECH_DATE, B1.INSPECT
			     ORDER BY A.POMH_PONO";

		// print_r($sql);

		return $this->db->query($sql)->result();
	}

	public function getDetailFormPO($pono, $wh, $mino){
		if($wh == 'MAT') {
			$headerPo = $this->headerPOcommon($pono, $mino);
			if(count($headerPo) == 0){
				$headerPo = $this->headerPOgeneral($pono, $mino);
			}
		}else{
			$headerPo = $this->headerPOgeneral($pono, $mino);
		}
		return $headerPo;
	}

	function headerPOcommon($pono, $mino){
		$sql = "select POMH_PONO, POMH_ORDT, POMH_POID, POMH_PART, POMH_VEND, B.MODD_NAME, C.VEND_DESC VDESC1, C.VEND_ADD1 VADD1, C.VEND_ADD2 VADD2, 
					   D.VEND_DESC VDESC2, D.VEND_ADD1 VADD3, D.VEND_ADD2 VADD4, E.RECH_MINO, E.RECH_DATE
				FROM ".$this->pomxxh." A
				LEFT JOIN ".$this->model." B ON LEFT(A.POMH_PART,6) = B.MODD_CODE
				LEFT JOIN ".$this->vendor." C ON C.VEND_VEND = A.POMH_VEND
				LEFT JOIN ".$this->vendor." D ON D.VEND_VEND = 'SM36'
				LEFT JOIN ".$this->recxxh." E ON E.RECH_PONO = A.POMH_PONO
				WHERE POMH_PONO = '".$pono."'
				AND RECH_MINO = '".$mino."'";
		return $this->db->query($sql)->result();
	}

	function headerPOgeneral($pono, $mino){
		$sql = "select POMH_PONO, POMH_ORDT, POMH_POID, POMH_PART, POMH_VEND, B.MODD_NAME, C.VEND_DESC VDESC1, C.VEND_ADD1 VADD1, C.VEND_ADD2 VADD2, 
					   D.VEND_DESC VDESC2, D.VEND_ADD1 VADD3, D.VEND_ADD2 VADD4, E.RECH_MINO, E.RECH_DATE
				FROM ".$this->pomexh." A
				LEFT JOIN ".$this->model." B ON LEFT(A.POMH_PART,6) = B.MODD_CODE
				LEFT JOIN ".$this->vendor." C ON C.VEND_VEND = A.POMH_VEND
				LEFT JOIN ".$this->vendor." D ON D.VEND_VEND = 'SM36'
				LEFT JOIN ".$this->recexh." E ON E.RECH_PONO = A.POMH_PONO
				WHERE POMH_PONO = '".$pono."'
				AND RECH_MINO = '".$mino."'";
		return $this->db->query($sql)->result();
	}

	public function getMiheader($pono, $mino){
		$sql = "select A.INSH_IDXX Id, ISNULL(INSD_MINO,'') mino, A.INSH_SJNO sjno, SUM(B.INSD_IQTY) qtty, B.INSD_LINE line
				FROM ".$this->inspecth2." A
				LEFT JOIN ".$this->inspectd2." B ON A.INSH_IDXX = B.INSD_IDXX
				WHERE B.INSD_PONO = '".$pono."'
				AND B.INSD_MINO = '".$mino."'
				GROUP BY A.INSH_IDXX, ISNULL(INSD_MINO,''), A.INSH_SJNO, B.INSD_LINE
				/*UNION ALL
				select A.Id COLLATE DATABASE_DEFAULT Id, '' MINO, A.sjno COLLATE DATABASE_DEFAULT sjno, SUM(B.qtty) qtty, CONVERT(VARCHAR(255),B.line)
				FROM ".$this->inspecth." A
				LEFT JOIN ".$this->inspectd." B ON A.Id = B.headerID
				WHERE B.headerPONumber = '".$pono."'
				GROUP BY A.Id, A.sjno, B.line*/
				ORDER BY A.INSH_IDXX";
		return $this->db->query($sql)->result();
	}

	public function getMiDetailMaterial($pono, $mino, $matcode, $wh){
		if($wh=='MAT'){
			$midetail = $this->getMiDetailMaterialCom($pono, $mino, $matcode);
			if(count($midetail) == 0){
				$midetail = $this->getMiDetailMaterialGen($pono, $mino, $matcode, $wh);
			}
		}else{
			$midetail = $this->getMiDetailMaterialGen($pono, $mino, $matcode, $wh);
		}
		return $midetail;
	}

	function getMiDetailMaterialCom($pono, $mino, $matcode){
		$sql = "select POMD_SEQN, POMD_CODE, POMD_QTTY POMD_QTTY, POMD_PRIC, POMH_UMCD, POMD_INSP_QTTY POMD_INSP_QTTY,
					   SUM(POMD_QTTY)-SUM(ISNULL(POMD_INSP_QTTY,0)) BALANCE, RECD_QTTY, SUM(ISNULL(E.INSD_IQTY,0)) IQTY, 
					   RECD_QTTY - SUM(ISNULL(E.INSD_IQTY,0)) BALANCE2, E.INSD_LOTNUMBER lotnumber, E.INSD_EXP_DATE tgl_expired
				FROM ".$this->pomxxd." A
				LEFT JOIN ".$this->pomxxh." B ON A.POMD_PONO = B.POMH_PONO
				LEFT JOIN ".$this->recxxh." C ON C.RECH_PONO = B.POMH_PONO
				LEFT JOIN ".$this->recxxd." D ON D.RECD_MINO = C.RECH_MINO
											 AND D.RECD_MATL = A.POMD_CODE
											 AND D.RECD_PO_SEQN = A.POMD_SEQN
				LEFT JOIN ".$this->inspectd2." E ON E.INSD_MINO = D.RECD_MINO
									   AND E.INSD_CODE = D.RECD_MATL
									   AND E.INSD_SEQN = D.RECD_PO_SEQN
				LEFT JOIN ".$this->inspecth2." F ON F.INSH_IDXX = E.INSD_IDXX
									   AND F.INSH_PONO = E.INSD_PONO
				WHERE D.RECD_QTTY IS NOT NULL
				AND POMD_PONO = '".$pono."'";
		if($mino != ""){
			$sql.= "AND RECH_MINO = '".$mino."'";
		}
		if ($matcode != ""){
			$sql.= "AND POMD_CODE LIKE '". $matcode ."'";
		}
		$sql.= "GROUP BY POMD_CODE, POMD_SEQN, POMD_PRIC, POMH_UMCD, D.RECD_MINO, RECD_QTTY, POMD_INSP_QTTY, POMD_QTTY, E.INSD_LOTNUMBER, E.INSD_EXP_DATE";
		// print_r($sql);
		return $this->db->query($sql)->result();
	}

	function getMiDetailMaterialGen($pono, $mino, $matcode, $wh){
		$sql = "select POMD_SEQN, POMD_CODE, POMD_QTTY POMD_QTTY, POMD_PRIC, POMH_UMCD, POMD_INSP_QTTY POMD_INSP_QTTY,
					   SUM(POMD_QTTY)-SUM(ISNULL(POMD_INSP_QTTY,0)) BALANCE, D.RECD_QTTY, SUM(ISNULL(E.INSD_IQTY,0)) IQTY, 
					   D.RECD_QTTY - SUM(ISNULL(E.INSD_IQTY,0)) BALANCE2, E.INSD_LOTNUMBER lotnumber, E.INSD_EXP_DATE tgl_expired
				FROM ".$this->pomexd." A
				LEFT JOIN ".$this->pomexh." B ON A.POMD_PONO = B.POMH_PONO
				LEFT JOIN ".$this->recexh." C ON C.RECH_PONO = B.POMH_PONO
				LEFT JOIN ".$this->recexd." D ON D.RECD_MINO = C.RECH_MINO
											 AND D.RECD_MATL = A.POMD_CODE
											 AND D.RECD_PO_SEQN = A.POMD_SEQN
				LEFT JOIN ".$this->inspectd2." E ON E.INSD_MINO = D.RECD_MINO
									   AND E.INSD_CODE = D.RECD_MATL
									   AND E.INSD_SEQN = D.RECD_PO_SEQN
				LEFT JOIN ".$this->inspecth2." F ON F.INSH_IDXX = E.INSD_IDXX
									   AND F.INSH_PONO = E.INSD_PONO
				WHERE D.RECD_QTTY IS NOT NULL
				AND POMD_PONO = '".$pono."'";
		if($wh == "MAT"){
			$sql.= "AND (LEFT(POMD_CODE,1) IN ('M','W','T') OR LEFT(POMD_CODE,4) ='X076')";
		}elseif($wh == "ENG"){
			$sql.= "AND LEFT(POMD_CODE,1) IN ('X')";
		}
		if($mino != ""){
			$sql.= "AND RECH_MINO = '".$mino."'";
		}
		if ($matcode != ""){
			$sql.= "AND POMD_CODE LIKE '". $matcode ."'";
		}
		$sql.= "GROUP BY POMD_CODE, POMD_SEQN, POMD_PRIC, POMH_UMCD, D.RECD_MINO, D.RECD_QTTY, POMD_INSP_QTTY, POMD_QTTY, E.INSD_LOTNUMBER, E.INSD_EXP_DATE";
		return $this->db->query($sql)->result();
	}

	public function getDateFromServer(){
      $this->db->select('CONVERT(VARCHAR(10), GETDATE(), 120) tgl');
      $query=$this->db->get();
      return $query->row();
	}

	public function inspectDataHeader($id){
		$sql = "select A.INSH_IDXX COLLATE Korean_Wansung_CI_AS headerID, B.INSD_PONO headerPONumber, A.INSH_DATE tanggal, 
					   A.INSH_SJNO COLLATE Korean_Wansung_CI_AS sjno, 
			     	   B.INSD_CODE headerMatCode, B.INSD_LINE line, B.INSD_KEMAS kemasan, B.INSD_TKEMAS jmlKem, B.INSD_IQTY qtty, 
			       	   B.INSD_UNIT COLLATE Korean_Wansung_CI_AS unit, B.INSD_SEQN headerSEQN, B.INSD_PRIC harga, B.INSD_UMCD umcd, 
			       	   A.INSH_NBRN release, A.INSH_PART style, A.INSH_WHID wh, INSH_VEND COLLATE Korean_Wansung_CI_AS vend, B.INSD_MINO mino, B.INSD_SJNO COLLATE Korean_Wansung_CI_AS sjno, B.INSD_LOTNUMBER lotnumber, B.INSD_EXP_DATE tgl_expired
				FROM ".$this->inspecth2." A
				LEFT JOIN ".$this->inspectd2." B ON A.INSH_IDXX = B.INSD_IDXX
				WHERE A.INSH_IDXX = '". $id ."'
				UNION ALL
				select A.headerID, A.headerPONumber, B.tanggal, 
				       B.sjno, A.headerMatCode, CONVERT(VARCHAR(255),A.line), 
				       A.kemasan, 0 tKem, a.qtty, A.unit, A.headerSEQN, ISNULL(A.harga,0) harga, '' umcd, ISNULL(A.release,'') release, 
				       ISNULL(A.style,style) style, B.warehouse, B.vendorid, '' mino, b.sjno
				FROM ".$this->inspectd." A
				LEFT JOIN ".$this->inspecth." B ON A.headerID = B.Id
				WHERE headerID = '". $id ."'";
		return $this->db->query($sql)->result();
	}

	public function loadDataVendor($vend = ""){
		$sql = "select VEND_VEND, VEND_DESC
				FROM ".$this->vendor."
				WHERE VEND_VEND != ''  
				AND VEND_VEND LIKE '". $vend ."%'";
			// if($vend != ""){
			// 	$sql.="WHERE VEND_VEND != '". $vend ."'";
			// }
		return $this->db->query($sql)->result();
	}

	public function loadDataModel($model){
		$sql = "select MODD_CODE, MODD_NAME, MODD_GEND
				FROM ".$this->model."
				WHERE MODD_CODE = '". $model ."'";
		return $this->db->query($sql)->result();
	}

	public function getdetailPoVend($barcode, $wh){
		// if($wh == 'MAT'){
		// 	$list = $this->getdetailPoVendCommon($barcode);
		// }else{
		// 	$list = $this->getdetailPoVendGeneral($barcode);
		// }

		$list = $this->getdetailPoVendDet($barcode);

		return $list;
	}

	public function getdetailPoVendDet($barcode){
		// $sql = "select A0.*
		// FROM (
		// 		select B.LineSj, B.LineItem lItem, noSuratJalan, vendor, tanggal, totalSJ, C.FACTORY, A.linebarcode, B.Pono, A.LineItem, B.itemId, B.satuan, A.qtty, B.Kemasan, B.jmlKemasan--, D.POMD_PONO, D.POMD_CODE, D.POMD_SEQN
		// 		FROM ".$this->sjvendordb." A
		// 		LEFT JOIN ".$this->sjvendord." B ON A.LineSj = B.LineSj
		// 													 AND A.LineItem = B.LineItem
		// 		LEFT JOIN ".$this->sjvendor." C ON B.LineSj = C.LineSj
		// 		WHERE A.linebarcode ='". $barcode ."'
		// 		AND ISNULL(B.stat_inspect,0) = 0
		// 		AND C.FACTORY = 'PM'
		// ) A0";

		$sql = "select A0.*
		FROM (
				select B.LineSj, B.LineItem lItem, noSuratJalan, vendor, tanggal, totalSJ, C.FACTORY, A.linebarcode, B.Pono, A.LineItem, B.itemId, B.satuan, A.qtty, B.Kemasan, B.jmlKemasan--, D.POMD_PONO, D.POMD_CODE, D.POMD_SEQN
				FROM ".$this->sjvendordb." A
				LEFT JOIN ".$this->sjvendord." B ON A.LineSj = B.LineSj
															 AND A.LineItem = B.LineItem
				LEFT JOIN ".$this->sjvendor." C ON B.LineSj = C.LineSj
				WHERE A.linebarcode ='". $barcode ."'
				AND C.FACTORY = 'PM'
		) A0";
		return $this->db->query($sql)->result();
	}

	// public function getdetailPoVendGeneral($barcode){
	// 	$sql = "select A0.*
	// 	FROM (
	// 			select noSuratJalan, vendor, tanggal, totalSJ, C.FACTORY, A.linebarcode, B.Pono, A.LineItem, B.itemId, B.satuan, A.qtty, B.Kemasan, B.jmlKemasan--, D.POMD_PONO, D.POMD_CODE, D.POMD_SEQN
	// 			FROM ".$this->sjvendordb." A
	// 			LEFT JOIN ".$this->sjvendord." B ON A.LineSj = B.LineSj
	// 														 AND A.LineItem = B.LineItem
	// 			LEFT JOIN ".$this->sjvendor." C ON B.LineSj = C.LineSj
	// 			WHERE A.linebarcode ='". $barcode ."'
	// 			AND C.FACTORY = 'PM'
	// 	) A0";

	// 	return $this->db->query($sql)->result();
	// }

	public function getSeqnSeqhPo($pono, $matl){
		$sql = "select POMD_SEQH, POMD_SEQN, POMH_PART, POMH_POID, POMD_PRIC, POMH_UMCD
				FROM ".$this->pomxxd." A
				LEFT JOIN ".$this->pomxxh." B ON A.POMD_PONO = B.POMH_PONO
				WHERE POMD_PONO = '". $pono ."'
				AND POMD_CODE = '". $matl ."'
				UNION ALL
				SELECT POMD_SEQH, POMD_SEQN, POMH_PART, POMH_POID, POMD_PRIC, POMH_UMCD
				FROM ".$this->pomexd." A
				LEFT JOIN ".$this->pomexh." B ON A.POMD_PONO = B.POMH_PONO
				WHERE POMD_PONO = '". $pono ."'
				AND POMD_CODE = '". $matl ."'";
		return $this->db->query($sql)->result();
	}

	public function getTotalOutErpMatl($mrno){
		$sql = "select SUM(A0.QTTY) QTTY
				FROM (
						SELECT SUM(ISNULL(TRND_QTTY,0)) QTTY
						FROM ".$this->transd."
						WHERE TRND_MRNO LIKE '". $mrno ."%'
						UNION ALL
						SELECT SUM(ISNULL(TRND_QTTY,0)) QTTY
						FROM ".$this->transd."
						WHERE TRND_LAST LIKE '". $mrno ."%'
					 )A0";
	}

	public function loadDataMR($mrno = "", $dt1= "", $dt2= "", $opcd= "", $fact= "", $wh= "", $tipe= ""){
		if($tipe !='KT' and $tipe !='KT2'){
			$sql = "select A0.MRQH_MRNO, A0.MRQH_REDT, A0.MRQH_IPWX, A0.MRQH_PART, A0.MRQH_OPCD, A0.ROTE_NAME, A0.MRQH_DEST, SUM(A0.QTTY) QTTY, \n
			               SUM(A0.QTTY_APPR) QTTY_APPR, SUM(A0.QTY_OUT) QTY_OUT, (SUM(A0.QTTY_APPR) - SUM(A0.QTY_OUT)) BALANCE, A0.wh, 'MR' STT\n
					FROM (\n
							SELECT A.MRQH_MRNO, A.MRQH_REDT, A.MRQH_IPWX, A.MRQH_PART, A.MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST, SUM(B.MRQD_QTTY) QTTY, \n
								   CASE WHEN SUM(B.MRQD_QTY2) = 0 THEN SUM(B.MRQD_QTTY) ELSE SUM(B.MRQD_QTY2) END QTTY_APPR, ISNULL(D.TRND_QTTY,0) QTY_OUT, '". $wh ."' wh\n
							FROM ".$this->tmmreqxh." A\n
							LEFT JOIN ".$this->tmmreqxd." B ON A.MRQH_MRNO = B.MRQD_MRNO\n
							LEFT JOIN ".$this->routem." C ON C.ROTE_OPCD = A.MRQH_OPCD\n
							LEFT JOIN ".$this->transd." D ON D.TRND_MRNO = B.MRQD_MRNO\n
															  AND D.TRND_CODE = B.MRQD_CODE\n
							WHERE A.MRQH_MRNO LIKE '". $mrno ."%'\n
							AND A.MRQH_STAT = 'C'\n
							AND LEFT(A.MRQH_MRNO ,2) !='KK'\n";
							if($dt1 != '' && $dt2 != ''){
								$sql.= "AND A.MRQH_REDT BETWEEN '". $dt1 ."' AND '". $dt2 ."'\n";
							}
							if($opcd != ''){
								$sql.= "AND A.MRQH_OPCD = '". $opcd ."'\n";
							}
							if($fact != ''){
								$sql.= "AND A.MRQH_DEST = '". $fact ."'\n";
							}
			$sql.= "GROUP BY A.MRQH_MRNO, A.MRQH_REDT, A.MRQH_IPWX, A.MRQH_PART, A.MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST, ISNULL(D.TRND_QTTY,0)\n
					)A0\n
					GROUP BY A0.MRQH_MRNO, A0.MRQH_REDT, A0.MRQH_IPWX, A0.MRQH_PART, A0.MRQH_OPCD, A0.ROTE_NAME, A0.MRQH_DEST, A0.wh \n";
			$sql.= " UNION ALL \n";
			$sql.= "select A0.MRQH_MRNO, A0.MRQH_DATE, A0.MRQH_IPWX, A0.MRQH_PART, A0.MRQH_OPCD, A0.ROTE_NAME, A0.MRQH_DEST, A0.QTTY,\n
			               A0.QTTY_APPR, SUM(D.TRND_QTTY) QTY_OUT, A0.QTTY_APPR - SUM(D.TRND_QTTY) BALANCE, A0.wh, 'MO' STT\n
					FROM (\n
							SELECT A.MRQH_MRNO, A.MRQH_DATE, A.MRQH_NBRN MRQH_IPWX, A.MRQH_PART, A.MRQH_PROC MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST, SUM(B.MRQD_QTTY) QTTY, \n
								   SUM(B.MRQD_QTTY) QTTY_APPR, '". $wh ."' wh\n
							FROM ".$this->tomreqxh." A\n
							LEFT JOIN ".$this->tomreqxd." B ON A.MRQH_MRNO = B.MRQD_MRNO\n
							LEFT JOIN ".$this->routem." C ON C.ROTE_OPCD = A.MRQH_OPCD\n
							WHERE A.MRQH_MRNO LIKE '". $mrno ."%'\n
							AND LEFT(A.MRQH_MRNO ,2) !='KK'\n";
							if($dt1 != '' && $dt2 != ''){
								$sql.= "AND A.MRQH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'\n";
							}
							if($opcd != ''){
								$sql.= "AND A.MRQH_PROC = '". $opcd ."'\n";
							}
							if($fact != ''){
								$sql.= "AND A.MRQH_DEST = '". $fact ."'\n";
							}
			$sql.= "		GROUP BY A.MRQH_MRNO, A.MRQH_DATE, A.MRQH_NBRN, A.MRQH_PART, A.MRQH_PROC, C.ROTE_NAME, A.MRQH_DEST\n
					)A0\n
					LEFT JOIN ".$this->transd." D ON D.TRND_MRNO = A0.MRQH_MRNO
					GROUP BY A0.MRQH_MRNO, A0.MRQH_DATE, A0.MRQH_IPWX, A0.MRQH_PART, A0.MRQH_OPCD, A0.ROTE_NAME, A0.MRQH_DEST, A0.QTTY,
						     A0.QTTY_APPR, A0.wh\n";
		}elseif($tipe =='KT2'){
				$sql = "select A.KRNH_LAST MRQH_MRNO, D.TRNH_DATE MRQH_REDT, A.KRNH_IPWX MRQH_IPWX, A.KRNH_PART MRQH_PART, A.KRNH_OPCD MRQH_OPCD, F.ROTE_NAME, 
							   CASE WHEN A.KRNH_SITE = '0' THEN E.MRQH_DEST ELSE A.KRNH_SITE END MRQH_DEST,  SUM(C.TRND_QTTY) QTTY_APPR, 'MAT' wh, 'MR' STT\n
				FROM PRTMMRP.PRTM.TM_KRNH A
				LEFT JOIN PRTMMRP.PRTM.TM_KRND B ON A.KRNH_MRNO = B.KRND_MRNO
				LEFT JOIN PRTMMRP.PRTM.TM_TRANSD C ON C.TRND_MRNO = B.KRND_MRNO
												  AND C.TRND_CODE = B.KRND_CODE
				LEFT JOIN PRTMMRP.PRTM.TM_TRANSH D ON D.TRNH_MRNO = C.TRND_MRNO
				LEFT JOIN PRTMMRP.PRTM.TM_MREQXH E ON E.MRQH_MRNO = A.KRNH_LASTMR
				LEFT JOIN PRTMERP.PRTM.TP_ROUTEM F ON F.ROTE_OPCD = A.KRNH_OPCD
				WHERE A.KRNH_LAST LIKE '". $mrno ."%'
				AND D.TRNH_MRNO IS NOT NULL\n";
				// --AND LEFT(D.TRNH_MRNO,2) ='KT'\n
				// --AND E.MRQH_STAT = 'C'\n";
				if($dt1 != '' && $dt2 != ''){
					$sql.= "AND D.TRNH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
				}
				if($opcd != ''){
					$sql.= "AND A.KRNH_OPCD = '". $opcd ."'";
				}
				if($fact != ''){
					$sql.= "AND CASE WHEN A.KRNH_SITE = '0' THEN E.MRQH_DEST ELSE A.KRNH_SITE END = '". $fact ."'";
				}
				$sql.= "GROUP BY A.KRNH_LAST, D.TRNH_DATE, A.KRNH_IPWX, A.KRNH_PART, A.KRNH_OPCD, A.KRNH_SITE, E.MRQH_DEST, F.ROTE_NAME";
		}else{
				$sql = "select A.KRNH_MRNO MRQH_MRNO, D.TRNH_DATE MRQH_REDT, A.KRNH_IPWX MRQH_IPWX, A.KRNH_PART MRQH_PART, A.KRNH_OPCD MRQH_OPCD, F.ROTE_NAME, 
							   CASE WHEN A.KRNH_SITE = '0' THEN E.MRQH_DEST ELSE A.KRNH_SITE END MRQH_DEST,  SUM(C.TRND_QTTY) QTTY_APPR, 'MAT' wh, 'MR' STT\n
				FROM PRTMMRP.PRTM.TM_KRNH A
				LEFT JOIN PRTMMRP.PRTM.TM_KRND B ON A.KRNH_MRNO = B.KRND_MRNO
				LEFT JOIN PRTMMRP.PRTM.TM_TRANSD C ON C.TRND_MRNO = B.KRND_MRNO
												  AND C.TRND_CODE = B.KRND_CODE
				LEFT JOIN PRTMMRP.PRTM.TM_TRANSH D ON D.TRNH_MRNO = C.TRND_MRNO
				LEFT JOIN PRTMMRP.PRTM.TM_MREQXH E ON E.MRQH_MRNO = A.KRNH_LASTMR
				LEFT JOIN PRTMERP.PRTM.TP_ROUTEM F ON F.ROTE_OPCD = A.KRNH_OPCD
				WHERE D.TRNH_MRNO LIKE '". $mrno ."%'
				AND LEFT(D.TRNH_MRNO,2) ='KT'
				AND E.MRQH_STAT = 'C'";
				if($dt1 != '' && $dt2 != ''){
					$sql.= "AND D.TRNH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
				}
				if($opcd != ''){
					$sql.= "AND A.KRNH_OPCD = '". $opcd ."'";
				}
				if($fact != ''){
					$sql.= "AND CASE WHEN A.KRNH_SITE = '0' THEN E.MRQH_DEST ELSE A.KRNH_SITE END = '". $fact ."'";
				}
				$sql.= "GROUP BY A.KRNH_MRNO, D.TRNH_DATE, A.KRNH_IPWX, A.KRNH_PART, A.KRNH_OPCD, A.KRNH_SITE, E.MRQH_DEST, F.ROTE_NAME";
		}
		return $this->db->query($sql)->result();
	}

	public function loadDataMRHeader($mrno){
		// $sql = "select tk.KRNH_MRNO, tk.KRNH_DATE, tk.KRNH_IPWX, tk.KRNH_PART, tk.KRNH_SITE, tk.KRNH_OPCD, SUM(td.TRND_QTTY) QTY_ERP
		// 		FROM prtmmrp.PRTM.TM_KRNH tk
		// 		LEFT JOIN prtmmrp.PRTM.TM_TRANSH tt ON tt.TRNH_MRNO = tk.KRNH_MRNO
		// 		LEFT JOIN prtmmrp.PRTM.TM_TRANSD td ON tt.TRNH_MRNO = td.TRND_MRNO
		// 		WHERE tk.KRNH_MRNO IN (". $mrno .")
		// 		AND tt.TRNH_MRNO IS NOT NULL
		// 		GROUP by tk.KRNH_MRNO, tk.KRNH_DATE, tk.KRNH_IPWX, tk.KRNH_PART, tk.KRNH_SITE, tk.KRNH_OPCD";
		
		$sql = "select tm.MRQH_MRNO, tm.MRQH_REDT, tm.MRQH_IPWX, tm.MRQH_PART, tm.MRQH_OPCD, tm.MRQH_DEST, SUM(tm1.MRQD_QTTY) QTTY, SUM(tm1.MRQD_QTY2) QTY2
				FROM PRTMMRP.PRTM.TM_MREQXH tm
				INNER JOIN PRTMMRP.PRTM.TM_MREQXD tm1 ON tm.MRQH_MRNO = tm1.MRQD_MRNO
				WHERE tm.MRQH_STAT = 'C'
				AND tm.MRQH_MRNO IN (". $mrno .")
				GROUP BY tm.MRQH_MRNO, tm.MRQH_REDT, tm.MRQH_IPWX, tm.MRQH_PART, tm.MRQH_OPCD, tm.MRQH_DEST";

		return $this->db->query($sql)->result();
	}

	public function loadDataMRDetAdd($mrno, $matcode, $tipe, $flag = 0){
		$sql = "select B.MRQD_CODE TRND_CODE, SUM(B.MRQD_QTTY) QTTY, ISNULL(A.TRND_QTTY,0) QTY_OUT, SUM(B.MRQD_QTTY) QTTY_APPR
				FROM PRTMMRP.PRTM.TM_MREQXD B
				LEFT JOIN PRTMMRP.PRTM.TM_MREQXH C ON C.MRQH_MRNO = B.MRQD_MRNO
				LEFT JOIN PRTMMRP.PRTM.TM_TRANSD A ON A.TRND_LAST = B.MRQD_MRNO AND A.TRND_CODE = B.MRQD_CODE
				WHERE C.MRQH_MRNO IN (". $mrno .")";
		if($matcode != ''){
			$sql.= "AND B.MRQD_CODE = '". $matcode ."'";
		}		
		$sql.= "GROUP BY B.MRQD_CODE, ISNULL(A.TRND_QTTY,0)";
		return $this->db->query($sql)->result();

	}

	public function loadDataMRDetail($mrno, $matcode, $tipe, $flag = 0){
		$sql = "";
		// $sql = "select B.TRND_CODE, COALESCE(SUM(D.MRQD_QTTY), SUM(E.MRQD_QTTY)) QTTY, ISNULL(B.TRND_QTTY,0) QTY_OUT,
		// 			   COALESCE(CASE WHEN SUM(D.MRQD_QTY2) = 0 THEN SUM(D.MRQD_QTTY) ELSE SUM(D.MRQD_QTY2)END, CASE WHEN SUM(E.MRQD_QTY2) = 0 THEN SUM(E.MRQD_QTTY) ELSE SUM(E.MRQD_QTY2)END) QTTY_APPR
		// 		FROM PRTMMRP.PRTM.TM_TRANSH A
		// 		LEFT JOIN PRTMMRP.PRTM.TM_TRANSD B ON A.TRNH_MRNO = B.TRND_MRNO
		// 		LEFT JOIN PRTMMRP.PRTM.TM_MREQXH C ON C.MRQH_MRNO = A.TRNH_MRNO
		// 		LEFT JOIN PRTMMRP.PRTM.TM_MREQXD D ON D.MRQD_MRNO = B.TRND_MRNO
		// 										  AND D.MRQD_CODE = B.TRND_CODE
		// 		LEFT JOIN PRTMMRP.PRTM.TM_MREQXD E ON E.MRQD_MRNO = B.TRND_LAST
		// 										  AND E.MRQD_CODE = B.TRND_CODE
		// 		WHERE A.TRNH_MRNO = '". $mrno ."'";
		// 	if($matcode != ''){
		// 		$sql.= "AND B.TRND_CODE = '". $matcode ."'";
		// 	}		
		// $sql.= "GROUP BY B.TRND_CODE, ISNULL(B.TRND_QTTY,0)";
		if($tipe=='KK'){
			$sql = "select B.KRND_MRNO, B.KRND_CODE TRND_CODE, SUM(B.KRND_QTTY) QTTY, ISNULL(C.TRND_QTTY,0) QTY_OUT, SUM(B.KRND_QTTY) QTTY_APPR
					FROM PRTMMRP.PRTM.TM_KRNH A
					LEFT JOIN PRTMMRP.PRTM.TM_KRND B ON A.KRNH_MRNO = B.KRND_MRNO
					LEFT JOIN PRTMMRP.PRTM.TM_TRANSD C ON C.TRND_MRNO = B.KRND_MRNO
													  AND C.TRND_CODE = B.KRND_CODE\n";
			if($flag == 0){
				$sql.= "WHERE A.KRNH_MRNO = '". $mrno ."'";	
			}else{
				$sql.= "WHERE A.KRNH_MRNO IN (". $mrno .") ";
			}
			
		 	if($matcode != ''){
		 		$sql.= "AND B.KRND_CODE = '". $matcode ."'";
		 	}
			$sql.= "AND B.KRND_QTTY <> 0
					GROUP BY B.KRND_MRNO, B.KRND_CODE, ISNULL(C.TRND_QTTY,0)
					ORDER BY B.KRND_CODE";
		}elseif($tipe=='KK2'){
			$sql = "select B.KRND_LAST, B.KRND_CODE TRND_CODE, SUM(B.KRND_QTTY) QTTY, SUM(ISNULL(C.TRND_QTTY,0)) QTY_OUT, SUM(B.KRND_QTTY) QTTY_APPR
					FROM PRTMMRP.PRTM.TM_KRNH A
					LEFT JOIN PRTMMRP.PRTM.TM_KRND B ON A.KRNH_MRNO = B.KRND_MRNO
					LEFT JOIN PRTMMRP.PRTM.TM_TRANSD C ON C.TRND_MRNO = B.KRND_MRNO
													  AND C.TRND_CODE = B.KRND_CODE\n";
			if($flag == 0){
				$sql.= "WHERE A.KRNH_LAST = '". $mrno ."'";	
			}else{
				$sql.= "WHERE A.KRNH_LAST IN (". $mrno .") ";
			}
			
		 	if($matcode != ''){
		 		$sql.= "AND B.KRND_CODE = '". $matcode ."'";
		 	}
			$sql.= "AND B.KRND_QTTY <> 0
					GROUP BY B.KRND_LAST, B.KRND_CODE
					ORDER BY B.KRND_CODE";
		}elseif($tipe == 'AP'){
			$sql = "select CASE WHEN MRQAD_CDNW != '' THEN MRQAD_CDNW ELSE MRQAD_CODE END TRND_CODE, SUM(MRQAD_QTTY) QTTY, ISNULL(C.TRND_QTTY,0) QTY_OUT,
						   CASE WHEN SUM(B.MRQAD_QTY2) = 0 THEN SUM(B.MRQAD_QTTY) ELSE SUM(B.MRQAD_QTY2)END QTTY_APPR
					FROM PRTMMRP.PRTM.TM_MREQXAP A
					LEFT JOIN PRTMMRP.PRTM.TM_MREQXAD B ON A.MRQAH_MRNO = B.MRQAD_MRNO
					LEFT JOIN PRTMMRP.PRTM.TM_TRANSD C ON C.TRND_MRNO = B.MRQAD_MRNO
													  AND C.TRND_CODE = B.MRQAD_CODE
					WHERE MRQAH_MRNO ='". $mrno ."'";

		 	if($matcode != ''){
		 		$sql.= "AND CASE WHEN MRQAD_CDNW != '' THEN MRQAD_CDNW ELSE MRQAD_CODE END = '". $matcode ."'";
		 	}
			$sql.= "AND B.MRQAD_QTTY <> 0
					GROUP BY CASE WHEN MRQAD_CDNW != '' THEN MRQAD_CDNW ELSE MRQAD_CODE END, ISNULL(C.TRND_QTTY,0)";
		}elseif($tipe == 'MR'){
			$sql = "select B.TRND_CODE, COALESCE(SUM(D.MRQD_QTTY), SUM(E.MRQD_QTTY)) QTTY, ISNULL(B.TRND_QTTY,0) QTY_OUT,
						   COALESCE(CASE WHEN SUM(D.MRQD_QTY2) = 0 THEN SUM(D.MRQD_QTTY) ELSE SUM(D.MRQD_QTY2)END, CASE WHEN SUM(E.MRQD_QTY2) = 0 THEN SUM(E.MRQD_QTTY) ELSE SUM(E.MRQD_QTY2)END) QTTY_APPR
					FROM PRTMMRP.PRTM.TM_TRANSH A
					LEFT JOIN PRTMMRP.PRTM.TM_TRANSD B ON A.TRNH_MRNO = B.TRND_MRNO
					LEFT JOIN PRTMMRP.PRTM.TM_MREQXH C ON C.MRQH_MRNO = A.TRNH_MRNO
					LEFT JOIN PRTMMRP.PRTM.TM_MREQXD D ON D.MRQD_MRNO = B.TRND_MRNO
													  AND D.MRQD_CODE = B.TRND_CODE
					LEFT JOIN PRTMMRP.PRTM.TM_MREQXD E ON E.MRQD_MRNO = B.TRND_LAST
													  AND E.MRQD_CODE = B.TRND_CODE
					WHERE A.TRNH_MRNO ='". $mrno ."'";
				if($matcode != ''){
					$sql.= "AND B.TRND_CODE = '". $matcode ."'";
				}		
			$sql.= "GROUP BY B.TRND_CODE, ISNULL(B.TRND_QTTY,0)";
		}elseif($tipe == 'MO'){
			$sql = "select B.MRQD_CODE TRND_CODE, SUM(B.MRQD_QTTY) QTTY, ISNULL(A.TRND_QTTY,0) QTY_OUT, SUM(B.MRQD_QTTY) QTTY_APPR
					FROM PRTMMRP.PRTM.TO_MREQXD B
					LEFT JOIN PRTMMRP.PRTM.TO_MREQXH C ON C.MRQH_MRNO = B.MRQD_MRNO
					LEFT JOIN PRTMMRP.PRTM.TM_TRANSD A ON A.TRND_MRNO = B.MRQD_MRNO AND A.TRND_CODE = B.MRQD_CODE
					WHERE C.MRQH_MRNO = '". $mrno ."'";
				if($matcode != ''){
					$sql.= "AND B.MRQD_CODE = '". $matcode ."'";
				}		
			$sql.= "GROUP BY B.MRQD_CODE, ISNULL(A.TRND_QTTY,0)";
		}

		// var_dump($sql);
		return $this->db->query($sql)->result();
	}

	public function getProcessname(){
		$sql = "select ROTE_OPCD, ROTE_NAME FROM ".$this->routem." WHERE ROTE_DIVI = '1' AND ROTE_MRPR != 0 ORDER BY ROTE_NAME";
		return $this->db->query($sql)->result();
	}

	public function loadDataRJ($pono, $rjno, $dt1, $dt2, $fact, $wh){
		$sql = "select A0.RECH_MINO, A0.RECH_PONO, A0.RECH_VEND, A0.RECH_SJNO, A0.RECH_NTRE, A0.RECH_RJNO, A0.RECH_PART, A0.RECH_POID,
			   		   SUM(A0.RECD_QTTY) TOTAL_QTTY, A0.POMH_FACT, A0.RECH_DATE, '". $wh ."' wh
				FROM (
						SELECT A.RECH_MINO, A.RECH_PONO, RECH_VEND, A.RECH_SJNO, A.RECH_NTRE, A.RECH_RJNO, A.RECH_PART, A.RECH_POID,
							   B.RECD_MATL, B.RECD_QTTY, C.POMH_FACT, A.RECH_DATE
						FROM ".$this->recxxh." A
						LEFT JOIN ".$this->recxxd." B ON A.RECH_MINO = B.RECD_MINO
						LEFT JOIN ".$this->pomxxh." C ON C.POMH_PONO = A.RECH_PONO
						WHERE A.RECH_MINO LIKE 'RJ%'";
						if($rjno != ''){
							$sql.="AND A.RECH_MINO = '". $rjno ."'";	
						}
						if($pono != ''){
							$sql.="AND A.RECH_PONO = '". $pono ."'";	
						}
						if($dt1 != '' and $dt2 != ''){
							$sql.="AND A.RECH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";	
						}
						if($fact != ''){
							$sql.="AND C.POMH_FACT LIKE '". $fact ."%'";	
						}
						$sql.="UNION ALL
						select A.RECH_MINO, A.RECH_PONO, RECH_VEND, A.RECH_SJNO, A.RECH_NTRE, A.RECH_RJNO, '' RECH_PART, '' RECH_POID,
							   B.RECD_MATL, B.RECD_QTTY, C.POMH_FACT, A.RECH_DATE
						FROM ".$this->recexh." A
						LEFT JOIN ".$this->recexd." B ON A.RECH_MINO = B.RECD_MINO
						LEFT JOIN ".$this->pomexh." C ON C.POMH_PONO = A.RECH_PONO
						WHERE A.RECH_MINO LIKE 'RJ%'";
						if($rjno != ''){
							$sql.="AND A.RECH_MINO = '". $rjno ."'";	
						}
						if($pono != ''){
							$sql.="AND A.RECH_PONO = '". $pono ."'";	
						}
						if($dt1 != '' and $dt2 != ''){
							$sql.="AND A.RECH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";	
						}
						if($fact != ''){
							$sql.="AND C.POMH_FACT LIKE'". $fact ."%'";	
						}
				$sql.=") A0
				GROUP BY A0.RECH_MINO, A0.RECH_PONO, A0.RECH_VEND, A0.RECH_SJNO, A0.RECH_NTRE, A0.RECH_RJNO, A0.RECH_PART, A0.RECH_POID,
					     A0.POMH_FACT, A0.RECH_DATE";	
		return $this->db->query($sql)->result();
	}

	public function loadDataRJDetail($rjno, $matcode){
		$sql = "select A0.RECD_PONO, A0.RECD_MATL, SUM(A0.RECD_QTTY) * -1 RECD_QTTY	
				FROM(    
						SELECT RECD_PONO, RECD_MATL, RECD_QTTY
						FROM ".$this->recxxd."
						WHERE RECD_MINO LIKE 'RJ%'
						AND RECD_MINO = '". $rjno ."'";
					if($matcode != ''){
						$sql.="AND RECD_MATL = '". $matcode ."'";
					}
				 $sql.="UNION ALL
						SELECT RECD_PONO, RECD_MATL, RECD_QTTY
						FROM ".$this->recexd."
						WHERE RECD_MINO LIKE 'RJ%'
						AND RECD_MINO = '". $rjno ."'";
					if($matcode != ''){
						$sql.="AND RECD_MATL = '". $matcode ."'";
					}
		$sql.=") A0
			   GROUP BY A0.RECD_MATL, A0.RECD_PONO";
		return $this->db->query($sql)->result();
	}

	public function getMenu($id, $module = ''){
		$sql =" select MENU_MODULE, MENU_GROUP, MENU_GRNM, MENU_MENUID, MENU_PGMID, MENU_NAME, MENU_READ, MENU_INQUERY, MENU_INSERT, MENU_UPDATE, MENU_DELETE, MENU_PRINT
				FROM ".$this->mainmenu."
				WHERE MENU_USERID = '". $id ."'";
		if($module != ''){
			$sql .="AND MENU_MODULE IN (". $module .")";
		}
		$sql .="ORDER BY MENU_MODULE, MENU_GROUP, LEN(MENU_GRNM), MENU_GRNM ";
		return $this->db->query($sql)->result();
	}

	public function getModul(){
		$sql =" select CODD_VALU
				FROM ".$this->codexd."
				WHERE CODD_FLNM = 'FMS_MODULE'";
		return $this->db->query($sql)->result();
	}

	public function countMenuId($module, $menuid){
		$cnt = 0;
		$sql ="select COUNT(*) CNT
			   FROM ".$this->mainmenu."
			   WHERE MENU_MODULE ='". $module ."' AND MENU_MENUID = '". $menuid ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

	public function countMenu($module, $menuid, $menupg){
		$cnt = 0;
		$sql ="select COUNT(*) CNT
			   FROM ".$this->mainmenu."
			   WHERE MENU_MODULE ='". $module ."' AND MENU_MENUID = '". $menuid ."' AND MENU_PGMID = '". $menupg ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

	public function getMenuName($module, $menuid, $menupg){
		$name = '';
		$sql ="select MENU_NAME
			   FROM ".$this->mainmenu."
			   WHERE MENU_MODULE ='". $module ."' AND MENU_MENUID = '". $menuid ."' AND MENU_PGMID = '". $menupg ."' GROUP BY MENU_NAME ";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$name = $d->MENU_NAME;
		}
		return $name;
	}

	public function addRoleMenu($module, $menuid, $menupg, $menuname, $menudt, $grno, $mnno){
		$cnt = $this->countMenu($module, $menuid, $menupg);
		$name = $this->getMenuName($module, $menuid, $menupg);

		$data = array(
					'MENU_MODULE'=>$module,
					'MENU_USERID'=>'ADMIN', 
					'MENU_GROUP'=>$grno, 
					'MENU_GRNM'=>$mnno, 
					'MENU_MENUID'=>$menuid, 
					'MENU_PGMID'=>$menupg,
					'MENU_NAME'=>$menuname,
					'MENU_DATE'=>$menudt,
					'MENU_READ'=> '1',
			        'MENU_INQUERY'=> '1',
			        'MENU_INSERT'=> '1',
			        'MENU_UPDATE'=> '1',
			        'MENU_DELETE'=> '1',
			        'MENU_PRINT'=> '1',
			        'MENU_PASS'=> ''
				);
		if($cnt == 0){
			$this->db->set($data);
			$this->db->insert($this->mainmenu);
		}else{
			if($menuname != $name){
				$param=array(
					'MENU_MODULE'=>$module,
					'MENU_MENUID'=>$menuid, 
					'MENU_PGMID'=>$menupg,
				);
				$this->db->where($param);
				$this->db->update($this->mainmenu, $data);
			}
		}

	}

	public function updateInspectPO($list, $wh, $totalBarcode, $pono, $matl, $seqn){

		$param=array('POMD_PONO'=>$pono,'POMD_CODE'=>$matl,'POMD_SEQN'=>$seqn);
		$data2 = array('POMD_INSP_QTTY'=>floatval($totalBarcode));

		if($wh=='MAT'){
			$this->db->where($param);
			$this->db->update($this->pomxxd, $data2);
		}else{
			$this->db->where($param);
			$this->db->update($this->pomexd, $data2);
		}

		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}

		foreach($list as $l){
			$data = array(
					'header_line'=>$l['INSD_LINE'],
					'headerPO'=>$l['INSD_PONO'], 
					'headerSEQH'=>$l['SEQH'], 
					'headerSEQN'=>$l['SEQN'], 
					'header_matl'=>$l['INSD_CODE'] 
				);
			// $totalBarcode = $totalBarcode + $l->INSD_IQTY;

			if($wh=='MAT'){
				$this->db->set($data);
				$this->db->insert($this->pomxxd_inspect2);
			}else{
				$this->db->set($data);
				$this->db->insert($this->pomexd_inspect2);
			}

			$rowsaffected= $this->db->affected_rows();
			if (floatval($rowsaffected) == 0) {
				$msg = 'error';
				return $msg;
			}
		}
	}

	public function cekTranshErp($mrno, $date = '', $stat = 0){
		$cnt = 0;
		$sql =" select COUNT(*) CNT
				FROM PRTMMRP.PRTM.TM_TRANSH";
		if($stat == 0){
			$sql .="\nWHERE TRNH_MRNO = '". $mrno ."'";	
		}else{
			$sql .="\nWHERE TRNH_MRNO IN (". $mrno .")";	
		}
		
		if($date != ''){
			$sql .="AND TRNH_DATE = '". $date ."'";
		}

		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

	public function cekRijectErp($mino, $date = ''){
		$cnt = 0;
		$sql =" select COUNT(*) CNT
				FROM PRTMMRP.PRTM.TM_RECXXH
				WHERE RECH_MINO = '". $mrno ."'";
		if($date != ''){
			$sql .="AND RECH_DATE = '". $date ."'";
		}
		$sql .="UNION ALL";
		$sql .="select COUNT(*) CNT
				FROM PRTMMRP.PRTM.TM_RECEXH
				WHERE RECH_MINO = '". $mrno ."'";
		if($date != ''){
			$sql .="AND RECH_DATE = '". $date ."'";
		}

		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

public function validationCodeErpvBarcode($mrno, $code, $wh, $mrold){
	$status = 0;
	$mr = '';
		if($wh == 'MAT'){
			$mrtype = substr($mrno,0,2);
			if($mrtype == 'RJ'){
				$ttlErp = $this->cekTtlRijectErp($mrno, $code);	
				$ttlErp = $ttlErp * -1;
			}else{
				$ttlErp = $this->totalOutbyTransdErp($mrno, $code);	
			}
			
			$mr = $mrno;
		}else{
			$ttlErp = $this->totalOutbyReqxxdErp($mrno, $code);
			$mr = $mrold;
		}
		
		$ttlBrc = $this->otspectd->totalOutbyMatl2($mr, $code);
		if(floatval($ttlErp) == floatval($ttlBrc)){
			$status = 1;
		}else{
			$status = 0;
		}

		return $status;
	}
	public function cekReqxxhErp($mrno, $date = ''){
		$cnt = 0;
		$sql =" select COUNT(*) CNT
				FROM PRTMENG.PRTM.TG_REQXXH
				WHERE REQH_NMBR = '". $mrno ."'";

		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

	public function cekTransdErp($mrno, $code){
		$cnt = 0;
		$sql =" select COUNT(*) CNT
				FROM ".$this->transd."
				WHERE TRND_MRNO = '". $mrno ."'
				AND TRND_CODE = '". $code ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

	public function totalOutbyTransdErp($mrno,$code){
		$ttl = 0;
		$sql="select TRND_MRNO, TRND_CODE, SUM(TRND_QTTY) OQTY
			  FROM ".$this->transd."
			  WHERE TRND_MRNO = '".$mrno."'";
			  if($code != ''){
			  	$sql.="AND TRND_CODE = '".$code."'";	
			  }
		$sql.="GROUP BY TRND_CODE, TRND_MRNO";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$ttl = $d->OQTY;	
		}
		return $ttl;
	}

	public function totalOutbyReqxxdErp($mrno,$code){
		$ttl = 0;
		$sql="select REQD_NMBR, REQD_MATL, SUM(REQD_QTTY) OQTY
			  FROM PRTMENG.PRTM.TG_REQXXD
			  WHERE REQD_NMBR = '".$mrno."'";
			  if($code != ''){
			  	$sql.="AND REQD_MATL = '".$code."'";	
			  }
		$sql.="GROUP BY REQD_MATL, REQD_NMBR";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$ttl = $d->OQTY;	
		}
		return $ttl;
	}

	public function cekReqxxdErp($mrno, $code){
		$cnt = 0;
		$sql =" select COUNT(*) CNT
				FROM PRTMENG.PRTM.TG_REQXXD
				WHERE REQD_NMBR = '". $mrno ."'
				AND REQD_MATL = '". $code ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

	public function cekTtlTransdErp($mrno, $code){
		$qtty = 0;
		$sql =" select SUM(TRND_QTTY) QTY
				FROM PRTMMRP.PRTM.TM_TRANSD
				WHERE TRND_MRNO = '". $mrno ."'
				AND TRND_CODE = '". $code ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$qtty = $d->QTY;	
		}
		return $qtty;
	}

	public function getMultipleMRKK($tmno){
		$sql = "select KRND_MRNO, KRND_CODE FROM PRTMMRP.PRTM.TM_KRND WHERE KRND_LAST = '". $tmno ."' ORDER BY KRND_MRNO";
		return $this->db->query($sql)->result();
	}

	public function getMultipleMRKK2($tmno){
		$sql = "select KRNH_MRNO FROM PRTMMRP.PRTM.TM_KRNH WHERE KRNH_LAST = '". $tmno ."' ORDER BY KRNH_MRNO";
		return $this->db->query($sql)->result();
	}

	public function cekTtlTransdErpIn($mrno, $code){
		$qtty = 0;
		$sql =" select SUM(TRND_QTTY) QTY
				FROM PRTMMRP.PRTM.TM_TRANSD
				WHERE TRND_MRNO = '". $mrno ."'
				AND TRND_CODE = '". $code ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$qtty = $d->QTY;	
		}
		return $qtty;
	}

	public function cekTtlRijectErp($mino, $code){
		$cnt = 0;
		$sql =" select rech_mino, recd_matl, sum(recd_qtty) OQTY
				FROM PRTMMRP.PRTM.TM_RECXXH
				WHERE RECH_MINO = '". $mrno ."'";
		if($code != ''){
			$sql .="AND recd_matl = '". $code ."'";
		}
		$sql.="GROUP BY recd_matl, rech_mino";
		$sql .="UNION ALL";
		$sql .="select rech_mino, recd_matl, sum(recd_qtty) OQTY
				FROM PRTMMRP.PRTM.TM_RECEXH
				WHERE RECH_MINO = '". $mrno ."'";
		if($code != ''){
			$sql .="AND recd_matl = '". $code ."'";
		}
		$sql.="GROUP BY recd_matl, rech_mino";

		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->OQTY;	
		}
		return $cnt;
	}

	public function cekTtlReqxxdErp($mrno, $code){
		$qtty = 0;
		$sql =" select SUM(REQD_QTTY) QTY
				FROM PRTMENG.PRTM.TG_REQXXD
				WHERE REQD_NMBR = '". $mrno ."'
				AND REQD_MATL = '". $code ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$qtty = $d->QTY;	
		}
		return $qtty;
	}

	public function saveTranshErp($mParam, $mrno, $list, $ck, $Ttlqtty){
		$part = '';
		$data = array();
		if($ck == 0){
			foreach($mParam as $ls){
				$part = $ls['part'];
				$data = array(
							'TRNH_MRNO'=>$ls['mrno'],
							'TRNH_DATE'=>$ls['tgl'], 
							'TRNH_OPCD'=>$ls['opcd'], 
							'TRNH_SITE'=>'0', 
							'TRNH_LINE'=>'', 
							'TRNH_IPWX'=>$ls['nbrn'],
							'TRNH_PART'=>$ls['part'],
							'TRNH_COST'=>'',
							'TRNH_RMKS'=> '',
					        'TRNH_STAT'=> 'R',
					        'TRNH_WHXX'=> $ls['wh'],
					        'TRNH_TYPE'=> '',
					        'TRNH_DIVI'=> '',
					        'TRNH_USER'=> $ls['user']
						);

				$this->db->set($data);
				$this->db->insert($this->transh);
				$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
				    return $msg;
				}
			}
		}

		foreach($mParam as $ls){
			if(!empty($ls['part'])){
				$part = $ls['part'];
			} 
		}

		$td = $this->master->saveTransdErp($mParam, $mrno, $part, $list, $Ttlqtty);
		return $td;
	}

	public function saveTransdErp($mParam, $mrno, $part, $list, $Ttlqtty){
	$stat = 0;
	$out = 0;
	$ttlOut = 0;
		foreach($list as $lt){
			$stat = $this->cekTransdErp($mrno, $lt->INSD_CODE);
		}	

		if($stat == 0){
			foreach($list as $lt2){
				$data = array(
						'TRND_MRNO'=>$mrno,
						'TRND_CODE'=>$lt2->INSD_CODE, 
						'TRND_SIZE'=>'', 
						'TRND_LINE'=>'', 
						'TRND_QTTY'=>floatval($lt2->INSD_BQTY), 
						'TRND_RMKS'=>'',
						'TRND_UNIT'=>$lt2->INSD_UNIT,
						'TRND_PART'=>$part,
						'TRND_CDAL'=> '',
				        'REAL_QTTY'=> '0'
					);

			$this->db->set($data);
			$this->db->insert($this->transd);
			$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
				    return $msg;
				}
			}
		}else{
			foreach($list as $lt2){
				// $out = $this->cekTtlTransdErp($mrno, $lt2->INSD_CODE);
				// $ttlOut = floatval($out) + floatval($lt2->INSD_BQTY);
				$out = $this->otspectd->totalOutbyMatl2($mrno, $lt2->INSD_CODE);
				$ttlOut = $out;

				$param=array('TRND_MRNO'=>$mrno,'TRND_CODE'=>$lt2->INSD_CODE);
				$data2 = array('TRND_QTTY'=>floatval($ttlOut));

				$this->db->where($param);
				$this->db->update($this->transd, $data2);
				$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
				    return $msg;
				}
			}
		}
		return $out;
	}

	public function deleteTranshBymr($mrno){
		$details= array('TRNH_MRNO'=>$mrno);
		$this->db->where($details);
		$this->db->delete($this->transh);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function deleteTransdBymr($mrno){
		$details= array('TRND_MRNO'=>$mrno);
		$this->db->where($details);
		$this->db->delete($this->transd);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function deleteREQHBymr($mrno){
		$details= array('REQH_NMBR'=>$mrno);
		$this->db->where($details);
		$this->db->delete('PRTMENG.PRTM.TG_REQXXH');
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function deleteREQDBymr($mrno){
		$details= array('REQD_NMBR'=>$mrno);
		$this->db->where($details);
		$this->db->delete('PRTMENG.PRTM.TG_REQXXD');
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			return $msg;
		}
	}

	public function cekStokDate($mrno){
		$cnt = 0;
		$sql = "select  COUNT(TT.TRNH_MRNO) CEK
				FROM	PRTMMRP.prtm.TM_TRANSH AS TT
				WHERE	TT.TRNH_MRNO='". $mrno ."'
				AND		EXISTS( SELECT NULL 
								FROM	PRTMMRP.prtm.TM_STOCKM AS TS
								WHERE	TS.STOK_DATE=REPLACE(CONVERT(VARCHAR(7),TT.TRNH_DATE,120),'-','')
								AND	TS.STOK_OPCD=tt.TRNH_WHXX
										  )";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CEK;	
		}
		return $cnt;
	}

	public function cekClosingErp($trandt, $flag){
		$cnt=0;
		$sql = "EXEC PRTMOSM.PRTM.USP_SCRUD_CLOSING_DATE
			    @P_CLOSE_DT_PROSES = '". $flag ."' , -- varchar(10)
			    @P_CLOSE_DT_DATE = '". $trandt ."' , -- char(10)
			    @P_CLOSE_DT_FLAG = 0 , -- tinyint
			    @P_PERIODE = '". substr($trandt, 0,4) ."' , -- char(4)
			    @P_FLAG = 'Q' -- varchar(2)";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			if ($d->CLOSE_DT_FLAG === null) {
			    $cnt = 0;
			}else{
			    $cnt = $d->CLOSE_DT_FLAG;
			}
		}

		return $cnt;
	}

	public function cekClosingErpEng($trandt, $flag){

	$originalDate = $trandt;
	$newDate = date("Ym", strtotime($originalDate));

		$cnt=0;
		$sql = "select COUNT(*) CNT
				FROM PRTMENG.PRTM.TG_STOCKM_IR2PM tsip
				WHERE tsip.STOK_PERD = '". $newDate ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			if ($d->CNT === null) {
			    $cnt = 0;
			}else{
			    $cnt = $d->CNT;
			}
		}


		return $cnt;
	}

	public function countClosingdate($dt1, $flag){
		$cnt=0;
		$sql = "select COUNT(*) CNT
				FROM ". $this->closedt ."
				WHERE CLOSE_DATE = '". $dt1 ."'
				AND CLOSE_PROC = '". $flag ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;		
		}

		return $cnt;
	}

	public function cekStatClosingBydate($trandt, $flag){
		$cnt=0;
		$sql = "select CLOSE_PROC, CLOSE_DATE, CLOSE_FLAG
				FROM ". $this->closedt ."
				WHERE CLOSE_DATE = '". $trandt ."'
				AND CLOSE_PROC = '". $flag ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			if(is_null($d->CLOSE_FLAG)== 1){
				$cnt = 0;		
			}else{
				$cnt = $d->CLOSE_FLAG;
			}
			
		}
		return $cnt;
	}

	public function cekClosingBydate($trandt, $flag){
		$cnt=0;
		$sql = "select CLOSE_PROC, CLOSE_DATE, CLOSE_FLAG
				FROM ". $this->closedt ."
				WHERE CLOSE_DATE = '". $trandt ."'
				AND CLOSE_PROC = '". $flag ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CLOSE_FLAG;
		}
		return $cnt;
	}

	public function cekClosing($flag, $dt = ''){
		$cnt=0;
		$sql = "select CLOSE_PROC, CLOSE_DATE, CLOSE_FLAG
				FROM ". $this->closedt ."
				WHERE CLOSE_PROC = '". $flag ."'
				AND   CLOSE_FLAG = 1";
		if($dt != ''){
			$sql.= "AND   CLOSE_DATE = '". $dt ."'";
		}
		return $this->db->query($sql)->result();
	}

	public function simpanClosingDate($list){
	$stat = 0;
	$flag = '';
		foreach($list as $lt){
			$flag = $lt['flag'];
			$dt = $this->countClosingdate($lt['tgl'], $flag);
			if($dt == 0){
				foreach($list as $lt2){
					$data = array(
							'CLOSE_PROC'=>$lt['flag'],
							'CLOSE_DATE'=>$lt['tgl'], 
							'CLOSE_FLAG'=>$lt['stat']
						);

				$this->db->set($data);
				$this->db->insert($this->closedt);
				}
			}else{
				$stat = $this->cekStatClosingBydate($lt['tgl'], $lt['flag']);
				$param=array('CLOSE_DATE'=>$lt['tgl'],'CLOSE_PROC'=>$lt['flag']);

				if($stat == 0){
					$data2 = array('CLOSE_FLAG'=>floatval(1));
				}else{
					$data2 = array('CLOSE_FLAG'=>floatval(0));
				}

				$this->db->where($param);
				$this->db->update($this->closedt, $data2);
			}
			
		}
		return $flag;
	}

	public function SimpansuratJalanVendorDetail($barcode, $pono, $matl, $linesj, $lineitem, $val){

		$param=array('linebarcode' => $barcode,'LineSj'=>$linesj,'LineItem'=>$lineitem);
		$data2 = array('lineBarcodeScan'=>floatval($val));
		$this->db->where($param);
		$this->db->update($this->sjvendordb, $data2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			 return $msg;
		}

		$ttl_scan = $this->cekTotalScanVend($linesj, $lineitem, $matl, $pono);
		if($val == 0){
			$ttl_scan = $ttl_scan - 1;	
		}else {
			$ttl_scan = $ttl_scan + 1;	
		}
		

		$param=array('Pono'=>$pono,'itemId'=>$matl,'LineSj'=>$linesj,'LineItem'=>$lineitem);
		$data2 = array('totalScan'=>floatval($ttl_scan));
		$this->db->where($param);
		$this->db->update($this->sjvendord, $data2);
		$rowsaffected= $this->db->affected_rows();
		if (floatval($rowsaffected) == 0) {
			$msg = 'error';
			 return $msg;
		}

	}

	public function cekTotalScanVend($linesj, $lineitem, $matl, $pono){
	$ttl_scan = 0;
		$sql = "select a.jmlKemasan, ISNULL(a.totalScan,0) ttl_scan, SUM(ISNULL(b.lineBarcodeScan,0)) scan
				FROM ITERP.PRTM.suratJalanVendorDetail a
				LEFT JOIN ITERP.PRTM.suratJalanVendorDetailBarcode b ON a.LineSj = b.LineSj AND a.LineItem = b.LineItem
				WHERE a.Pono = '". $pono ."'
				AND a.LineSj = '". $linesj ."'
				AND a.LineItem = '". $lineitem ."'
				AND a.itemId = '". $matl ."'
				GROUP BY ISNULL(a.totalScan,0), a.jmlKemasan";
			$dt = $this->db->query($sql)->result();
				foreach($dt as $d){
					$ttl_scan = $d->ttl_scan;
				}
		return $ttl_scan;
	}

	// public function listPoOldInspect(){
	// 	$sql =" select b0.headerPONumber--, b0.headerMatCode, b0.qtty, b0.qty_vend
	// 			FROM (
	// 					SELECT a0.headerPONumber, a0.headerMatCode, SUM(a0.qtty) qtty, SUM(a0.qty_vend) qty_vend, SUM(a0.qtty) - SUM(a0.qty_vend) bal
	// 					FROM (
	// 							SELECT headerPONumber, headerMatCode, SUM(qtty) qtty, 0 qty_vend
	// 							FROM ".$this->inspectd."
	// 							GROUP BY headerPONumber, headerMatCode
	// 							UNION ALL
	// 							SELECT Pono, itemId, 0, SUM(Qtty) qtty
	// 							FROM ".$this->sjvendord."
	// 							GROUP BY Pono, itemId
	// 						)a0
	// 					GROUP BY a0.headerPONumber, a0.headerMatCode
	// 					HAVING SUM(a0.qty_vend) > 0
	// 				 )b0
	// 			WHERE b0.qtty <> 0
	// 			AND b0.bal = 0";
	// 	return $this->db->query($sql)->result();
	// }

	public function cekStatusApprv($mrno){
	$flag = '';	

		if(substr($mrno, 0,2) == 'KT'){
			$sql = "select COUNT(*) CNT, 'KK' STAT
					FROM PRTMMRP.PRTM.TM_KRNH
					WHERE KRNH_MRNO = '". $mrno ."'";
			$dt = $this->db->query($sql)->result();
			foreach($dt as $d){
				$flag = $d->STAT;
			}
		}elseif(substr($mrno, 0,2) == 'MR' or substr($mrno, 0,2) == 'IZ' or substr($mrno, 0,2) == 'CM'){
			$sql = "select a0.*
					FROM (
							SELECT COUNT(*) CNT, 'AP' STAT
							FROM PRTMMRP.PRTM.TM_MREQXAP
							WHERE MRQAH_MRNO = '". $mrno ."'
						 )a0
					WHERE a0.CNT > 0";
			$dt = $this->db->query($sql)->result();
			foreach($dt as $d){
				$flag = $d->STAT;
			}
		}elseif(substr($mrno, 0,2) == 'MO'){
			$sql = "select a0.*
					FROM (
							SELECT COUNT(*) CNT, 'MO' STAT
							FROM PRTMMRP.PRTM.TO_MREQXH
							WHERE MRQH_MRNO = '". $mrno ."'
						 )a0
					WHERE a0.CNT > 0";
			$dt = $this->db->query($sql)->result();
			foreach($dt as $d){
				$flag = $d->STAT;
			}
		}elseif(substr($mrno, 0,2) == 'TM'){
			$sql = "select COUNT(*) CNT, 'KK2' STAT
					FROM PRTMMRP.PRTM.TM_KRNH
					WHERE KRNH_LAST = '". $mrno ."'";
			$dt = $this->db->query($sql)->result();
			foreach($dt as $d){
				$flag = $d->STAT;
			}
		}
		return $flag;
	}

	public function cekStatusApprvIn($mrno, $tipe){
	$flag = '';

		if($tipe == 'KT'){
			$sql = "select COUNT(*) CNT, 'KK' STAT
					FROM PRTMMRP.PRTM.TM_KRNH
					WHERE KRNH_MRNO IN (". $mrno .")";
			$dt = $this->db->query($sql)->result();
			foreach($dt as $d){
				$flag = $d->STAT;
				if($flag == ''){
					$flag = '';
					return;
				}
			}
		}elseif($tipe == 'MR'){
			$sql = "select a0.*
					FROM (
							SELECT COUNT(*) CNT, 'AP' STAT
							FROM PRTMMRP.PRTM.TM_MREQXAP
							WHERE MRQAH_MRNO IN (". $mrno .")
						 )a0
					WHERE a0.CNT > 0";
			$dt = $this->db->query($sql)->result();
			foreach($dt as $d){
				$flag = $d->STAT;
				if($flag == ''){
					$flag = '';
					return;
				}
			}
		}elseif($tipe == 'MO'){
			$sql = "select a0.*
					FROM (
							SELECT COUNT(*) CNT, 'MO' STAT
							FROM PRTMMRP.PRTM.TO_MREQXH
							WHERE MRQH_MRNO IN (". $mrno .")
						 )a0
					WHERE a0.CNT > 0";
			$dt = $this->db->query($sql)->result();
			foreach($dt as $d){
				$flag = $d->STAT;
				if($flag == ''){
					$flag = '';
					return;
				}
			}
		}
		return $flag;
	}

	public function cekFactPO($pono){
	$fact = '';
			$sql = "select A0.POMH_FACT
					FROM(
							SELECT POMH_FACT
							FROM prtmmrp.PRTM.TO_POMXXH
							WHERE POMH_PONO = '". $pono ."'
							UNION ALL
							SELECT POMH_FACT
							FROM prtmmrp.PRTM.TO_POMEXH
							WHERE POMH_PONO = '". $pono ."'
						) A0";
			$dt = $this->db->query($sql)->result();
				foreach($dt as $d){
					$fact = $d->POMH_FACT;
				}
		return $flag;
	}

	public function loadOldInspectH($id){
		$sql = "select A.headerID, A.headerPONumber, B.tanggal, 
				       B.sjno, A.headerMatCode, CONVERT(VARCHAR(255),A.line), 
				       A.kemasan, 0 tKem, a.qtty, A.unit, A.headerSEQN, ISNULL(A.harga,0) harga, '' umcd, ISNULL(A.release,'') release, 
				       ISNULL(A.style,style) style, B.warehouse, B.vendorid, '' mino, b.sjno
				FROM ".$this->inspectd." A
				LEFT JOIN ".$this->inspecth." B ON A.headerID = B.Id
				WHERE headerID = '". $id ."'
				AND a.qtty > 0";
	}

	public function getDataPOAperture($pono){
		$sql = "select POMD_PONO, oiaItem, POMD_QTTY, B.POMH_DUDT, A.POMD_DESC
				FROM PRTMMRP.PRTM.TO_POMEXD A
				LEFT JOIN PRTMMRP.PRTM.TO_POMEXH B ON A.POMD_PONO = B.POMH_PONO
				WHERE A.POMD_PONO = '". $pono ."'";
		return $this->db->query($sql)->result();
	}

	public function getHeaderDataMO($mono){
		$sql = "select A.MRQH_OPCD, A.MRQH_EMPL, A.MRQH_TITLE, A.MRQH_DEST, A.MRQH_CTGR, A.MRQH_CTGR3, A.MRQH_DEST
				FROM PRTMMRP.PRTM.TO_MREQXH A
				WHERE A.MRQH_MRNO = '". $mono ."'";
		return $this->db->query($sql)->result();
	}

	public function getDetailDataMO($mono, $kode){
		$sql = "select A.MRQD_CODE, A.MRQD_SEQN, A.MRQD_RMKS, B.MRQH_CTGR, B.MRQH_CTGR3
				FROM PRTMMRP.PRTM.TO_MREQXD A
				INNER JOIN PRTMMRP.PRTM.TO_MREQXH B ON A.MRQD_MRNO = B.MRQH_MRNO
				WHERE A.MRQD_MRNO = '". $mono ."'
				AND A.MRQD_CODE = '". $kode ."'";
		return $this->db->query($sql)->result();
	}

	public function saveReqxxhErp($mParam, $mono, $data, $tgl){
		$date = new DateTime($tgl);
		$tgl = date_format($date, 'ym');
		$cnt = $this->cekMrNumber($tgl);
		$mrno2 = '';
		$mrno2 = $this->cekMrNumberExist($mono);

			foreach ($mParam as $ls) {
				if($mrno2 == ''){
					$this->saveAutoMrNumber($cnt,$tgl);
		
					$ls_no = $this->getLastMrNumber($tgl);
					if($ls_no > 999){
						$mrno2 = 'MR'.$tgl.'-'.floatval($ls_no);
					}else{
						$mrno2 = 'MR'.$tgl.'-'.sprintf("%04s", $ls_no);
					}

					$data2 = array(
							'REQH_NMBR' => $mrno2, 
							'REQH_DATE' => $ls['tgl'], 
							'REQH_COMP' => 'S004', 
							'REQH_DEPT' => $ls['dept'], 
							'REQH_USID' => $ls['user'], 
							'REQH_KARY' => $ls['user2'], 
							'REQH_RMKS' => $ls['rmks'], 
							'REQH_MONO' => $ls['mrno'],
							'REQH_DEST' => $ls['fact']
							);

					$this->db->set($data2);
					$this->db->insert('PRTMENG.PRTM.TG_REQXXH');
					$rowsaffected= $this->db->affected_rows();
					if (floatval($rowsaffected) == 0) {
						$msg = 'error';
					    return $msg;
					}
				}else{
					$data2 = array(
							'REQH_DATE' => $ls['tgl'], 
							'REQH_COMP' => 'S004', 
							'REQH_DEPT' => $ls['dept'], 
							'REQH_USID' => $ls['user'], 
							'REQH_KARY' => $ls['user2'], 
							'REQH_RMKS' => $ls['rmks'], 
							'REQH_DEST' => $ls['fact']
							);
					$param = array(
								'REQH_NMBR' => $mrno2
							);
					$this->db->where($param);
					$this->db->update('PRTMENG.PRTM.TG_REQXXH', $data2);
					$rowsaffected= $this->db->affected_rows();
					if (floatval($rowsaffected) == 0) {
						$msg = 'error';
					    return $msg;
					}
				}
			}
			$this->saveReqxxdErp($mParam, $mrno2, $mono, $data);
			return $mrno2;
	}

	public function saveReqxxdErp($mParam, $mrno, $mono, $list){
	$stat = 0;
	$out = 0;
	$ttlOut = 0;
		foreach($list as $lt){
			$stat = $this->cekReqxxdErp($mrno, $lt->INSD_CODE);
		}	

		if($stat == 0){
			foreach($list as $lt2){
				$l0 = $this->getDetailDataMO($mono, $lt->INSD_CODE);
				foreach($l0 as $v){
					$data = array(
							'REQD_NMBR'=>$mrno,
							'REQD_MATL'=>$lt2->INSD_CODE, 
							'REQD_SEQN'=>floatval($v->MRQD_SEQN), 
							'REQD_QTTY'=>floatval($lt2->INSD_BQTY), 
							'REQD_RMKS'=>$v->MRQD_RMKS, 
							'REQD_SPRT'=>$v->MRQH_CTGR,
							'REQD_SPR2'=>$v->MRQH_CTGR3
						);

				$this->db->set($data);
				$this->db->insert('PRTMENG.PRTM.TG_REQXXD');
				$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
				    return $msg;
				}
				}
			}
		}else{
			foreach($list as $lt2){
				$out = $this->cekTtlReqxxdErp($mrno, $lt2->INSD_CODE);
				$ttlOut = floatval($out) + floatval($lt2->INSD_BQTY);

				$param=array('REQD_NMBR'=>$mrno,'REQD_MATL'=>$lt2->INSD_CODE);
				$data2 = array('REQD_QTTY'=>floatval($ttlOut));
				
				$this->db->where($param);
				$this->db->update('PRTMENG.PRTM.TG_REQXXD', $data2);
				$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
				    return $msg;
				}
			}
		}

		return $out;
	}

	public function cekMrNumber($dt){
	$icnt = 0;

		$sql ="select COUNT(*) ICOUNT FROM PRTMENG.PRTM.TG_AUTOXM WHERE AUTO_DIVI = 'MR' AND AUTO_DATE = '". $dt ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$icnt = $d->ICOUNT;
		}

		return $icnt;
	}

	public function getLastMrNumber($dt){
	$last_no = 0;
		$sql ="select AUTO_INCR FROM PRTMENG.PRTM.TG_AUTOXM WHERE AUTO_DIVI = 'MR' AND AUTO_DATE = '". $dt ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$last_no = $d->AUTO_INCR;
		}

		return $last_no;
	}

	public function saveAutoMrNumber($cnt, $dt){
		if($cnt == 0){
			$data = array(
							'AUTO_DIVI'=>'MR',
							'AUTO_DATE'=>$dt, 
							'AUTO_INCR'=>1
						);

			$this->db->set($data);
			$this->db->insert('PRTMENG.PRTM.TG_AUTOXM');
		}else{
			$ls_no = $this->getLastMrNumber($dt);
			$ls_no = $ls_no + 1;
			$data = array(
							'AUTO_INCR'=>$ls_no
						);
			$param = array(
							'AUTO_DIVI'=>'MR',
							'AUTO_DATE'=>$dt
						);
			$this->db->where($param);
			$this->db->update('PRTMENG.PRTM.TG_AUTOXM', $data);
		}
	}

	public function cekMrNumberExist($mrno){
	$icnt = '';

		$sql ="select REQH_NMBR FROM PRTMENG.PRTM.TG_REQXXH WHERE REQH_MONO = '". $mrno ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$icnt = $d->REQH_NMBR;
		}

		return $icnt;
	}

	public function checkExistIDInspecthDetail($idxx){
	$icnt = '';

		$sql ="select count(*) cnt FROM ". $this->inshdetail ." WHERE MOSD_IDXX = '". $idxx ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$icnt = $d->cnt;
		}

		return $icnt;
	}

	public function saveInspecthDetail($id, $mParam){
		$part = '';
		$data = array();
		$ck = $this->checkExistIDInspecthDetail($id);
		if($ck == 0){
			foreach($mParam as $ls){
				$data = array(
							'MOSD_IDXX'=>$id,
							'MOSD_MRNO'=>$ls['mrno'], 
							'MOSD_WHID'=>$ls['wh'], 
							'MOSD_NBRN'=>$ls['rls'], 
							'MOSD_PART'=>$ls['style'], 
							'MOSD_OPCD'=>$ls['opcd'], 
							'MOSD_FACT'=>$ls['fact'],
							'MOSD_TQTY'=>$ls['qtty']
				);
				$this->db->set($data);
				$this->db->insert($this->inshdetail);
				$rowsaffected= $this->db->affected_rows();
				if (floatval($rowsaffected) == 0) {
					$msg = 'error';
				}else{
					$msg = 'ok';		
				}
			}
		}else{
			$msg = 'ok';
		}
		return $msg;
	}
}

/* End of file Master_model.php */
/* Location: ./application/models/Master_model.php */