# Dokumentasi Perubahan Modul Inspection Entry

## 1. Tujuan perubahan

Perubahan ini dibuat untuk memperbaiki alur input inspection barang saat proses penerimaan / inspeksi material. Fokus utamanya adalah membuat input data inspection lebih detail, lebih terstruktur, dan lebih aman dari kesalahan qty, LOT, serta expired date.

Sumber utama perubahan ada di:
- [application/views/transaction/modalInspectEntry.php](../application/views/transaction/modalInspectEntry.php)
- [application/views/transaction/PODetail.php](../application/views/transaction/PODetail.php)
- [application/controllers/Transaction.php](../application/controllers/Transaction.php)

---

## 2. Fungsi utama dari modul ini

Modul ini berfungsi untuk:

1. Menampilkan data head inspection dari PO / material yang sedang diproses.
2. Menampilkan detail material yang masih memiliki saldo qty yang harus diinspeksi.
3. Memasukkan data inspeksi per row dengan parameter:
   - Kemasan
   - Jumlah Kemasan
   - Qty per row
   - LOT Number
   - Expired Date
4. Membagi qty sesuai dengan jumlah kemasan agar total qty tidak melebihi saldo yang tersedia.
5. Menyimpan data inspection ke database melalui controller transaksi.
6. Menghapus detail baris tertentu atau seluruh data inspeksi bila diperlukan.
7. Mencetak label / data barcode hasil inspeksi.

---

## 3. Yang diubah secara fungsional

### A. LOT dan Expired Date sebelumnya diinput sekali untuk banyak kemasan
Sebelum perubahan, LOT Number dan Expired Date biasanya diinput satu kali saja untuk satu kelompok / banyak kemasan. Artinya, jika satu transaksi memiliki beberapa kemasan, semua baris detail cenderung mengikuti nilai yang sama.

### B. Flow baru: LOT dan Expired Date per row detail
Setelah perubahan, user mengisi data pada form Detail Inspection secara lebih spesifik per baris, yaitu:
- Kemasan
- Jumlah Kemasan
- Qty
- LOT per Kemasan
- Expired per Kemasan

Setiap row detail dibuat lengkap, sehingga LOT dan expired date bisa berbeda-beda sesuai kondisi tiap kemasan atau tiap baris inspeksi.

### C. Validasi qty dan balance
Sistem melakukan pengecekan terhadap saldo qty material. Saat user menambah data, program menghitung total qty yang akan masuk untuk memastikan jumlah tidak melebihi saldo yang tersedia.

### D. Data detail di-save secara terstruktur
Data yang disimpan tidak hanya berisi qty umum, tetapi juga mencakup:
- kemasan
- jumlah kemasan
- qty inspect
- lotnumber
- tgl_expired
- serial

Ini membuat hasil inspeksi lebih konsisten untuk kebutuhan reporting, label barcode, dan audit data di masa depan.

---

## 4. Flow lama vs flow baru

| Aspek | Flow lama | Flow baru |
|---|---|---|
| Input detail | Umum dan kurang detail | Detail per row lengkap |
| LOT Number | Diinput satu kali untuk banyak kemasan | Diinput per row detail |
| Expired Date | Diinput satu kali untuk banyak kemasan | Diinput per row detail |
| Kemasan | Hanya sebatas nama kemasan | Nama + jumlah + qty + distribusi otomatis |
| Validasi saldo | Kurang eksplisit | Dihitung dan dicek sebelum save |
| Penjumlahan qty | Lebih rawan melebihi saldo | Sistem menghitung distribusi qty secara otomatis |
| Pemeliharaan data | Lebih rawan duplikasi / inkonsistensi | Lebih rapi dan terdokumentasi |

### Flow lama
1. User membuka modal inspeksi.
2. Input qty dan data dasar saja.
3. LOT dan Expired Date biasanya dibuat satu kali untuk banyak kemasan.
4. Detail row dibuat secara sederhana dan semua baris mengikuti nilai yang sama.
5. Risiko data tidak sesuai kondisi tiap kemasan lebih tinggi.

