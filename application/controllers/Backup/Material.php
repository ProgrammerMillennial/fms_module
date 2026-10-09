<?php

include_once (dirname(__FILE__) . "/Master.php");
include_once (dirname(__FILE__) . "/Tes.php");

class Material extends Master {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_model', 'master');
    }

    public function index()
    {
        $this->template->load('template', 'dashboard');
    }

    public function warehouse()
    {
        $pg = $this->uri->segment(3,0);
        if ($pg == '1') {
            $data['list'] =array();
        }else{
            $data['list'] = $this->master->getWarehouselist();
        }
        $this->load->view('button');
        $this->template->load('template', 'warehouseAkses', $data);
    }

    public function rack()
    {
        
        $pg = $this->uri->segment(3,0);
        if ($pg == '1') {
            $data['rack'] =array();
        }else{
            $data['rack'] = $this->master->getWarehouserack();
        }
        $this->template->load('template', 'locationrack', $data);
    }

    public function barcode_transaction()
    {
        $pg = $this->uri->segment(3,0);
        if ($pg == '1' or $this->input->post('barcode') == '') {
            $row = array(
                         "barcode" =>"",
                         "trans_in" =>"",
                         "tgl_in" => "",
                         "sj_no" => "",
                         "pono" => "",
                         "qty_in" =>"",
                         "trans_out" =>"",
                         "tgl_out" =>"",
                         "mrno" =>"",
                         "qty_out" =>"",
                         "qty_akhir" =>"",
                         "nama" =>"",
                         "mat_code" =>""
                        );
        }else{
            $barcode = $this->input->post('barcode');
            $list = $this->master->getTransactionBarcode($barcode);
            $data = array();
            foreach ($list as $val) {

                $nama = $this->getMaterialNama($val->mat_code);

                $row = array(
                              "barcode" => $val->line,
                              "trans_in" => $val->id_in,
                              "tgl_in" => $val->tgl_in,
                              "sj_no" => $val->sjno,
                              "pono" => $val->pono,
                              "qty_in" => $val->qty_in,
                              "trans_out" => $val->id_out,
                              "tgl_out" => $val->tgl_out,
                              "mrno" => $val->mr_no,
                              "qty_out" => $val->qty_out,
                              "qty_akhir" => floatval($val->qtyakhir),
                              "nama" => $nama,
                              "mat_code" =>$val->mat_code
                            );
            }

        }

        $data['trans'] = $row;

        $this->template->load('template', 'barcode_transaction', $data);
    }

}