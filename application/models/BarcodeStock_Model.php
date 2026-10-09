<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Laminating_model.php");

class Barcodestock_model extends Laminating_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function TransferSerialBarcode($prev_dt, $dt1, $dt2, $wh, $fact, $matl){
		$sql = " EXEC PRTMMRP.PRTM.SP_MCM_SERIAL_BARCODE_CLOSING 'CLOSE', '". $prev_dt ."', '". $dt1 ."', '". $dt2 ."', '". $matl ."%', '". $fact ."%'";
		return $this->db->query($sql)->result();
	}

	public function getSerialBarcode($prev_dt, $dt1, $dt2, $wh, $fact, $matl){
		$sql = " EXEC PRTMMRP.PRTM.SP_MCM_SERIAL_BARCODE_CLOSING 'LOAD', '". $prev_dt ."', '". $dt1 ."', '". $dt2 ."', '". $matl ."%', '". $fact ."%'";
		return $this->db->query($sql)->result();
	}

	public function cekStockERP($kode){
	$ddt = '';
	$dt = new DateTime();
	$ddt = $dt->format('Y-m-01');
	$previousDate = date('Ym', strtotime('-1 day', strtotime($ddt)));
	$tgl2 = new DateTime();
	$tgl2 = $tgl2->format('Y-m-d');

	$qty = 0;
		$sql="select A0.STOK_CODE, SUM(A0.awal)+SUM(A0.masuk)-SUM(A0.keluar) AKHIR
				FROM(
						SELECT STOK_CODE, STOK_CQTY awal, 0 masuk, 0 keluar
						FROM PRTMMRP.PRTM.TM_STOCK_REALTIME_MONTHLY
						WHERE STOK_DATE = '". $previousDate ."'
						AND STOK_CODE = '".$kode."'
						UNION ALL
						SELECT B.RECD_MATL, 0, B.RECD_QTTY, 0
						FROM PRTMMRP.PRTM.TM_RECXXH A
						INNER JOIN PRTMMRP.PRTM.TM_RECXXD B ON B.RECD_MINO = A.RECH_MINO
						WHERE A.RECH_DATE BETWEEN '". $ddt ."' AND '". $tgl2 ."'
						AND B.RECD_MATL = '".$kode."'
						UNION ALL
						SELECT B.RECD_MATL, 0, B.RECD_QTTY, 0
						FROM PRTMMRP.PRTM.TM_RECEXH A
						INNER JOIN PRTMMRP.PRTM.TM_RECEXD B ON B.RECD_MINO = A.RECH_MINO
						WHERE A.RECH_DATE BETWEEN '". $ddt ."' AND '". $tgl2 ."'
						AND B.RECD_MATL = '".$kode."'
						UNION ALL
						SELECT B.TRND_CODE, 0, 0, B.TRND_QTTY
						FROM PRTMMRP.PRTM.TM_TRANSH A
						INNER JOIN PRTMMRP.PRTM.TM_TRANSD B ON B.TRND_MRNO = A.TRNH_MRNO
						WHERE A.TRNH_DATE BETWEEN '". $ddt ."' AND '". $tgl2 ."'
						AND B.TRND_CODE = '".$kode."'
						UNION ALL
						SELECT B.REQD_MATL, 0, 0, B.REQD_QTTY
						FROM PRTMENG.PRTM.TG_REQXXH A
						INNER JOIN PRTMENG.PRTM.TG_REQXXD B ON B.REQD_NMBR = A.REQH_NMBR
						WHERE A.REQH_DATE BETWEEN '". $ddt ."' AND '". $tgl2 ."'
						AND B.REQD_MATL = '".$kode."'
					) A0
				GROUP BY  A0.STOK_CODE";
		$dt = $this->db->query($sql)->result();
		foreach($dt as $d){
			$qty = floatval($d->AKHIR);	
		}
		return $qty;
	}

	public function getTransactionInBacodeOld($tgl1, $tgl2, $wh, $fact, $matl){
		$sql = "select A0.kode, max(a0.warehouse) as wh, a0.factory as factory
					  ,max(a0.unit) as UNIT    
				      ,sum(a0.qtty) as in_qtty
				      ,round(sum(a0.qtty * a0.harga * a0.rate),0) as in_amount
				      ,round(sum(case when a0.kurs='001' then case when rate0=0 then 0 else a0.qtty * a0.harga/rate0 end  
				                      when a0.kurs='002' then a0.qtty * a0.harga
				                      when a0.kurs='004' then a0.qtty * a0.harga else 0 END),2) as in_amount_usd
				from(
						SELECT a0.vendorid, a0.warehouse, a0.poNumber
				               ,COALESCE(a1.headerMatCode,pd0.POMD_CODE,pd1.POMD_CODE) as kode, a1.unit, a1.qtty 
				               ,coalesce(p0.POMH_UMCD,p1.POMH_UMCD) as kurs
				               ,coalesce(pd0.POMD_PRIC,pd1.POMD_PRIC) as harga
				               ,a0.tanggal
				               ,case when coalesce(p0.POMH_UMCD,p1.POMH_UMCD)='001' then 1 else r0.EXRA_RATE end as rate
				               ,case when v0.vend_imex='N' then 1 else 2 end as import                                   
				               ,r0.EXRA_RATE as rate0, coalesce(p0.POMH_FACT,p1.POMH_FACT,
				               	CASE WHEN (LEFT(a1.headerPONumber,2) = 'BM' OR LEFT(a1.headerPONumber,2) = 'PM' OR LEFT(a1.headerPONumber,2) = 'PS' 
				               			   OR LEFT(a1.headerPONumber,2) = '') THEN 'PM' ELSE 'IR2' END) factory
				        from  ". $this->inspecth ." as a0 with(nolock)
				        inner join ". $this->inspectd ." as a1 with(nolock) on a0.id=a1.headerid
				        left join ". $this->pomxxh ." as p0 with(nolock) on p0.POMH_PONO=a0.poNumber
				        left join ". $this->pomexh ." as p1 with(nolock) on p1.POMH_PONO=a0.poNumber
				        left join ". $this->pomxxd ." as pd0 with(nolock) on pd0.POMD_PONO=a0.poNumber and pd0.POMD_SEQN= a1.headerSEQN
				        left join ". $this->pomexd ." as pd1 with(nolock) on pd1.POMD_PONO=a0.poNumber and pd1.POMD_SEQN= a1.headerSEQN   
				        left join ". $this->exraxm ." as r0 on r0.EXRA_CURR='IDR' and r0.EXRA_DIVI='1' and r0.EXRA_STDT=a0.tanggal                         
				        left join ". $this->vendor ." as v0 on v0.vend_vend=a0.vendorid collate database_default                            
				        where a0.tanggal BETWEEN '". $tgl1 ."' AND '". $tgl2 ."'";
						if($matl != 'ALL'){
							$sql.= "AND  COALESCE(a1.headerMatCode,pd0.POMD_CODE, pd1.POMD_CODE) LIKE '". $matl ."%'";
						}else{
							// $sql.= "and a1.headerMatCode is not null";
						}

						if($wh == 'MAT'){
							$sql.= "AND a0.warehouse LIKE '". $wh ."%'
									AND left(COALESCE(a1.headerMatCode,pd0.POMD_CODE, pd1.POMD_CODE),1)  != 'X'";
						}elseif($wh == 'ENG'){
							$sql.= "AND left(COALESCE(a1.headerMatCode,pd0.POMD_CODE, pd1.POMD_CODE),1) = 'X'";
						}
		$sql.= "        union all
						SELECT a0.vendorid, a0.warehouse, a0.poNumber
							   ,COALESCE(a1.headerMatCode,pd0.POMD_CODE, pd1.POMD_CODE) as kode, a1.unit, B.Qtty *-1
							   ,coalesce(p0.POMH_UMCD,p1.POMH_UMCD) as kurs
							   ,coalesce(pd0.POMD_PRIC,pd1.POMD_PRIC) as harga
							   ,a0.tanggal
							   ,case when coalesce(p0.POMH_UMCD,p1.POMH_UMCD)='001' then 1 else r0.EXRA_RATE end as rate
							   ,case when v0.vend_imex='N' then 1 else 2 end as import                                   
							   ,r0.EXRA_RATE as rate0, coalesce(p0.POMH_FACT,p1.POMH_FACT,
							   CASE WHEN (LEFT(a1.headerPONumber,2) = 'BM' OR LEFT(a1.headerPONumber,2) = 'PM' OR LEFT(a1.headerPONumber,2) = 'PS' 
										  OR LEFT(a1.headerPONumber,2) = '') THEN 'PM' ELSE 'IR2' END) factory
						FROM ". $this->irijecth ." A
						LEFT JOIN ". $this->irijectd ." B ON A.id = B.headerID
						inner join ". $this->inspectd ." as a1 with(nolock) on A1.line=B.InspectionD_line
						LEFT JOIN ". $this->inspecth ." AS a0 ON a0.Id = a1.headerID
				        left join ". $this->pomxxh ." as p0 with(nolock) on p0.POMH_PONO=a0.poNumber
				        left join ". $this->pomexh ." as p1 with(nolock) on p1.POMH_PONO=a0.poNumber
				        left join ". $this->pomxxd ." as pd0 with(nolock) on pd0.POMD_PONO=a0.poNumber and pd0.POMD_SEQN= a1.headerSEQN
				        left join ". $this->pomexd ." as pd1 with(nolock) on pd1.POMD_PONO=a0.poNumber and pd1.POMD_SEQN= a1.headerSEQN   
				        left join ". $this->exraxm ." as r0 on r0.EXRA_CURR='IDR' and r0.EXRA_DIVI='1' and r0.EXRA_STDT=a0.tanggal                         
				        left join ". $this->vendor ." as v0 on v0.vend_vend=a0.vendorid collate database_default    
						WHERE divi =1
						AND a.tanggal BETWEEN '". $tgl1 ."' AND '". $tgl2 ."'";
						if($matl != 'ALL'){
							$sql.= "AND  COALESCE(a1.headerMatCode,pd0.POMD_CODE, pd1.POMD_CODE) LIKE '". $matl ."%'";
						}else{
							// $sql.= "AND B.header_MR_mateial is not null";
						}
						if($wh == 'MAT'){
							$sql.= "AND a0.warehouse LIKE '". $wh ."%'
									AND left(COALESCE(a1.headerMatCode,pd0.POMD_CODE, pd1.POMD_CODE),1)  != 'X'";
						}elseif($wh == 'ENG'){
							$sql.= "AND left(COALESCE(a1.headerMatCode,pd0.POMD_CODE, pd1.POMD_CODE),1) = 'X'";
						}
		$sql.= ") as a0
				where 1=1";
				if($fact != 'ALL'){
					$sql.= "AND a0.factory LIKE '". $fact ."%'";
				}
		$sql.= "GROUP BY a0.kode, a0.factory
				order by A0.kode";
		return $this->db->query($sql)->result();
	}

	public function getTransactionOutBacodeOld($tgl1, $tgl2, $wh, $fact, $matl){
		$sql = "select a0.kode, max(a0.warehouse) as wh, sum(a0.qtty) as out_qtty,max(a0.unit) as UNIT, a0.factory as factory        
				from (
						select  CASE WHEN LEFT(a1.header_MR_mateial,1) = 'X' THEN 'ENG' ELSE a0.warehouse END warehouse, COALESCE(a1.header_MR_mateial collate database_default, pd0.POMD_CODE, pd1.POMD_CODE,'') as kode, a1.Qtty, a1.unit, coalesce(p0.POMH_FACT,p1.POMH_FACT,
				               	CASE WHEN (LEFT(a2.headerPONumber,2) = 'BM' OR LEFT(a2.headerPONumber,2) = 'PM' OR LEFT(a2.headerPONumber,2) = 'PS'
				               			   OR LEFT(a2.headerPONumber,2) = '') THEN 'PM' ELSE 'IR2' END) factory               
						from    ". $this->outbarcodeh ." as a0
						inner join ". $this->outbarcoded ." as a1 on a0.id=a1.headerID
						inner join ". $this->inspectd ." as a2 with(nolock) on a1.InspectionD_line=a2.line
				        left join ". $this->pomxxh ." as p0 with(nolock) on p0.POMH_PONO=a2.headerPONumber
				        left join ". $this->pomexh ." as p1 with(nolock) on p1.POMH_PONO=a2.headerPONumber
				        left join ". $this->pomxxd ." as pd0 with(nolock) on pd0.POMD_PONO=a2.headerPONumber and pd0.POMD_SEQN= a2.headerSEQN
				        left join ". $this->pomexd ." as pd1 with(nolock) on pd1.POMD_PONO=a2.headerPONumber and pd1.POMD_SEQN= a2.headerSEQN 
						where a0.tanggal BETWEEN '". $tgl1 ."' AND '". $tgl2 ."'";
						if($matl != 'ALL'){
							$sql.= "AND  COALESCE(a1.header_MR_mateial collate database_default, pd0.POMD_CODE, pd1.POMD_CODE,'') LIKE '". $matl ."%'";
						}else{
							// $sql.= "AND  a1.header_MR_mateial is not null";
						}

		$sql.= "	 )a0
				WHERE 1=1";
				if($wh == 'MAT'){
					$sql.= "AND a0.warehouse LIKE '". $wh ."%'
						    AND LEFT(a0.kode,1) != 'X'";
				}elseif($wh == 'ENG'){
					$sql.= "AND LEFT(a0.kode,1) = 'X'";
				}
				if($fact != 'ALL'){
					$sql.= "AND a0.factory LIKE '". $fact ."%'";
				}
		$sql.= "group by  a0.kode, a0.factory
				order by a0.kode";
		return $this->db->query($sql)->result();
	}

	public function loadDataStock($tgl, $wh, $fact, $matl){
		$sql = "select BCDH_WHID wh, BCDH_CODE, BCDH_CQTY, BCDH_CAMT, BCDH_CAMT_OTH, BCDH_FACT
				FROM ". $this->tmstock ."
				WHERE BCDH_PERD = '". $tgl ."'
				AND BCDH_CQTY <> 0";
				if($wh == 'MAT'){
					$sql.= "AND BCDH_WHID = '". $wh ."'
							AND left(BCDH_CODE,1) != 'X'";
				}elseif($wh == 'ENG'){
					$sql.= "AND left(BCDH_CODE,1) = 'X'";
				}
				if($fact != 'ALL'){
					$sql.=" AND BCDH_FACT LIKE '". $fact ."%'";
				}
				if($matl != 'ALL'){
					$sql.=" AND BCDH_CODE LIKE '". $matl ."%'";
				}

		$sql.="		ORDER BY BCDH_CODE";
		return $this->db->query($sql)->result();
	}

	public function closingBarcodeMonth($wh, $tgl1, $tgl2, $prev_date){
	}

	public function transferNextMonthStock($wh, $tgl1, $tgl2){
		$sql ="insert INTO ". $this->tmstock ." (BCDH_PERD,BCDH_DIVI,BCDH_WHID,BCDH_CODE,BCDH_BQTY,BCDH_BAMT,BCDH_BAMT_OTH,
														   BCDH_IQTY,BCDH_IAMT,BCDH_IAMT_OTH,BCDH_OQTY,BCDH_OAMT,BCDH_OAMT_OTH,BCDH_CQTY,
														   BCDH_CAMT,BCDH_CAMT_OTH)
				SELECT '". $tgl1 ."' BCDH_PERD,BCDH_DIVI,BCDH_WHID,BCDH_CODE,BCDH_CQTY,BCDH_CAMT,BCDH_CAMT_OTH, 0, 0, 0, 0, 0, 0, 0, 0, 0
				FROM ". $this->tmstock ."
				WHERE BCDH_PERD = '". $tgl2 ."'
				AND BCDH_WHID = '". $wh ."'";
		$this->db->query($sql)->result();
	}

	public function saveBarcodeStockMonthly($list, $lastDate, $wh){
		// $p = 0;
		// $k = 0;
		// $ttl_dt = 0;
		// $ttl_lp = 0;

		$tb = "";
		$t = "";
		$lkode="";
		$p = 0;

			$tb = "##T" . $this->createTableTempID();
			$this->DeleteTempTabel($tb);

	        $s = "create table ". $tb ." 
	        	  ( [BCDH_PERD] [varchar] (10) COLLATE Korean_Wansung_CI_AS NOT NULL,
					[BCDH_DIVI] [smallint] NULL,
					[BCDH_WHID] [varchar] (20) COLLATE Korean_Wansung_CI_AS NOT NULL,
					[BCDH_CODE] [varchar] (50) COLLATE Korean_Wansung_CI_AS NOT NULL,
					[BCDH_BQTY] [decimal] (18, 4) NULL,
					[BCDH_BAMT] [decimal] (18, 4) NULL,
					[BCDH_BAMT_OTH] [decimal] (18, 4) NULL,
					[BCDH_IQTY] [decimal] (18, 4) NULL,
					[BCDH_IAMT] [decimal] (18, 4) NULL,
					[BCDH_IAMT_OTH] [decimal] (18, 4) NULL,
					[BCDH_OQTY] [decimal] (18, 4) NULL,
					[BCDH_OAMT] [decimal] (18, 4) NULL,
					[BCDH_OAMT_OTH] [decimal] (18, 4) NULL,
					[BCDH_CQTY] [decimal] (18, 4) NULL,
					[BCDH_CAMT] [decimal] (18, 4) NULL,
					[BCDH_CAMT_OTH] [decimal] (18, 4) NULL,
					[BCDH_CPRC_OTH] [decimal] (18, 4) NULL,
					[BCDH_CPRC] [decimal] (18, 4) NULL,
					[BCDH_FACT] [varchar] (20) COLLATE Korean_Wansung_CI_AS NOT NULL				  
	        	  )";
	        $this->db->query($s);

	        foreach($list as $lt){
	        	$si = "insert INTO ". $tb ." (BCDH_PERD,BCDH_DIVI,BCDH_WHID,BCDH_CODE,BCDH_BQTY,BCDH_BAMT,BCDH_BAMT_OTH,BCDH_IQTY,\n
		 									  BCDH_IAMT,BCDH_IAMT_OTH,BCDH_OQTY,BCDH_OAMT,BCDH_OAMT_OTH,BCDH_CQTY,BCDH_CAMT,BCDH_CAMT_OTH,\n
											  BCDH_CPRC_OTH,BCDH_CPRC,BCDH_FACT)\n";
	        	$si.= "select '". $lastDate ."' BCDH_PERD, 0 BCDH_DIVI, '". $lt['wh'] ."' BCDH_WHID, '". $lt['kode'] ."' BCDH_CODE, ". $lt['qty_sa'] ." BCDH_BQTY, ". $lt['nil_sa'] ." BCDH_BAMT, ". $lt['nil_sa_usd'] ." BCDH_BAMT_OTH, ". $lt['qty_in'] ." BCDH_IQTY, ". $lt['nil_in'] ." BCDH_IAMT, ". $lt['nil_in_usd'] ." BCDH_IAMT_OTH, ". $lt['qty_out'] ." BCDH_OQTY, ". $lt['nil_out'] ." BCDH_OAMT, ". $lt['nil_out_usd'] ." BCDH_OAMT_OTH, ". $lt['qty_bal'] ." BCDH_CQTY, ". $lt['nil_bal'] ." BCDH_CAMT, ". $lt['nil_bal_usd'] ." BCDH_CAMT_OTH, ". $lt['priceAvg'] ." BCDH_CPRC_OTH, ". $lt['priceAvg_conv'] ." BCDH_CPRC, '". $lt['factory'] ."' BCDH_FACT";
	        	$this->db->query($si);
	        }
	        $t = $tb;

	    	$sql="insert into ". $this->tmstock ." (BCDH_PERD,BCDH_DIVI,BCDH_WHID,BCDH_CODE,BCDH_BQTY,BCDH_BAMT,BCDH_BAMT_OTH,BCDH_IQTY,\n
		 									  		BCDH_IAMT,BCDH_IAMT_OTH,BCDH_OQTY,BCDH_OAMT,BCDH_OAMT_OTH,BCDH_CQTY,BCDH_CAMT,BCDH_CAMT_OTH,\n
											  		BCDH_CPRC_OTH,BCDH_CPRC,BCDH_FACT)\n
				  select * from ". $tb;
			$this->db->query($sql);


	    	if($tb != ""){
				$this->DeleteTempTabel($tb);
			}

		// foreach($list as $lt){
		// 	$ttl_dt = count($list) - $p;
		// 	if($ttl_dt > 500){
		// 		if($k == 0){
		// 			$t="insert into ". $this->tmstock ." \n
		// 				VALUES ('BCDH_PERD','BCDH_DIVI','BCDH_WHID','BCDH_CODE','BCDH_BQTY','BCDH_BAMT','BCDH_BAMT_OTH','BCDH_IQTY',\n
		// 				'BCDH_IAMT','BCDH_IAMT_OTH','BCDH_OQTY','BCDH_OAMT','BCDH_OAMT_OTH','BCDH_CQTY','BCDH_CAMT','BCDH_CAMT_OTH',\n
		// 				'BCDH_CPRC_OTH','BCDH_CPRC','BCDH_FACT')\n";

		// 	    	$t.= "select '". $lastDate ."', 0, '". $lt['wh'] ."', '". $lt['kode'] ."', ". $lt['qty_sa'] .", ". $lt['nil_sa'] .", ". $lt['nil_sa_usd'] .", ". $lt['qty_in'] .", ". $lt['nil_in'] .", ". $lt['nil_in_usd'] .", ". $lt['qty_out'] .", ". $lt['nil_out'] .", ". $lt['nil_out_usd'] .", ". $lt['qty_bal'] .", ". $lt['nil_bal'] .", ". $lt['nil_bal_usd'] .", ". $lt['priceAvg'] .", ". $lt['priceAvg_conv'] .", '". $lt['factory'] ."' \n";
		// 	    }else{
		// 			$t.= "union all \n";
		// 	    	$t.= "select '". $lastDate ."', 0, '". $lt['wh'] ."', '". $lt['kode'] ."', ". $lt['qty_sa'] .", ". $lt['nil_sa'] .", ". $lt['nil_sa_usd'] .", ". $lt['qty_in'] .", ". $lt['nil_in'] .", ". $lt['nil_in_usd'] .", ". $lt['qty_out'] .", ". $lt['nil_out'] .", ". $lt['nil_out_usd'] .", ". $lt['qty_bal'] .", ". $lt['nil_bal'] .", ". $lt['nil_bal_usd'] .", ". $lt['priceAvg'] .", ". $lt['priceAvg_conv'] .", '". $lt['factory'] ."' \n";
		// 	    }
		// 	    $k = $k + 1;
		// 	    if($k == 500){
		// 	    	$k = 0;
		// 			$myfile = fopen("D:newfile.txt", "w") or die("Unable to open file!");
		// 			$txt = $t;
		// 			fwrite($myfile, $txt);
		// 			fclose($myfile);
		// 	    	// $this->db->query($t);
		// 	    }
		//     }else{
		// 		if($k == 0){
		// 			$t="insert into ". $this->tmstock ." \n
		// 				VALUES ('BCDH_PERD','BCDH_DIVI','BCDH_WHID','BCDH_CODE','BCDH_BQTY','BCDH_BAMT','BCDH_BAMT_OTH','BCDH_IQTY',\n
		// 				'BCDH_IAMT','BCDH_IAMT_OTH','BCDH_OQTY','BCDH_OAMT','BCDH_OAMT_OTH','BCDH_CQTY','BCDH_CAMT','BCDH_CAMT_OTH',\n
		// 				'BCDH_CPRC_OTH','BCDH_CPRC','BCDH_FACT')\n";
						
		// 	    	$t.= "select '". $lastDate ."', 0, '". $lt['wh'] ."', '". $lt['kode'] ."', ". $lt['qty_sa'] .", ". $lt['nil_sa'] .", ". $lt['nil_sa_usd'] .", ". $lt['qty_in'] .", ". $lt['nil_in'] .", ". $lt['nil_in_usd'] .", ". $lt['qty_out'] .", ". $lt['nil_out'] .", ". $lt['nil_out_usd'] .", ". $lt['qty_bal'] .", ". $lt['nil_bal'] .", ". $lt['nil_bal_usd'] .", ". $lt['priceAvg'] .", ". $lt['priceAvg_conv'] .", '". $lt['factory'] ."' \n";
		// 	    }else{
		// 			$t.= "union all \n";
		// 	    	$t.= "select '". $lastDate ."', 0, '". $lt['wh'] ."', '". $lt['kode'] ."', ". $lt['qty_sa'] .", ". $lt['nil_sa'] .", ". $lt['nil_sa_usd'] .", ". $lt['qty_in'] .", ". $lt['nil_in'] .", ". $lt['nil_in_usd'] .", ". $lt['qty_out'] .", ". $lt['nil_out'] .", ". $lt['nil_out_usd'] .", ". $lt['qty_bal'] .", ". $lt['nil_bal'] .", ". $lt['nil_bal_usd'] .", ". $lt['priceAvg'] .", ". $lt['priceAvg_conv'] .", '". $lt['factory'] ."' \n";
		// 	    }
		// 	    $k = $k + 1;
		// 	    if($ttl_dt == 0){
		// 	    	$k = 0;
		// 			$myfile = fopen("D:newfile2.txt", "w") or die("Unable to open file!");
		// 			$txt = $t;
		// 			fwrite($myfile, $txt);
		// 			fclose($myfile);
		// 	    	// $this->db->query($t);
		// 	    }
		//     }
		//     $p = $p + 1;
		// 	// $dt = array(
		// 	// 			'BCDH_PERD'=>$lastDate,
		// 	// 			'BCDH_DIVI'=> 0,
		// 	// 			'BCDH_WHID'=>$lt['wh'],
		// 	// 			'BCDH_CODE'=>$lt['kode'],
		// 	// 			'BCDH_BQTY'=>$lt['qty_sa'],
		// 	// 			'BCDH_BAMT'=>$lt['nil_sa'],
		// 	// 			'BCDH_BAMT_OTH'=>$lt['nil_sa_usd'],
		// 	// 			'BCDH_IQTY'=>$lt['qty_in'],
		// 	// 			'BCDH_IAMT'=>$lt['nil_in'],
		// 	// 			'BCDH_IAMT_OTH'=>$lt['nil_in_usd'],
		// 	// 			'BCDH_OQTY'=>$lt['qty_out'],
		// 	// 			'BCDH_OAMT'=>$lt['nil_out'],
		// 	// 			'BCDH_OAMT_OTH'=>$lt['nil_out_usd'],
		// 	// 			'BCDH_CQTY'=>$lt['qty_bal'],
		// 	// 			'BCDH_CAMT'=>$lt['nil_bal'],
		// 	// 			'BCDH_CAMT_OTH'=>$lt['nil_bal_usd'],
		// 	// 			'BCDH_CPRC_OTH'=>$lt['priceAvg'],
		// 	// 			'BCDH_CPRC'=>$lt['priceAvg_conv'],
		// 	// 			'BCDH_FACT'=>$lt['factory']
		// 	// );

		// 	// $this->db->set($dt);
		// 	// $this->db->insert($this->tmstock);
		// }
		// // $this->db->query($t);
	}

	public function deleteBarcodeStockMonthly($lastDate, $wh, $fact)
	{
		if($wh != 'ALL' and $fact != 'ALL'){
			$param= array('BCDH_PERD'=>$lastDate,
						  'BCDH_WHID'=>$wh,
						  'BCDH_FACT'=>$fact
						 );
		}elseif($wh != 'ALL' and $fact == 'ALL'){
			$param= array('BCDH_PERD'=>$lastDate,
						  'BCDH_WHID'=>$wh
						 );
		}elseif($wh == 'ALL' and $fact != 'ALL'){
			$param= array('BCDH_PERD'=>$lastDate,
						  'BCDH_FACT'=>$fact
						 );
		}else{
			$param= array('BCDH_PERD'=>$lastDate);
		}

		$this->db->where($param);
		$this->db->delete($this->tmstock);
	}

	public function deleteSeriBarcodeStockMonthly($lastDate, $wh, $fact)
	{
		if($fact != 'ALL'){
			$param= array('BRCD_DATE'=>$lastDate,
						  'BRCD_FACT'=>$fact
						 );
		}else{
			$param= array('BRCD_DATE'=>$lastDate);
		}

		$this->db->where($param);
		$this->db->delete($this->seristock);
	}

	public function saveSerialBarcodeStockMonthly($prev_dt, $dt1, $dt2, $wh, $fact){
		$list = $this->TransferSerialBarcode($prev_dt, $dt1, $dt2, $wh, $fact);
		foreach($list as $lt){
			$dt = array(
						'BRCD_DATE'=>$lt->periode,
						'BRCD_LINE'=>$lt->line,
						'BRCD_CODE'=>$lt->headerMatCode,
						'BRCD_QTTY'=>$lt->qtty,
						'BRCD_FACT'=>$lt->factory
			);
			$this->db->set($dt);
			$this->db->insert($this->seristock);
		}

	}

	// public function saveSerialBarcodeMonthly(){

	// }

}

/* End of file  */
/* Location: ./application/models/ */