### Flow baru
1. User membuka modal inspeksi.
2. Sistem menampilkan data head inspection dan saldo material.
3. User mengisi data per row: kemasan, qty, LOT, expired date.
4. Sistem otomatis membagi qty sesuai jumlah kemasan dan membatasi sesuai saldo.
5. User menekan Go untuk menambahkan baris detail.
6. Data disimpan melalui fungsi save / addTransaction.
7. Sistem melakukan validasi duplikasi dan saldo sebelum commit data.

---

## 5. Fungsi-fungsi yang terlibat

### Front-end / UI
- [application/views/transaction/modalInspectEntry.php](../application/views/transaction/modalInspectEntry.php)
  - Membuat form modal Inspection Entry.
  - Menangani input data detail per row.
  - Menambahkan row baru ke tabel detail.
  - Validasi form sebelum submit.

- [application/views/transaction/PODetail.php](../application/views/transaction/PODetail.php)
  - Memanggil modal inspeksi.
  - Mengambil data header dan detail material.
  - Menyimpan transaksi ke server.
  - Mengatur tombol delete / print / reload.

### Back-end / Controller
- [application/controllers/Transaction.php](../application/controllers/Transaction.php)
  - `getInspectEntry()`
    - Memuat data inspection yang akan ditampilkan di modal.
  - `addTransaction()`
    - Menampung data dari form.
    - Mengecek total qty dan saldo.
    - Menangani logika perhitungan qty per kemasan.
    - Menyimpan transaksi ke database dengan transaksi DB.
  - `scanMatlVendor()`
    - Menangkap data vendor / material untuk proses inspeksi.
  - `inspectionDetail()`
    - Mengambil detail data inspection dan barcode untuk ditampilkan kembali.

### Fungsi JavaScript penting di UI
- `saveTrans2()`
  - Mengumpulkan form data sebelum proses simpan.
- `deleteRow(ele)`
  - Menghapus baris detail yang sedang dipilih.
- `delete_all_matl(line, ele)`
  - Menghapus seluruh data detail pada transaksi tertentu.
- `removeFormatting(numberString)`
  - Membersihkan format angka sebelum diproses.
- `inspect_detail(id)`
  - Memuat data detail inspeksi berdasarkan id transaksi.

---

## 6. Alur kerja baru yang benar

1. User memilih material / PO yang hendak di-inspeksi.
2. Sistem memanggil `inspectionDetail()` untuk menampilkan header dan saldo material.
3. User mengisi form Detail Inspection:
   - kemasan
   - jumlah kemasan
   - qty
   - lot number
   - expired date
4. Klik tombol Go.
5. Sistem memvalidasi field wajib dan menghitung distribusi qty sesuai saldo.
6. Baris detail ditambahkan ke tabel.
7. User menekan tombol simpan / save.
8. Controller `addTransaction()` mengecek duplikasi dan saldo.
9. Data disimpan ke tabel transaksi dan detail inspeksi.
10. Sistem menampilkan status sukses atau error.

---

## 7. Manfaat perubahan

- Lebih akurat dalam pencatatan qty.
- LOT dan expired date lebih konsisten per row.
- Lebih mudah dalam audit data.
- Mengurangi risiko double input atau over qty.
- Proses inspeksi lebih rapi untuk kebutuhan barcode dan print label.

---

## 8. Kesimpulan

Perubahan ini membuat proses inspection entry tidak lagi bersifat input dasar, tetapi menjadi proses yang lebih lengkap dan terkontrol. Perbedaan utama dari flow lama ke flow baru adalah pada detail per-row, validasi saldo, dan penyimpanan data yang lebih siap untuk kebutuhan transaksi dan monitoring.

Dengan begitu, modul inspection entry menjadi lebih stabil untuk digunakan dalam aktivitas penerimaan material dan laporan inspeksi di aplikasi ini.
