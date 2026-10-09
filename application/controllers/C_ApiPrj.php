<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Report.php");

class C_ApiPrj extends Report {
	
	var $vUrl_token = 'https://nike.okta.com/oauth2/aus27z7p76as9Dz0H1t7/v1/token';
	var $vUrl_Aperture = 'https://nikescpprod.apimanagement.us2.hana.ondemand.com/scp/SOMassCreate';

	var $client_secret = 'eo2qAda7gDSClEA9sG8cNxZVbsg9VC7jr7yG4NsVsLiGpPZ7iYPLgMg16e7VHQZ2';
	var $mClientId = 'pratama.nikedm.api';

	public function index()
	{
		
	}

	public function sendDataBL(){
		$noHostBl = $_GET['blno'];
		$tglHostBl = $_GET['bltgl'];
		$curl = curl_init();
		$pUrl = 'http://192.168.88.129/automated_project/Master/responBL?'.'noHostBl='. $noHostBl .'&tglHostBl='. $tglHostBl;

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $pUrl,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Cookie: ci_session=a3f6kbcjhhoanmiqdeuut0jdtqg7m68u'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		header('Content-Type: application/json');
		echo $response;
	}

	public function sendPOAperture(){
	$pono = $this->uri->segment(3);
	$lst = $this->master->getDataPOAperture($pono);
	$vendorCode = 'PM';
	$ShipTo = '5095155';
	$ResultEmail = 'purchase.afifah@pratama.net';
	$TestMode = false;
	$Data = '';

	$token = $this->getTokenAperture();

	$dt = array();
		foreach ($lst as $v) {
			$po = array('PoNum' => $v->POMD_PONO,
						'Material' => $v->oiaItem,
				           'Quantity' => floatval($v->POMD_QTTY),
				           'Date' => date("m/d/Y", strtotime($v->POMH_DUDT)),
				           'Note' => $v->POMD_DESC
				        );
			$dt[] = $po;
		}
	$a=json_encode($dt);
	$Data = urlencode($a);

	$pData = "VendorCode=" . $vendorCode . "&ShipTo=" . $ShipTo . "&ResultEmail=". $ResultEmail . "&TestMode=" . $TestMode . "&Data=" . $Data

		// $curl = curl_init();

		// curl_setopt_array($curl, array(
		//   CURLOPT_URL => $this->vUrl_Aperture,
		//   CURLOPT_RETURNTRANSFER => true,
		//   CURLOPT_ENCODING => '',
		//   CURLOPT_MAXREDIRS => 10,
		//   CURLOPT_TIMEOUT => 0,
		//   CURLOPT_FOLLOWLOCATION => true,
		//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		//   CURLOPT_CUSTOMREQUEST => 'POST',
		//   CURLOPT_POSTFIELDS =>$pData,
		//   CURLOPT_HTTPHEADER => array(
		//     'Authorization: Bearer '. $token,
		//     'Content-Type: application/x-www-form-urlencoded'
		//   ),
		// ));

		// $response = curl_exec($curl);

		// curl_close($curl);
		header('Content-Type: application/json');
		echo $token;
	}

	public function getTokenAperture(){
		$data = "client_id=". $this->mClientId . "&client_secret=". $this->client_secret . "&grant_type=client_credentials";

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->vUrl_token,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $data,
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/x-www-form-urlencoded'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;

	}
}

/* End of file c_ApiPrj.php */
/* Location: ./application/controllers/c_ApiPrj.php */