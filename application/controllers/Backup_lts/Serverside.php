<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Serverside extends CI_Controller {

	public function index()
	{
		
	}

	function view_data_query()
	{

		$sql="  select C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty
				FROM ".$this->sjvendord." B
				LEFT JOIN ".$this->sjvendordb." C ON B.LineSj = C.LineSj
												 AND B.LineItem = C.LineItem
				LEFT JOIN ".$this->inspectd2." E ON E.INSD_LIN2 = C.linebarcode
				LEFT JOIN ".$this->sjvendor." F ON B.LineSj = F.LineSj
				LEFT JOIN ".$this->pomxxh." G ON G.POMH_PONO = B.Pono
				WHERE G.POMH_PONO IS NOT NULL
				AND E.INSD_LIN2 IS NULL
				AND C.linebarcode IS NOT NULL
				AND B.Pono NOT IN (SELECT headerPONumber FROM ".$this->inspectd." GROUP BY headerPONumber)
				GROUP BY C.linebarcode, B.itemId, B.namaitem, B.satuan, C.qtty
				ORDER BY B.itemId";

    	$query  = "SELECT kategori.nama_kategori AS nama_kategori, subkat.* FROM subkat 
                   JOIN kategori ON subkat.id_kategori = kategori.id_kategori";
        $search = array('linebarcode','itemId','namaitem', 'satuan', 'qtty');
        $where  = null; 
            // $where  = array('nama_kategori' => 'Tutorial');
            
            // jika memakai IS NULL pada where sql
        $isWhere = null;
            // $isWhere = 'artikel.deleted_at IS NULL';
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query,$search,$where,$isWhere);
    }

}

/* End of file serverside.php */
/* Location: ./application/controllers/serverside.php */