<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Inspection_master.php");

class Master_model extends Inspection_master {

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
	

	public function getWarehouserack(){

		return $this->db->query("select LOCATION_ROW, LOCATION_TYPE FROM ". $this->location ." 
								 WHERE LOCATION_TYPE IN ('R','L') GROUP BY LOCATION_ROW, LOCATION_TYPE
								 ORDER BY LOCATION_ROW")->result();
	}

	public function getHeadTransBarcode($barcode, $stat){
		if($stat == 0){
			if(substr($barcode,0,1) == 'B'){
			$sql = "select INSD_LINE line, INSD_CODE mat_code, INSD_IQTY qtyakhir, INSD_NAME matname
					FROM ".$this->inspectd2."
					WHERE INSD_LINE = '".$barcode."'";
			}else{
			$sql = "select CONVERT(VARCHAR(255),a.line) line, a.headerMatCode COLLATE Korean_Wansung_CI_AS mat_code,  a.qtyakhir, '' matname
					FROM ".$this->inspectd." A
					WHERE CONVERT(VARCHAR(255),a.line) = '".$barcode."'";
			}
		}else{
			$sql = "select CONVERT(VARCHAR(255),INSD_LIN2) line, INSD_CODE mat_code, INSD_IQTY qtyakhir, INSD_NAME matname
					FROM ".$this->inspectd2."
					WHERE CONVERT(VARCHAR(255),INSD_LIN2) = '".$barcode."'";
		}


		return $this->db->query($sql)->result();
	}

	public function getTransactionBarcode($barcode){

		$sql = "select CONVERT(VARCHAR(200),a.line) line, b.Id id_in, a.headerMatCode mat_code, b.tanggal tgl_in, b.sjno, a.headerPONumber pono, a.qtty qty_in, 
					   C.headerID id_out, D.tanggal tgl_out, C.header_MR_NO mr_no, C.Qtty qty_out, a.qtyakhir
				FROM ".$this->inspectd." A
				LEFT JOIN ".$this->inspecth." B ON B.ID = A.headerID
				LEFT JOIN ".$this->outbarcoded." C ON C.InspectionD_line = A.line
				LEFT JOIN ".$this->outbarcodeh." D ON D.id = C.headerID
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
				WHERE a.INSD_LINE = '".$barcode."'";

		return $this->db->query($sql)->result();
	}

	public function get_wh_by_id($id){

		return $this->db->query("select A.ROTE_OPCD, A.ROTE_NAME
								 FROM ".$this->routem." A
								 LEFT JOIN ".$this->whakses." B ON B.OPCD = A.ROTE_OPCD
								 WHERE B.OPCD IS NOT NULL
								 AND A.ROTE_OPCD LIKE '".$id."'
								 GROUP BY A.ROTE_OPCD, A.ROTE_NAME")->row();
	}

	public function getNamaMaterial($kode){

		$sql = "select  a0.* 
			    , COALESCE(b0.PARD_NME1,G.PARD_NME1) AS mat_name
			    ,C.PARD_NME1 AS tipe_name
			    ,D.PARD_NME1 AS wide_name
			    ,COALESCE(e.PARD_NME1,H.PARD_NME1) AS spec_name
			    ,f.CODD_DESC AS colo_name
			    ,COALESCE(b0.PARD_UNIT,g.PARD_UNIT) AS mat_unit, COALESCE(F2.PARH_DIVI,'C') as PARH_DIVI,f2.PARH_NAME as groupName
			    ,F3.PARM_MCSN AS PARM_MCSN, coalesce(B0.PARD_ACCX,G.PARD_ACCX) AS PARD_ACCX, F2.PARH_HSNO, f.CODD_DATA1 AS colo_remark
			from  ( SELECT '".$kode."' AS kode)  as a0
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

		return $this->db->query($sql)->result();
	}

	public function poDetail($pono, $dt1, $dt2, $vend, $fact, $wh){
		if($wh == 'MAT'){
			$listPo = $this->poDetailCommon($pono, $dt1, $dt2, $vend, $fact);
		}else{
			$listPo = $this->poDetailGeneral($pono, $dt1, $dt2, $vend, $fact);
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

	public function poDetailVendor($pono,$wh, $ttl_mi){
		if($wh == 'MAT'){
			$listPo = $this->poCommonDetailVend($pono, $ttl_mi);
		}else{
			$listPo = $this->poGeneralDetailVend($pono, $ttl_mi);
		}
		return $listPo;
	}

	function poCommonVend($pono, $dt1, $dt2, $vend, $fact){

		$sql = "select A0.Pono POMH_PONO, A0.RECH_MINO, A0.POMH_POID, A0.POMH_PART, A0.vendor POMH_VEND, A0.RECH_DATE, SUM(A0.POMD_QTTY) POMD_QTTY, SUM(A0.RECD_QTTY) RECD_QTTY, 
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

	function poCommonDetailVend($pono, $ttl_mi){
		// $sql="  select C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, '' stat
		// 		FROM ".$this->sjvendord." B
		// 		LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
		// 										 AND B.LineItem = C.LineItem
		// 		LEFT JOIN ".$this->recxxd." D ON D.RECD_PONO = B.Pono
		// 								     AND D.RECD_MATL = B.itemId
		// 		LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
		// 		LEFT JOIN ".$this->sjvendor." F ON B.LineSj = F.LineSj
		// 		WHERE B.Pono = '" . $pono ."'";
				// if(substr($mino,0,2)!='MA'){
				// $sql.="AND F.tanggal = '" . $tgl_mi ."'
				// AND F.totalSJ = '" . $ttl_mi ."'";
				// }
		// $sql.=" AND D.RECD_MINO IS NOT NULL
		// 		AND E.INSD_LIN2 IS NULL
		// 		GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty
		// 		UNION ALL
		// 		SELECT C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, 'OK' stat
		// 		FROM ".$this->sjvendord." B
		// 		LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
		// 															AND B.LineItem = C.LineItem
		// 		LEFT JOIN ".$this->recxxd." D ON D.RECD_PONO = B.Pono
		// 										  AND D.RECD_MATL = B.itemId
		// 		LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
		// 		WHERE B.Pono = '" . $pono ."'
		// 		AND D.RECD_MINO IS NOT NULL
		// 		AND E.INSD_LIN2 != ''
		// 		GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty";
		$sql="  select top 4 C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, '' stat
				FROM ".$this->sjvendord." B 
				LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj AND B.LineItem = C.LineItem 
				LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode 
				LEFT JOIN ".$this->sjvendor." F ON B.LineSj = F.LineSj 
				WHERE ISNULL(C.linebarcode,'') != ''
				AND ISNULL(E.INSD_LIN2,'') = ''";

		// $sql.=" AND E.INSD_LIN2 IS NULL
		// 		AND C.linebarcode IS NOT NULL
		// 		GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty
		// 		UNION ALL
		// 		SELECT C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, 'OK' stat
		// 		FROM ".$this->sjvendord." B
		// 		LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
		// 															AND B.LineItem = C.LineItem
		// 		LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
		// 		WHERE B.Pono LIKE '" . $pono ."%'
		// 		AND E.INSD_LIN2 != ''
		// 		GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty";
		return $this->db->query($sql)->result();
	}

	function poGeneralDetailVend($pono, $ttl_mi){
		// $sql="  select C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, '' stat
		// 		FROM ".$this->sjvendord." B
		// 		LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
		// 										 AND B.LineItem = C.LineItem
		// 		LEFT JOIN ".$this->recexd." D ON D.RECD_PONO = B.Pono
		// 								     AND D.RECD_MATL = B.itemId
		// 		LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
		// 		LEFT JOIN ".$this->sjvendor." F ON B.LineSj = F.LineSj
		// 		WHERE B.Pono = '" . $pono ."'";
				// if(substr($mino,0,2)!='MA'){
				// $sql.="AND F.tanggal = '" . $tgl_mi ."'
				// AND F.totalSJ = '" . $ttl_mi ."'";
				// }
		// $sql.=" AND D.RECD_MINO IS NOT NULL
		// 		AND E.INSD_LIN2 IS NULL
		// 		GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty
		// 		UNION ALL
		// 		SELECT C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, 'OK' stat
		// 		FROM ".$this->sjvendord." B
		// 		LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
		// 															AND B.LineItem = C.LineItem
		// 		LEFT JOIN ".$this->recexd." D ON D.RECD_PONO = B.Pono
		// 										  AND D.RECD_MATL = B.itemId
		// 		LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
		// 		WHERE B.Pono = '" . $pono ."'
		// 		AND D.RECD_MINO IS NOT NULL
		// 		AND E.INSD_LIN2 != ''
		// 		GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty";
		// $sql="  select C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, '' stat
		// 		FROM ".$this->sjvendord." B
		// 		LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
		// 										 AND B.LineItem = C.LineItem
		// 		LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
		// 		LEFT JOIN ".$this->sjvendor." F ON B.LineSj = F.LineSj
		// 		WHERE B.Pono LIKE '" . $pono ."%'";
		// $sql.=" AND E.INSD_LIN2 IS NULL
		// 		AND C.linebarcode IS NOT NULL
		// 		GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty
		// 		UNION ALL
		// 		SELECT C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, 'OK' stat
		// 		FROM ".$this->sjvendord." B
		// 		LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
		// 										 AND B.LineItem = C.LineItem
		// 		LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
		// 		WHERE B.Pono LIKE '" . $pono ."%'
		// 		AND E.INSD_LIN2 != ''
		// 		GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty";
		$sql="  select C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty, '' stat
				FROM ".$this->sjvendord." B
				LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
												 AND B.LineItem = C.LineItem
				LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
				LEFT JOIN ".$this->sjvendor." F ON B.LineSj = F.LineSj
				LEFT JOIN ".$this->pomexh." G ON G.POMH_PONO = B.Pono
				WHERE G.POMH_PONO IS NOT NULL
				AND E.INSD_LIN2 IS NULL
				AND C.linebarcode IS NOT NULL
				AND B.Pono NOT IN (SELECT headerPONumber FROM ".$this->inspectd." GROUP BY headerPONumber)
				GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty
				ORDER BY B.itemId";

		return $this->db->query($sql)->result();
	}

	function poDetailCommon($pono, $dt1, $dt2, $vend, $fact){

		$sql = "select A.POMH_PONO, D.RECH_MINO, D.RECH_DATE, A.POMH_POID, A.POMH_PART, A.POMH_ORDT, A.POMH_VEND, B.POMD_PRIC, SUM(ISNULL(B.POMD_QTTY,0)) POMD_QTTY, 
					   ISNULL(SUM(C.RECD_QTTY),0) RECD_QTTY, ISNULL(SUM(B.POMD_INSP_QTTY),0) POMD_INSP_QTTY, 
					   (SUM(ISNULL(B.POMD_QTTY,0)) - ISNULL(SUM(B.POMD_INSP_QTTY),0)) BALANCE, C.RECD_PO_SEQN
				FROM ".$this->pomxxh." A
				LEFT JOIN ".$this->pomxxd." B ON A.POMH_PONO = B.POMD_PONO
				LEFT JOIN ".$this->recxxd." C ON C.RECD_PONO = B.POMD_PONO AND C.RECD_MATL = B.POMD_CODE AND C.RECD_PO_SEQH = B.POMD_SEQH AND C.RECD_PO_SEQN = B.POMD_SEQN
				LEFT JOIN ".$this->recxxh." D ON D.RECH_MINO = C.RECD_MINO
				WHERE A.POMH_PONO LIKE '" . $pono ."%'";
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
		$sql .= "GROUP BY A.POMH_PONO, A.POMH_POID, A.POMH_PART, A.POMH_ORDT, A.POMH_VEND, B.POMD_PRIC, D.RECH_MINO, D.RECH_DATE, C.RECD_PO_SEQN
				 ORDER BY A.POMH_PONO";


		// print_r($sql);

		return $this->db->query($sql)->result();
	}

	function poDetailGeneral($pono, $dt1, $dt2, $vend, $fact){

		$sql = "select A.POMH_PONO, D.RECH_MINO, D.RECH_DATE, A.POMH_POID, A.POMH_PART, A.POMH_ORDT, A.POMH_VEND, B.POMD_PRIC, SUM(ISNULL(B.POMD_QTTY,0)) POMD_QTTY, 
					   ISNULL(SUM(C.RECD_QTTY),0) RECD_QTTY, ISNULL(SUM(B.POMD_INSP_QTTY),0) POMD_INSP_QTTY, 
					   (SUM(ISNULL(B.POMD_QTTY,0)) - ISNULL(SUM(B.POMD_INSP_QTTY),0)) BALANCE
				FROM ".$this->pomexh." A
				LEFT JOIN ".$this->pomexd." B ON A.POMH_PONO = B.POMD_PONO
				LEFT JOIN ".$this->recexd." C ON C.RECD_PONO = B.POMD_PONO AND C.RECD_MATL = B.POMD_CODE AND C.RECD_PO_SEQH = B.POMD_SEQH AND C.RECD_PO_SEQN = B.POMD_SEQN
				LEFT JOIN ".$this->recexh." D ON D.RECH_MINO = C.RECD_MINO
				WHERE A.POMH_PONO LIKE '" . $pono ."%'";
		if ($dt1 != "" and $dt2 != ""){ 
			$sql.= "AND A.POMH_ORDT BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
		}
		if ($vend != ""){
			$sql.= "AND A.POMH_VEND LIKE '". $vend ."'";
		}
		if ($fact != ""){
			$sql.= "AND A.POMH_FACT = '". $fact ."'";
		}
		$sql .= "GROUP BY A.POMH_PONO, A.POMH_POID, A.POMH_PART, A.POMH_ORDT, A.POMH_VEND, B.POMD_PRIC, D.RECH_MINO, D.RECH_DATE
			     ORDER BY A.POMH_PONO";

		// print_r($sql);

		return $this->db->query($sql)->result();
	}

	public function getDetailFormPO($pono, $wh, $mino){
		if($wh == 'MAT') {
			$headerPo = $this->headerPOcommon($pono, $mino);
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
				AND B.INSD_IQTY > 0
				GROUP BY A.INSH_IDXX, ISNULL(INSD_MINO,''), A.INSH_SJNO, B.INSD_LINE
				/*UNION ALL
				select A.Id COLLATE DATABASE_DEFAULT Id, '' MINO, A.sjno COLLATE DATABASE_DEFAULT sjno, SUM(B.qtty) qtty, CONVERT(VARCHAR(255),B.line)
				FROM ".$this->inspecth." A
				LEFT JOIN ".$this->inspectd." B ON A.Id = B.headerID
				WHERE B.headerPONumber = '".$pono."'
				AND B.qtty > 0
				GROUP BY A.Id, A.sjno, B.line*/
				ORDER BY A.INSH_IDXX";
		return $this->db->query($sql)->result();
	}

	public function getMiDetailMaterial($pono, $mino, $matcode, $wh){
		if($wh=='MAT'){
			$midetail = $this->getMiDetailMaterialCom($pono, $mino, $matcode);
		}else{
			$midetail = $this->getMiDetailMaterialGen($pono, $mino, $matcode);
		}
		return $midetail;
	}

	function getMiDetailMaterialCom($pono, $mino, $matcode){
		$sql = "select POMD_SEQN, POMD_CODE, POMD_QTTY POMD_QTTY, POMD_PRIC, POMH_UMCD, POMD_INSP_QTTY POMD_INSP_QTTY,
					   SUM(POMD_QTTY)-SUM(ISNULL(POMD_INSP_QTTY,0)) BALANCE, RECD_QTTY, SUM(ISNULL(E.INSD_IQTY,0)) IQTY, 
					   RECD_QTTY - SUM(ISNULL(E.INSD_IQTY,0)) BALANCE2
				FROM ".$this->pomxxd." A
				LEFT JOIN ".$this->pomxxh." B ON A.POMD_PONO = B.POMH_PONO
				LEFT JOIN ".$this->recxxh." C ON C.RECH_PONO = B.POMH_PONO
				LEFT JOIN ".$this->recxxd." D ON D.RECD_MINO = C.RECH_MINO
											 AND D.RECD_MATL = A.POMD_CODE
											 AND D.RECD_PO_SEQN = A.POMD_SEQN
				LEFT JOIN ".$this->inspectd2." E ON E.INSD_MINO = D.RECD_MINO
									   AND E.INSD_CODE = D.RECD_MATL
									   AND E.INSD_SEQN = D.RECD_PO_SEQN
				WHERE D.RECD_QTTY IS NOT NULL
				AND POMD_PONO = '".$pono."'";
		if($mino != ""){
			$sql.= "AND RECH_MINO = '".$mino."'";
		}
		if ($matcode != ""){
			$sql.= "AND POMD_CODE LIKE '". $matcode ."'";
		}
		$sql.= "GROUP BY POMD_CODE, POMD_SEQN, POMD_PRIC, POMH_UMCD, D.RECD_MINO, RECD_QTTY, POMD_INSP_QTTY, POMD_QTTY";
		// print_r($sql);
		return $this->db->query($sql)->result();
	}

	function getMiDetailMaterialGen($pono, $mino, $matcode){
		$sql = "select POMD_SEQN, POMD_CODE, POMD_QTTY POMD_QTTY, POMD_PRIC, POMH_UMCD, POMD_INSP_QTTY POMD_INSP_QTTY,
					   SUM(POMD_QTTY)-SUM(ISNULL(POMD_INSP_QTTY,0)) BALANCE, RECD_QTTY, SUM(ISNULL(E.INSD_IQTY,0)) IQTY, 
					   RECD_QTTY - SUM(ISNULL(E.INSD_IQTY,0)) BALANCE2
				FROM ".$this->pomexd." A
				LEFT JOIN ".$this->pomexh." B ON A.POMD_PONO = B.POMH_PONO
				LEFT JOIN ".$this->recexh." C ON C.RECH_PONO = B.POMH_PONO
				LEFT JOIN ".$this->recexd." D ON D.RECD_MINO = C.RECH_MINO
											 AND D.RECD_MATL = A.POMD_CODE
											 AND D.RECD_PO_SEQN = A.POMD_SEQN
				LEFT JOIN ".$this->inspectd2." E ON E.INSD_MINO = D.RECD_MINO
									   AND E.INSD_CODE = D.RECD_MATL
									   AND E.INSD_SEQN = D.RECD_PO_SEQN
				WHERE D.RECD_QTTY IS NOT NULL
				AND POMD_PONO = '".$pono."'";
		if($mino != ""){
			$sql.= "AND RECH_MINO = '".$mino."'";
		}
		if ($matcode != ""){
			$sql.= "AND POMD_CODE LIKE '". $matcode ."'";
		}
		$sql.= "GROUP BY POMD_CODE, POMD_SEQN, POMD_PRIC, POMH_UMCD";
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
			       	   A.INSH_NBRN release, A.INSH_PART style, A.INSH_WHID wh, INSH_VEND COLLATE Korean_Wansung_CI_AS vend, B.INSD_MINO mino
				FROM ".$this->inspecth2." A
				LEFT JOIN ".$this->inspectd2." B ON A.INSH_IDXX = B.INSD_IDXX
				WHERE A.INSH_IDXX = '". $id ."'
				AND B.INSD_IQTY > 0
				UNION ALL
				select A.headerID, A.headerPONumber, B.tanggal, 
				       B.sjno, A.headerMatCode, CONVERT(VARCHAR(255),A.line), 
				       A.kemasan, 0 tKem, a.qtty, A.unit, A.headerSEQN, ISNULL(A.harga,0) harga, '' umcd, ISNULL(A.release,'') release, 
				       ISNULL(A.style,style) style, B.warehouse, B.vendorid, '' mino
				FROM ".$this->inspectd." A
				LEFT JOIN ".$this->inspecth." B ON A.headerID = B.Id
				WHERE headerID = '". $id ."'
				AND a.qtty > 0";
		return $this->db->query($sql)->result();
	}

	public function loadDataVendor($vend = ""){
		$sql = "select VEND_VEND, VEND_DESC
				FROM ".$this->vendor."
				WHERE VEND_VEND LIKE '". $vend ."%'";
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
		if($wh == 'MAT'){
			$list = $this->getdetailPoVendCommon($barcode);
		}else{
			$list = $this->getdetailPoVendGeneral($barcode);
		}

		return $list;
	}

	public function getdetailPoVendCommon($barcode){
		// $sql = "select A0.*, E.RECH_MINO, E.RECH_DATE
		// FROM (
		// 		select noSuratJalan, vendor, tanggal, totalSJ, C.FACTORY, A.linebarcode, B.Pono, A.LineItem, B.itemId, B.satuan, A.qtty, B.Kemasan, B.jmlKemasan--, D.POMD_PONO, D.POMD_CODE, D.POMD_SEQN
		// 		FROM ".$this->sjvendordb." A
		// 		LEFT JOIN ".$this->sjvendord." B ON A.LineSj = B.LineSj
		// 													 AND A.LineItem = B.LineItem
		// 		LEFT JOIN ".$this->sjvendor." C ON B.LineSj = C.LineSj
		// 		WHERE A.linebarcode ='". $barcode ."'
		// 		AND C.FACTORY = 'PM'
		// ) A0
		// LEFT JOIN ".$this->recxxh." E ON E.RECH_PONO = A0.Pono
		// 								  AND E.RECH_DATE = A0.tanggal
		// 								  AND E.MI_Qtty = A0.totalSJ
		// LEFT JOIN ".$this->recxxd." F ON F.RECD_MINO = E.RECH_MINO
		// 								  AND F.RECD_MATL = A0.itemId";
		$sql = "select A0.*
		FROM (
				select noSuratJalan, vendor, tanggal, totalSJ, C.FACTORY, A.linebarcode, B.Pono, A.LineItem, B.itemId, B.satuan, A.qtty, B.Kemasan, B.jmlKemasan--, D.POMD_PONO, D.POMD_CODE, D.POMD_SEQN
				FROM ".$this->sjvendordb." A
				LEFT JOIN ".$this->sjvendord." B ON A.LineSj = B.LineSj
															 AND A.LineItem = B.LineItem
				LEFT JOIN ".$this->sjvendor." C ON B.LineSj = C.LineSj
				WHERE A.linebarcode ='". $barcode ."'
				AND C.FACTORY = 'PM'
		) A0";
		return $this->db->query($sql)->result();
	}

	public function getdetailPoVendGeneral($barcode){
		// $sql = "select A0.*, E.RECH_MINO, E.RECH_DATE
		// FROM (
		// 		select noSuratJalan, vendor, tanggal, totalSJ, C.FACTORY, A.linebarcode, B.Pono, A.LineItem, B.itemId, B.satuan, A.qtty, B.Kemasan, B.jmlKemasan--, D.POMD_PONO, D.POMD_CODE, D.POMD_SEQN
		// 		FROM ".$this->sjvendordb." A
		// 		LEFT JOIN ".$this->sjvendord." B ON A.LineSj = B.LineSj
		// 													 AND A.LineItem = B.LineItem
		// 		LEFT JOIN ".$this->sjvendor." C ON B.LineSj = C.LineSj
		// 		WHERE A.linebarcode ='". $barcode ."'
		// 		AND C.FACTORY = 'PM'
		// ) A0
		// LEFT JOIN ".$this->recexh." E ON E.RECH_PONO = A0.Pono
		// 								  AND E.RECH_DATE = A0.tanggal
		// 								  AND E.MI_Qtty = A0.totalSJ
		// LEFT JOIN ".$this->recexd." F ON F.RECD_MINO = E.RECH_MINO
		// 								  AND F.RECD_MATL = A0.itemId";
		$sql = "select A0.*
		FROM (
				select noSuratJalan, vendor, tanggal, totalSJ, C.FACTORY, A.linebarcode, B.Pono, A.LineItem, B.itemId, B.satuan, A.qtty, B.Kemasan, B.jmlKemasan--, D.POMD_PONO, D.POMD_CODE, D.POMD_SEQN
				FROM ".$this->sjvendordb." A
				LEFT JOIN ".$this->sjvendord." B ON A.LineSj = B.LineSj
															 AND A.LineItem = B.LineItem
				LEFT JOIN ".$this->sjvendor." C ON B.LineSj = C.LineSj
				WHERE A.linebarcode ='". $barcode ."'
				AND C.FACTORY = 'PM'
		) A0";

		return $this->db->query($sql)->result();
	}

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
				WHERE POMD_PONO = '". $matl ."'
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
		if($tipe!='KT'){
			$sql = "select A0.MRQH_MRNO, A0.MRQH_REDT, A0.MRQH_IPWX, A0.MRQH_PART, A0.MRQH_OPCD, A0.ROTE_NAME, A0.MRQH_DEST, SUM(A0.QTTY) QTTY, \n
			               SUM(A0.QTTY_APPR) QTTY_APPR, SUM(A0.QTY_OUT) QTY_OUT, (SUM(A0.QTTY_APPR) - SUM(A0.QTY_OUT)) BALANCE, A0.wh\n
					FROM (\n
							SELECT A.MRQH_MRNO, A.MRQH_REDT, A.MRQH_IPWX, A.MRQH_PART, A.MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST, SUM(B.MRQD_QTTY) QTTY, \n
								   CASE WHEN SUM(B.MRQD_QTY2) = 0 THEN SUM(B.MRQD_QTTY) ELSE SUM(B.MRQD_QTY2) END QTTY_APPR, ISNULL(D.TRND_QTTY,0) QTY_OUT, 'MAT' wh\n
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
			               A0.QTTY_APPR, SUM(D.TRND_QTTY) QTY_OUT, A0.QTTY_APPR - SUM(D.TRND_QTTY) BALANCE, A0.wh\n
					FROM (\n
							SELECT A.MRQH_MRNO, A.MRQH_DATE, A.MRQH_NBRN MRQH_IPWX, A.MRQH_PART, A.MRQH_PROC MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST, SUM(B.MRQD_QTTY) QTTY, \n
								   SUM(B.MRQD_QTTY) QTTY_APPR, 'MAT' wh\n
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
		}else{
				$sql = "select A.KRNH_MRNO MRQH_MRNO, D.TRNH_DATE MRQH_REDT, A.KRNH_IPWX MRQH_IPWX, A.KRNH_PART MRQH_PART, A.KRNH_OPCD MRQH_OPCD, F.ROTE_NAME, 
							   CASE WHEN A.KRNH_SITE = '0' THEN E.MRQH_DEST ELSE A.KRNH_SITE END MRQH_DEST,  SUM(C.TRND_QTTY) QTTY_APPR, 'MAT' wh
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

			// $sql = "select A0.MRQH_MRNO, A0.MRQH_REDT, A0.MRQH_IPWX, A0.MRQH_PART, A0.MRQH_OPCD, A0.ROTE_NAME, A0.MRQH_DEST, SUM(A0.QTTY) QTTY, \n
			//                SUM(A0.QTTY_APPR) QTTY_APPR, SUM(A0.QTY_OUT) QTY_OUT, (SUM(A0.QTTY_APPR) - SUM(A0.QTY_OUT)) BALANCE, A0.wh\n
			// 		FROM (\n
			// 				SELECT A.MRQH_MRNO, A.MRQH_REDT, A.MRQH_IPWX, A.MRQH_PART, A.MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST, SUM(B.MRQD_QTTY) QTTY, \n
			// 					   CASE WHEN SUM(B.MRQD_QTY2) = 0 THEN SUM(B.MRQD_QTTY) ELSE SUM(B.MRQD_QTY2) END QTTY_APPR,\n
			// 					   CASE WHEN ISNULL(D.TRND_QTTY,0) = 0 THEN ISNULL(E.TRND_QTTY,0) ELSE ISNULL(D.TRND_QTTY,0) END QTY_OUT, 'MAT' wh\n
			// 				FROM ".$this->tmmreqxh." A\n
			// 				LEFT JOIN ".$this->tmmreqxd." B ON A.MRQH_MRNO = B.MRQD_MRNO\n
			// 				LEFT JOIN ".$this->routem." C ON C.ROTE_OPCD = A.MRQH_OPCD\n
			// 				LEFT JOIN ".$this->transd." D ON D.TRND_MRNO = B.MRQD_MRNO\n
			// 												  AND D.TRND_CODE = B.MRQD_CODE\n
			//  				LEFT JOIN ".$this->transd." E ON E.TRND_LAST = B.MRQD_MRNO\n
			//  												  AND E.TRND_CODE = B.MRQD_CODE\n
			// 				WHERE A.MRQH_MRNO LIKE '". $mrno ."%'\n
			// 				AND A.MRQH_STAT = 'C'\n
			// 				AND LEFT(A.MRQH_MRNO ,2) ='KK'\n";
			// 				if($dt1 != '' && $dt2 != ''){
			// 					$sql.= "AND A.MRQH_REDT BETWEEN '". $dt1 ."' AND '". $dt2 ."'\n";
			// 				}
			// 				if($opcd != ''){
			// 					$sql.= "AND A.MRQH_OPCD = '". $opcd ."'\n";
			// 				}
			// 				if($fact != ''){
			// 					$sql.= "AND A.MRQH_DEST = '". $fact ."'\n";
			// 				}
			// $sql.= "GROUP BY A.MRQH_MRNO, A.MRQH_REDT, A.MRQH_IPWX, A.MRQH_PART, A.MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST, ISNULL(D.TRND_QTTY,0), ISNULL(E.TRND_QTTY,0)\n
			// 		)A0\n
			// 		GROUP BY A0.MRQH_MRNO, A0.MRQH_REDT, A0.MRQH_IPWX, A0.MRQH_PART, A0.MRQH_OPCD, A0.ROTE_NAME, A0.MRQH_DEST, A0.wh \n";
			// $sql = "select A0.MRQH_MRNO, A0.MRQH_REDT, A0.MRQH_IPWX, A0.MRQH_PART, A0.MRQH_OPCD, A0.ROTE_NAME, A0.MRQH_DEST, A0.QTTY, \n
			//                A0.QTTY_APPR, A0.QTY_OUT, A0.QTTY_APPR - A0.QTY_OUT BALANCE, A0.wh\n
			// 		FROM (\n
			// 				SELECT A.MRQH_MRNO, A.MRQH_REDT, A.MRQH_IPWX, A.MRQH_PART, A.MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST, SUM(B.MRQD_QTTY) QTTY, \n
			// 					   CASE WHEN SUM(B.MRQD_QTY2) = 0 THEN SUM(B.MRQD_QTTY) ELSE SUM(B.MRQD_QTY2) END QTTY_APPR, 
			// 					   COALESCE(SUM(D.TRND_QTTY), SUM(E.TRND_QTTY)) QTY_OUT, 'MAT' wh\n
			// 				FROM PRTMMRP.PRTM.TM_MREQXH A\n
			// 				LEFT JOIN PRTMMRP.PRTM.TM_MREQXD B ON A.MRQH_MRNO = B.MRQD_MRNO\n
			// 				LEFT JOIN PRTMERP.PRTM.TP_ROUTEM C ON C.ROTE_OPCD = A.MRQH_OPCD\n
			// 				LEFT JOIN PRTMMRP.PRTM.TM_TRANSD D ON D.TRND_MRNO = B.MRQD_MRNO\n
			// 												  AND D.TRND_CODE = B.MRQD_CODE\n
			// 				LEFT JOIN PRTMMRP.PRTM.TM_TRANSD E ON E.TRND_LAST = B.MRQD_MRNO\n
			// 												  AND E.TRND_CODE = B.MRQD_CODE\n
			// 				WHERE A.MRQH_MRNO LIKE '". $mrno ."%'\n
			// 				AND LEFT(A.MRQH_MRNO ,2) ='KK'\n";
			// 				if($dt1 != '' && $dt2 != ''){
			// 					$sql.= "AND A.MRQH_REDT BETWEEN '". $dt1 ."' AND '". $dt2 ."'\n";
			// 				}
			// 				if($opcd != ''){
			// 					$sql.= "AND A.MRQH_OPCD = '". $opcd ."'\n";
			// 				}
			// 				if($fact != ''){
			// 					$sql.= "AND A.MRQH_DEST = '". $fact ."'\n";
			// 				}
			// $sql.= "GROUP BY A.MRQH_MRNO, A.MRQH_REDT, A.MRQH_IPWX, A.MRQH_PART, A.MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST\n
			// 		)A0\n";
			// $sql.= "UNION ALL \n";
			// $sql = "select A0.MRQH_MRNO, A0.MRQH_DATE, A0.MRQH_IPWX, A0.MRQH_PART, A0.MRQH_OPCD, A0.ROTE_NAME, A0.MRQH_DEST, A0.QTTY, \n
			//                A0.QTTY_APPR, A0.QTY_OUT, A0.QTTY_APPR - A0.QTY_OUT BALANCE, A0.wh\n
			// 		FROM (\n
			// 				SELECT A.MRQH_MRNO, A.MRQH_DATE, A.MRQH_NBRN MRQH_IPWX, A.MRQH_PART, A.MRQH_PROC MRQH_OPCD, C.ROTE_NAME, A.MRQH_DEST, SUM(B.MRQD_QTTY) QTTY, \n
			// 					   SUM(B.MRQD_QTTY) QTTY_APPR, COALESCE(SUM(D.TRND_QTTY), SUM(E.TRND_QTTY)) QTY_OUT, 'MAT' wh\n
			// 				FROM PRTMMRP.PRTM.TO_MREQXH A\n
			// 				LEFT JOIN PRTMMRP.PRTM.TO_MREQXD B ON A.MRQH_MRNO = B.MRQD_MRNO\n
			// 				LEFT JOIN PRTMERP.PRTM.TP_ROUTEM C ON C.ROTE_OPCD = A.MRQH_OPCD\n
			// 				LEFT JOIN PRTMMRP.PRTM.TM_TRANSD D ON D.TRND_MRNO = B.MRQD_MRNO\n
			// 												  AND D.TRND_CODE = B.MRQD_CODE\n
			// 				WHERE A.MRQH_MRNO LIKE '". $mrno ."%'\n
			// 				AND LEFT(A.MRQH_MRNO ,2) ='KK'\n";
			// 				if($dt1 != '' && $dt2 != ''){
			// 					$sql.= "AND A.MRQH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'\n";
			// 				}
			// 				if($opcd != ''){
			// 					$sql.= "AND A.MRQH_PROC = '". $opcd ."'\n";
			// 				}
			// 				if($fact != ''){
			// 					$sql.= "AND A.MRQH_DEST = '". $fact ."'\n";
			// 				}
			// $sql.= "GROUP BY A.MRQH_MRNO, A.MRQH_DATE, A.MRQH_NBRN, A.MRQH_PART, A.MRQH_PROC, C.ROTE_NAME, A.MRQH_DEST\n
			// 		)A0\n";
		

		// $sql = "select TRNH_MRNO, TRNH_DATE, COALESCE(MRQH_IPWX, KRNH_IPWX) MRQH_IPWX, COALESCE(B.MRQH_PART, KRNH_PART) MRQH_PART, \n
		// 			   COALESCE(B.MRQH_OPCD, D.KRNH_OPCD) MRQH_OPCD, C.ROTE_NAME, COALESCE(B.MRQH_DEST,KRNH_SITE) MRQH_DEST, COALESCE(B.MRQH_QTTY,0) MRQH_QTTY, '".$wh."' wh \n
		// 		FROM ".$this->transh." A \n
		// 		LEFT JOIN ".$this->tmmreqxh." B ON A.TRNH_MRNO = B.MRQH_MRNO\n
		// 		LEFT JOIN ".$this->tmkrnh." D ON D.KRNH_MRNO = A.TRNH_MRNO\n
		// 		LEFT JOIN ".$this->routem." C ON COALESCE(B.MRQH_OPCD, D.KRNH_OPCD) = C.ROTE_OPCD\n
		// 		LEFT JOIN ".$this->outbarcoded2." E ON E.ONSD_MRNO = A.TRNH_MRNO\n
		// 		WHERE LEFT(TRNH_MRNO,2) != 'MO'\n
		// 		AND E.ONSD_MRNO IS NULL\n";
		// if($mrno != ''){
		// 	$sql.= "AND TRNH_MRNO = '". $mrno ."'";
		// }
		// if($dt1 != '' && $dt2 != ''){
		// 	$sql.= "AND TRNH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
		// }
		// if($opcd != ''){
		// 	$sql.= "AND COALESCE(B.MRQH_OPCD, D.KRNH_OPCD) = '". $opcd ."'";
		// }
		// if($fact != ''){
		// 	$sql.= "AND COALESCE(MRQH_DEST,KRNH_SITE) = '". $fact ."'";
		// }
		// $sql.= "GROUP BY TRNH_MRNO, TRNH_DATE, COALESCE(MRQH_IPWX, KRNH_IPWX), COALESCE(B.MRQH_PART, KRNH_PART), 
		// 			     COALESCE(B.MRQH_OPCD, D.KRNH_OPCD), C.ROTE_NAME, COALESCE(B.MRQH_DEST,KRNH_SITE), COALESCE(B.MRQH_QTTY,0) \n";
		// $sql.= "UNION ALL \n
		// 		select B0.TRNH_MRNO, B0.TRNH_DATE, A0.MRQH_NBRN MRQH_IPWX, A0.MRQH_PART MRQH_PART, \n
		// 			   A0.MRQH_PROC MRQH_OPCD, C0.ROTE_NAME, A0.MRQH_DEST MRQH_DEST, 0 MRQH_QTTY, '".$wh."' wh\n
		// 		FROM ".$this->tomreqxh." A0\n
		// 		LEFT JOIN ".$this->transh." B0 ON A0.MRQH_MRNO = B0.TRNH_MRNO\n
		// 		LEFT JOIN ".$this->routem." C0 ON A0.MRQH_PROC = C0.ROTE_OPCD\n
		// 		LEFT JOIN ".$this->outbarcoded2." E0 ON E0.ONSD_MRNO = B0.TRNH_MRNO\n
		// 		WHERE B0.TRNH_MRNO != ''\n
		// 		AND E0.ONSD_MRNO IS NULL\n";
		// if($mrno != ''){
		// 	$sql.= "AND B0.TRNH_MRNO = '". $mrno ."'";
		// }
		// if($dt1 != '' && $dt2 != ''){
		// 	$sql.= "AND B0.TRNH_DATE BETWEEN '". $dt1 ."' AND '". $dt2 ."'";
		// }
		// if($opcd != ''){
		// 	$sql.= "AND A0.MRQH_PROC = '". $opcd ."'";
		// }
		// if($fact != ''){
		// 	$sql.= "AND A0.MRQH_DEST = '". $fact ."'";
		// }
		// $sql.= "GROUP BY B0.TRNH_MRNO, B0.TRNH_DATE, A0.MRQH_NBRN, A0.MRQH_PART, 
		// 			     A0.MRQH_PROC, C0.ROTE_NAME, A0.MRQH_DEST";
		// var_dump($sql);
		// print_r($sql);
		return $this->db->query($sql)->result();
	}

	public function loadDataMRDetail($mrno, $matcode, $tipe){
		$sql = "select B.TRND_CODE, COALESCE(SUM(D.MRQD_QTTY), SUM(E.MRQD_QTTY)) QTTY, ISNULL(B.TRND_QTTY,0) QTY_OUT,
					   COALESCE(CASE WHEN SUM(D.MRQD_QTY2) = 0 THEN SUM(D.MRQD_QTTY) ELSE SUM(D.MRQD_QTY2)END, CASE WHEN SUM(E.MRQD_QTY2) = 0 THEN SUM(E.MRQD_QTTY) ELSE SUM(E.MRQD_QTY2)END) QTTY_APPR
				FROM PRTMMRP.PRTM.TM_TRANSH A
				LEFT JOIN PRTMMRP.PRTM.TM_TRANSD B ON A.TRNH_MRNO = B.TRND_MRNO
				LEFT JOIN PRTMMRP.PRTM.TM_MREQXH C ON C.MRQH_MRNO = A.TRNH_MRNO
				LEFT JOIN PRTMMRP.PRTM.TM_MREQXD D ON D.MRQD_MRNO = B.TRND_MRNO
												  AND D.MRQD_CODE = B.TRND_CODE
				LEFT JOIN PRTMMRP.PRTM.TM_MREQXD E ON E.MRQD_MRNO = B.TRND_LAST
												  AND E.MRQD_CODE = B.TRND_CODE
				WHERE A.TRNH_MRNO = '". $mrno ."'";
			if($matcode != ''){
				$sql.= "AND B.TRND_CODE = '". $matcode ."'";
			}		
		$sql.= "GROUP BY B.TRND_CODE, ISNULL(B.TRND_QTTY,0)";

		// if($tipe != 'KT'){
		// 	$sql = "select B.MRQD_CODE TRND_CODE, SUM(B.MRQD_QTTY) TRND_QTTY, CASE WHEN SUM(B.MRQD_QTY2) = 0 THEN SUM(B.MRQD_QTTY) ELSE SUM(B.MRQD_QTY2) END QTTY_APPR,
		// 				  ISNULL(D.TRND_QTTY,0) QTY_OUT
		// 			FROM ".$this->tmmreqxh." A
		// 			LEFT JOIN ".$this->tmmreqxd." B ON A.MRQH_MRNO = B.MRQD_MRNO
		// 			LEFT JOIN ".$this->transd." D ON D.TRND_MRNO = B.MRQD_MRNO
		// 							  AND D.TRND_CODE = B.MRQD_CODE
		// 			WHERE B.MRQD_MRNO = '". $mrno ."'";
		// 	if($matcode != ''){
		// 		$sql.= "AND B.MRQD_CODE = '". $matcode ."'";
		// 	}
		// 	$sql.= "GROUP BY B.MRQD_CODE, ISNULL(D.TRND_QTTY,0), ISNULL(E.TRND_QTTY,0)
		// 			HAVING CASE WHEN SUM(B.MRQD_QTY2) = 0 THEN SUM(B.MRQD_QTTY) ELSE SUM(B.MRQD_QTY2) END > 0";
		// }else{

		// }
		// $sql = "select A.TRND_CODE, A.TRND_QTTY, A.REAL_QTTY
		// 		FROM ".$this->transd." A
		// 		WHERE A.TRND_MRNO = '". $mrno ."'";
		// if($matcode != ''){
		// 	$sql.= "AND TRND_CODE = '". $matcode ."'";
		// }

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
		$sql = "select A0.RECD_MATL, SUM(A0.RECD_QTTY) * -1 RECD_QTTY	
				FROM(    
						SELECT RECD_MATL, RECD_QTTY
						FROM ".$this->recxxd."
						WHERE RECD_MINO LIKE 'RJ%'
						AND RECD_MINO = '". $rjno ."'";
					if($matcode != ''){
						$sql.="AND RECD_MATL = '". $matcode ."'";
					}
				 $sql.="UNION ALL
						SELECT RECD_MATL, RECD_QTTY
						FROM ".$this->recexd."
						WHERE RECD_MINO LIKE 'RJ%'
						AND RECD_MINO = '". $rjno ."'";
					if($matcode != ''){
						$sql.="AND RECD_MATL = '". $matcode ."'";
					}
		$sql.=") A0
			   GROUP BY A0.RECD_MATL";
		return $this->db->query($sql)->result();
	}

	public function getMenu($id){
		$sql =" select MENU_MODULE, MENU_GROUP, MENU_GRNM, MENU_MENUID, MENU_PGMID, MENU_NAME, MENU_READ, MENU_INQUERY, MENU_INSERT, MENU_UPDATE, MENU_DELETE, MENU_PRINT
				FROM ".$this->mainmenu."
				WHERE MENU_MODULE ='MFAW'
				AND MENU_USERID = '". $id ."'
				ORDER BY MENU_GROUP, MENU_GRNM";
		return $this->db->query($sql)->result();
	}

	public function countMenuId($menuid){
		$cnt = 0;
		$sql ="select COUNT(*) CNT
			   FROM ".$this->mainmenu."
			   WHERE MENU_MODULE ='MFAW' AND MENU_MENUID = '". $menuid ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

	public function countMenu($menuid, $menupg){
		$cnt = 0;
		$sql ="select COUNT(*) CNT
			   FROM ".$this->mainmenu."
			   WHERE MENU_MODULE ='MFAW' AND MENU_MENUID = '". $menuid ."' AND MENU_PGMID = '". $menupg ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

	public function getMenuName($menuid, $menupg){
		$name = '';
		$sql ="select MENU_NAME
			   FROM ".$this->mainmenu."
			   WHERE MENU_MODULE ='MFAW' AND MENU_MENUID = '". $menuid ."' AND MENU_PGMID = '". $menupg ."' GROUP BY MENU_NAME";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$name = $d->MENU_NAME;
		}
		return $name;
	}

	public function addRoleMenu($menuid, $menupg, $menuname, $menudt, $grno, $mnno){
		$cnt = $this->countMenu($menuid, $menupg);
		$name = $this->getMenuName($menuid, $menupg);

		$data = array(
					'MENU_MODULE'=>'MFAW',
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
					'MENU_MODULE'=>'MFAW',
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
		}
	}

	public function cekTranshErp($mrno){
		$cnt = 0;
		$sql =" select COUNT(*) CNT
				FROM PRTMMRP.PRTM.TM_TRANSH
				WHERE TRNH_MRNO = '". $mrno ."'";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$cnt = $d->CNT;	
		}
		return $cnt;
	}

	public function cekTransdErp($mrno, $code){
		$cnt = 0;
		$sql =" select COUNT(*) CNT
				FROM PRTMMRP.PRTM.TM_TRANSD
				WHERE TRND_MRNO = '". $mrno ."'
				AND TRND_CODE = '". $code ."'";
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
			$cnt = $d->QTY;	
		}
		return $qtty;
	}

	public function saveTranshErp($data, $mrno, $list){
		$part = '';
		foreach($data as $l){
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

			$td = $this->master->saveTransdErp($data, $mrno, $part, $list);
		}
	}

	public function saveTransdErp($data, $mrno, $part, $list){
	$stat = 0;
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
			}
		}else{
			foreach($list as $lt2){
				$ttlOut = $this->cekTtlTransdErp($mrno, $lt2->INSD_CODE);
				$ttlOut = $ttlOut + floatval($lt2->INSD_BQTY);

				$param=array('TRND_MRNO'=>$mrno,'TRND_CODE'=>$lt2->INSD_CODE);
				$data2 = array('TRND_QTTY'=>floatval($ttlOut));
				
				$this->db->where($param);
				$this->db->update($this->transd, $data2);
			}
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

	public function cekClosing($tranno, $trandt, $flag){
		$cnt=0;
		$sql = "EXEC PRTMOSM.PRTM.USP_SCRUD_CLOSING_DATE
			    @P_CLOSE_DT_PROSES = '". $flag ."' , -- varchar(10)
			    @P_CLOSE_DT_DATE = '". $trandt ."' , -- char(10)
			    @P_CLOSE_DT_FLAG = 0 , -- tinyint
			    @P_PERIODE = '". substr($trandt, 0,4) ."' , -- char(4)
			    @P_FLAG = 'Q' -- varchar(2)";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			if(is_null($d->CLOSE_DT_FLAG)== 1){
				$cnt = 0;		
			}else{
				$cnt = $d->CLOSE_DT_FLAG;
			}
			
		}
		return $cnt;
	}
}

/* End of file Master_model.php */
/* Location: ./application/models/Master_model.php */