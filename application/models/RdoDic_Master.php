<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Inspection_master.php");

class RdoDic_Master extends Inspection_master {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function createTableTempID(){
	$t = "";
		$sql = "select newid() id";
		$q= $this->db->query($sql)->result();
		foreach ($q as $val) {
			$t = $val->id;
			$t = str_replace("{","",$t);
			$t = str_replace("}","",$t);
			$t = str_replace("-","",$t);
		}
		return $t;
	}

	public function DeleteTempTabel($t){
	    $sql = "IF object_id('tempdb.." . $t . "') IS NOT NULL
	    		DROP TABLE " . $t . "";
	    $this->db->query($sql);
	}

	public function testdb($table){
		$sql = "select top 0* from " . $table . "";
		return $this->db->query($sql);
	}

	public function insertBatchSql($lData, $values, $cnt, $db){
	$p = 0;	
	$t = "";
	$i = 0;
		foreach ($lData as $val) {
			if($p == 0){
		    	$t.= "select '". $val['kode'] ."' as kode \n";
		    }else{
				$t.= "union all \n";
		        $t.= "select '". $val['kode'] ."' as kode \n";
		    }
		    $p = $p + 1;
		}
	}

public function listMessage($stat){
	$message = '';
		switch ($stat) {
		    case 1: //error save out header
		        $message = 'Error while saving out barcode header !'; 
		        break;
		    case 2:
		        $message = 'Error while saving out barcode detail !'; 
		        break;
		    case 3:
		       $message = 'Error while update qtty barcode !';
		        break;
		    case 4:
		         $message = 'Error while saving data to ERP !'; 
		        break;
		    case 5:
		        $message = 'Error while saving inspect barcode header!';
		        break;
		    case 6:
		        $message = 'Error while saving inspect barcode detail!';
		        break;
		    case 7:
		        $message = 'Error while saving split barcode !';
		        break;
		    case 8:
		        $message = 'Error while delete after split barcode !';
		        break;
		    case 9:
		        $message = 'Error, barcode is empty !';
		        break;
		    case 10:
		        $message = 'Error while delete out barcode detail !'; 
		        break;
		    case 11:
		        $message = 'Error while delete split barcode !'; 
		        break;
		    case 12:
		        $message = 'Error while delete out barcode header !'; 
		        break;
		    case 13:
		         $message = 'Error while delete data ERP !'; 
		        break;
		    case 14:
		         $message = 'Error while update total header barcode !'; 
		        break;
		    case 15:
		         $message = 'Error while update status SJ Vendor !'; 
		        break;
		    case 16:
		         $message = 'Error while update data inspect PO !'; 
		        break;
		    case 17:
		         $message = 'Error while delete data inspect header !'; 
		        break;
		    case 18:
		         $message = 'Error while delete data inspect detail !'; 
		        break;
		    case 19:
		         $message = 'Error while saving data inspect header detail KK!'; 
		        break;
		    default:
		        $message = 'Error while proccessing data , re-chek again !!'; 
		        break;
		}

		return $message;
	}

}

/* End of file RdoDic_Master.php */
/* Location: ./application/models/RdoDic_Master.php */