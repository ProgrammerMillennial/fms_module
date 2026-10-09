<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
include_once (dirname(__FILE__) . "/Master_model.php");

class Inspectionh_Model extends Master_model {

	// var $table =  'PRTMMRP.PRTM.TM_MI_INSPECTH';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getIdHeader(){
		$sql = "select CONVERT(VARCHAR(10),GETDATE(), 112) bln, COUNT(INSH_IDXX) NOMER
				FROM ". $this->inspecth2 ." WHERE LEFT(LTRIM((REPLACE(INSH_IDXX,'INH',''))),8) = CONVERT(VARCHAR(11),GETDATE(), 112)";

		return $this->db->query($sql)->result();
	}

	public function CalculateHeader($data, $ttlQtty)
	{	
		$sj = '';
		$idH = $this->getIdHeader();
			foreach ($idH as $value) {
				$urut = $value->NOMER + 1;
				$line = 'INH'. $value->bln.'-'. sprintf("%05s", $urut);
			}

		$details = array();
		$param= array();
		$trans = '';
		$tranno = '';
	    foreach ($data as $lst) {
	    	if($sj != $lst['sjno']){
	    		$trans = $lst['tranno'];
	    		if($trans != ''){
	    			$line = $lst['tranno'];
	    			$tranno = $lst['tranno'];
	    		}

	    		if($lst['vstat']==0){
			        $pono = $lst['pono'];
			        $release =$lst['release'];
			        $style =$lst['style'];
			        $vend =$lst['vend'];
			        $sjno =$lst['sjno'];
			    }else{
			        $pono = '';
			        $release ='';
			        $style ='';
			        $vend ='';
			        $sjno ='';
			    }
	    		if($tranno == ''){
	    			$details= array(
			            'INSH_IDXX'=>$line,
			            'INSH_PONO'=>$pono,
			            'INSH_PART'=>$release,
			            'INSH_NBRN'=>$style,
			           	'INSH_VEND'=>$vend,
			           	'INSH_SJNO'=>$sjno,
			            'INSH_DATE'=>$lst['tgl_inspect'],
			            'INSH_WHID'=>$lst['wh'],
			            'INSH_VSAT'=>$lst['vstat'],
			            'INSH_TOTAL'=>floatval($ttlQtty),
			            'CREATE_USER'=>$lst['user'],
			            'CREATE_DATE'=>date("Y-m-d h:i:s")
			        );
	    		}else{
					$details= array(
			            'INSH_SJNO'=>$sjno,
			            'INSH_TOTAL'=>floatval($ttlQtty),
			            'UPDATE_USER'=>$lst['user'],
			            'UPDATE_DATE'=>date("Y-m-d h:i:s")
			        );
			       	$param= array(
			            'INSH_IDXX'=>$tranno
			        );
	    		}
	        }
	        $sj = $lst['sjno'];
	    }

	    if($tranno == ''){
	    	if($ttlQtty != 0){
			    $this->db->set($details);
				$this->db->insert($this->inspecth2);	    		
			}
		}else{
			if($ttlQtty != 0){
			$this->db->where($param);
			$this->db->update($this->inspecth2, $details);
			}else {

				$this->delete_header($tranno);

			}
		}
	    return $line;
	}

	public function delete_header($id)
	{
		$this->db->where('INSH_IDXX', $id);
		$this->db->delete($this->inspecth2);
	}

	public function updateTotalHeader($param, $data)
	{
		$this->db->where($param);
		$this->db->update($this->inspecth2, $data);
	}
}

/* End of file  */
/* Location: ./application/models/ */