<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Outinspecth_model.php");

class Laminating_model extends OutInspecth_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function loadScanLam($mesin, $dt1, $dt2){
		$sql = "select a0.LMQB_CRDT, a0.LMQB_COMP, a0.LMQB_STYL, a0.STYL_SIZE, a0.COND_CODE, a0.COND_SIZE, 
				       a0.LAMH_MCHN, a0.CODD_DESC, a0.LMQB_CODE, a0.LMQB_QTTY
				FROM (
						SELECT DISTINCT A.LMQB_CRDT, A.LMQB_COMP, A.LMQB_STYL, D.STYL_SIZE, E.COND_CODE, E.COND_SIZE, B.LAMH_MCHN, C.CODD_DESC, A.LMQB_CODE, A.LMQB_QTTY
						FROM ". $this->lampxb ." A
						LEFT JOIN ". $this->stylem ." D ON D.STYL_CODE = A.LMQB_STYL
						LEFT JOIN ". $this->trconsxd ." E ON E.COND_PART = A.LMQB_STYL
														  AND E.COND_SIZE = D.STYL_SIZE
														  AND E.COND_COMP = A.LMQB_COMP
						LEFT JOIN ( SELECT LAMH_PART,LAMH_COMP,LAMH_MCHN
									FROM ". $this->tolamxh ."
									GROUP BY LAMH_PART,LAMH_COMP,LAMH_MCHN ) B ON A.LMQB_STYL = B.LAMH_PART  AND A.LMQB_COMP = B.LAMH_COMP
						LEFT JOIN ". $this->codexd ." C ON C.CODD_FLNM = 'ITEM_COMP'
														  AND C.CODD_VALU = A.LMQB_COMP
						WHERE ISNULL(E.COND_SIZE,'') != ''
						AND B.LAMH_MCHN LIKE '". $mesin ."%'
						AND A.LMQB_CRDT BETWEEN '". $dt1 ."' AND '". $dt2 ."'
					)a0
				ORDER BY a0.LMQB_CRDT DESC, a0.LMQB_STYL, a0.LMQB_COMP";
	    return $this->db->query($sql)->result();
	}

}

/* End of file Laminating_Model.php */
/* Location: ./application/models/Laminating_Model.php */