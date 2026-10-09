<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mezzanine_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database(); 
    }

    // 1. Cek Qty (Digunakan saat scan awal DAN saat klik tombol Transfer)
    public function cek_validasi_barcode($barcode) {
        $sql = "SELECT A.INSD_LINE as line, A.INSD_CODE as mat_code, A.INSD_BQTY as qtyakhir, 
                       A.INSD_NAME as matname, 
                       B.TBQR_PLCD as loc_code, B.TBQR_DIVI as divi, B.TBQR_USER as user_id,
                       C.LOCATION_NAME as loc_name, C.LOCATION_TYPE as loc_type, C.LOCATION_ROW as loc_row
                FROM PRTMMRP.PRTM.TM_Mi_INSPECTD A
                LEFT JOIN PRTMMRP.PRTM.TO_PALETTE_Inspection B ON B.TBQR_LINE = A.INSD_LINE  
                LEFT JOIN PRTMMRP.PRTM.TO_PALETTE_MSCODE C ON C.LOCATION_CODE = B.TBQR_PLCD
                WHERE A.INSD_LINE = ? 
                AND A.INSD_BQTY > 0";
                
        return $this->db->query($sql, array($barcode))->row(); 
    }

    // 2. Cek apakah ada di lokasi yang sesuai (Rak atau Mezzanine) [MODE TRANSFER]
    public function cek_duplicate($barcode, $tipe_lokasi) {
        $sql = "SELECT COUNT(*) as JML 
                FROM PRTMMRP.PRTM.TO_PALETTE_Inspection A
                LEFT JOIN PRTMMRP.PRTM.TO_PALETTE_MSCODE B ON B.LOCATION_CODE = A.TBQR_PLCD
                WHERE A.TBQR_LINE = ? 
                AND B.LOCATION_TYPE = ?";
                
        return $this->db->query($sql, array($barcode, $tipe_lokasi))->row()->JML;
    }

    // 3. Jalankan Update dengan JOIN ke MSCODE [MODE TRANSFER]
    public function update_transfer_lokasi($barcode, $lokasi, $user, $tipe_lokasi) {
        $sql = "UPDATE A 
                SET A.TBQR_PLCD = ?, 
                    A.TBQR_USER = ?,
                    A.TBQR_DIVI = ?
                FROM PRTMMRP.PRTM.TO_PALETTE_Inspection A
                LEFT JOIN PRTMMRP.PRTM.TO_PALETTE_MSCODE B ON B.LOCATION_CODE = A.TBQR_PLCD
                WHERE A.TBQR_LINE = ? 
                AND B.LOCATION_TYPE = ?";
                
        $this->db->query($sql, array($lokasi, $user, $tipe_lokasi, $barcode, $tipe_lokasi));
        return true; 
    }

    // Cek tipe lokasi berdasarkan master data MSCODE
    public function get_location_type($location_code) {
        $sql = "SELECT LOCATION_TYPE 
                FROM PRTMMRP.PRTM.TO_PALETTE_MSCODE 
                WHERE LOCATION_CODE = ?";
                
        $row = $this->db->query($sql, array($location_code))->row();
        return $row ? $row->LOCATION_TYPE : null; 
    }

    // =======================================================
    // FUNGSI BARU UNTUK MODE: SCAN
    // =======================================================

    // Cek apakah data sudah ada di TO_PALETTE_Inspection
    public function cek_exist_inspection($barcode) {
        $sql = "SELECT TBQR_LINE FROM PRTMMRP.PRTM.TO_PALETTE_Inspection WHERE TBQR_LINE = ?";
        return $this->db->query($sql, array($barcode))->num_rows() > 0;
    }

    // Insert jika data belum ada di tabel TO_PALETTE_Inspection
    public function insert_inspection($barcode, $lokasi, $user) {
        $sql = "INSERT INTO PRTMMRP.PRTM.TO_PALETTE_Inspection (TBQR_PLCD, TBQR_LINE, TBQR_USER, TBQR_DTTM, TBQR_DIVI)
                SELECT 
                    A.lokasi, 
                    A.barcode, 
                    A.userid, 
                    GETDATE(), 
                    B.LOCATION_TYPE
                FROM (SELECT ? AS lokasi, ? AS barcode, ? AS userid) A
                LEFT JOIN PRTMMRP.PRTM.TO_PALETTE_MSCODE B ON B.LOCATION_CODE = A.lokasi";
                
        return $this->db->query($sql, array($lokasi, $barcode, $user));
    }

    // 2. Update jika data sudah ada (Juga disesuaikan dengan LEFT JOIN)
    public function update_inspection_scan($barcode, $lokasi, $user) {
        $sql = "UPDATE A
                SET A.TBQR_PLCD = ?, 
                    A.TBQR_USER = ?, 
                    A.TBQR_DTTM = GETDATE(), 
                    A.TBQR_DIVI = B.LOCATION_TYPE
                FROM PRTMMRP.PRTM.TO_PALETTE_Inspection A
                LEFT JOIN PRTMMRP.PRTM.TO_PALETTE_MSCODE B ON B.LOCATION_CODE = ?
                WHERE A.TBQR_LINE = ?";
                
        // Urutan parameter: Lokasi (PLCD), User, Lokasi (Untuk JOIN MSCODE), Barcode (WHERE)
        return $this->db->query($sql, array($lokasi, $user, $lokasi, $barcode));
    }


    // Fungsi untuk cek ketersediaan kode rack di tabel Master MSCODE
    public function cek_master_lokasi($location_code) {
        $sql = "SELECT LOCATION_CODE, LOCATION_NAME, LOCATION_TYPE
                FROM PRTMMRP.PRTM.TO_PALETTE_MSCODE 
                WHERE LOCATION_CODE = ?";
                
        return $this->db->query($sql, array($location_code))->row();
    }
}