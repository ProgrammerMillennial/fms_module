<!-- Pastikan Library SweetAlert2 ada -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fa fa-exchange-alt"></i> Scan & Transfer Rack</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Transaction</a></li>
                    <li class="breadcrumb-item active">Scan & Transfer Rack</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            
            <!-- FORM SCAN BARCODE & LOKASI (Bagian Kiri) -->
            <div class="col-md-4">
                <div class="card card-primary shadow-sm">
                    
                    <!-- HEADER CARD DENGAN DROPDOWN MODE & JENIS -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h3 class="card-title m-0 mr-2" id="judul_form">Input Transfer</h3>
                            <!-- Dropdown Mode (Transfer / Scan) diletakkan di sebelah judul -->
                            <select id="mode_aksi" class="form-control form-control-sm font-weight-bold text-success w-auto" style="cursor: pointer;">
                                <option value="scan">Scan</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        
                        <!-- Dropdown Jenis (Mezzanine / Rak) diletakkan di sisi kanan -->
                        <select id="jenis_transfer" class="form-control form-control-sm font-weight-bold text-primary w-auto" style="cursor: pointer;">
                            <option value="Mezzanine">Mezzanine</option>
                            <option value="Rak">Rack</option>
                        </select>
                    </div>

                    <div class="card-body">
                        <!-- Alert Placeholder -->
                        <div id="alert-message" style="display:none;" class="alert alert-dismissible fade show" role="alert">
                            <span id="alert-text"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <!-- 1. Input Lokasi Tujuan (Otomatis menyesuaikan mode via JS) -->
                        <div class="form-group" id="group_lokasi">
                            <label for="lokasi_mezzanine" id="label_lokasi">1. Lokasi Mezzanine Tujuan</label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" id="lokasi_mezzanine" placeholder="Scan/Ketik Lokasi..." autocomplete="off">
                                <div class="input-group-append" id="btn-lock-container">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-lock-lokasi"><i class="fa fa-lock"></i></button>
                                </div>
                            </div>
                            <small class="text-muted" id="hint_lokasi">Tentukan lokasi.</small>
                        </div>

                        <hr>

                        <!-- 2. Input Barcode -->
                        <div class="form-group mt-3" id="group_barcode">
                            <label for="barcode_id" id="label_barcode">2. Scan Barcode Material</label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                                </div>
                                <input type="text" class="form-control" id="barcode_id" placeholder="Scan Barcode disini..." autocomplete="off">
                            </div>
                            <small class="text-muted">Tekan Enter untuk memasukkan ke daftar.</small>
                        </div>

                        <!-- Tombol Eksekusi -->
                        <button type="button" class="btn btn-success btn-lg btn-block mt-4" id="btn-transfer">
                            <i class="fa fa-paper-plane"></i> Proses Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <!-- DAFTAR TAMPUNGAN BARCODE (Bagian Kanan) -->
            <div class="col-md-8">
                <div class="card card-warning shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0"><i class="fa fa-list"></i> Daftar Scan (Belum Diproses)</h3>
                        <span class="badge badge-danger" id="badge-count" style="font-size: 14px;">Total: 0 Item</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-striped table-hover" id="table-queue">
                               <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                    <tr>
                                        <th style="width: 30px;" class="text-center">No</th>
                                        <th>Barcode ID</th>
                                        <th>Loc Code</th>
                                        <th>Loc Name</th>
                                        <th>Type</th>
                                        <th>Divi</th>
                                        <th>Row</th>
                                        <th>User</th>
                                        <th class="text-center">Status</th>
                                        <th style="width: 50px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="queue-body">
                                    <!-- Data render -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger" id="btn-clear-all">
                            <i class="fa fa-trash"></i> Kosongkan Daftar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        const STORAGE_KEY = 'mezzanine_barcode_queue';
        let barcodeQueue = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        renderTable();
        
        applyModeLogic(); // Inisialisasi UI awal

        // -------------------------------------------------------------
        // LOGIKA PERUBAHAN DROPDOWN MODE & JENIS
        // -------------------------------------------------------------
        $('#mode_aksi').change(function() {
            applyModeLogic();
            barcodeQueue = []; // Reset queue jika ganti mode untuk mencegah konflik data
            saveToLocalStorage();
            renderTable();
        });

        $('#jenis_transfer').change(function() {
            applyModeLogic(); // Panggil ulang agar label terupdate
            if ($('#mode_aksi').val() === 'scan') {
                $('#lokasi_mezzanine').focus();
            } else {
                $('#barcode_id').focus(); 
            }
        });

        // Kunci input manual pada mode scan dengan tombol Enter/Lock
        $('#lokasi_mezzanine').on('keypress', function(e) {
            if (e.which === 13 && $('#mode_aksi').val() === 'scan') {
                e.preventDefault();
                $('#btn-lock-lokasi').click();
            }
        });

        $('#btn-lock-lokasi').click(function() {
            let locInput = $('#lokasi_mezzanine');
            let loc = locInput.val().trim();
            let btn = $(this);

            // 1. LOGIKA UNLOCK: Jika input sedang terkunci, buka kembali kuncinya
            if (locInput.prop('readonly')) {
                locInput.prop('readonly', false).focus();
                $('#barcode_id').prop('readonly', true); // Kunci kembali input barcode
                btn.removeClass('btn-success').addClass('btn-outline-secondary').html('<i class="fa fa-lock"></i>');
                $('#hint_lokasi').text('Wajib isi dan Enter/Lock lokasi sebelum scan barcode.');
                return; // Hentikan proses sampai di sini (tidak lanjut AJAX)
            }

            // 2. LOGIKA LOCK & VALIDASI: Jika input sedang terbuka dan ada isinya
            if (loc !== '') {
                btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

                $.ajax({
                    url: "<?php echo site_url('Transaction/cek_lokasi_mez') ?>",
                    type: "POST",
                    dataType: "json",
                    data: { lokasi: loc },
                    success: function(res) {
                        if(res.status === 'success') {
                            // Kunci lokasi, buka barcode
                            locInput.prop('readonly', true);
                            $('#barcode_id').prop('readonly', false).focus();
                            
                            // Ubah tombol jadi warna hijau dengan icon Edit / Unlock
                            btn.removeClass('btn-outline-secondary').addClass('btn-success').html('<i class="fa fa-edit"></i>');
                            $('#hint_lokasi').text('Lokasi Terkunci (' + res.data.LOCATION_NAME + '). Klik tombol hijau untuk ganti lokasi.');
                            
                            showAlert('Lokasi valid. Silakan scan barcode.', 'success');
                        } else {
                            showAlert(res.message, 'danger');
                            locInput.val('').focus();
                            btn.html('<i class="fa fa-lock"></i>');
                        }
                    },
                    error: function() {
                        showAlert('Terjadi kesalahan jaringan saat mengecek lokasi.', 'danger');
                        btn.html('<i class="fa fa-lock"></i>');
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                    }
                });
            }
        });


        // FUNGSI MENGATUR UI BERDASARKAN MODE (SCAN/TRANSFER)
        function applyModeLogic() {
            let mode = $('#mode_aksi').val();
            let jenis = $('#jenis_transfer').val();
            
            if (mode === 'scan') {
                // Judul Form
                $('#judul_form').text('Input Scan');
                
                // Susunan Form (Lokasi harus di-scan duluan)
                $('#group_lokasi').insertBefore('#group_barcode'); // Pastikan lokasi di atas
                $('#label_lokasi').text('1. Lokasi ' + jenis + ' (Scan Rack Dulu)');
                $('#label_barcode').text('2. Scan Barcode Material');
                
                $('#lokasi_mezzanine').prop('readonly', false).val('').attr('placeholder', 'Scan/Ketik Lokasi ' + jenis).focus();
                $('#barcode_id').prop('readonly', true).val(''); // Kunci sampai lokasi diisi
                
                $('#btn-lock-container').show();
                $('#btn-lock-lokasi').removeClass('btn-success').addClass('btn-outline-secondary').html('<i class="fa fa-lock"></i>');
                $('#hint_lokasi').text('Wajib isi dan Enter/Lock lokasi sebelum scan barcode.');
                
                $('#btn-transfer').html('<i class="fa fa-save"></i> Proses Simpan & Transfer').removeClass('btn-success').addClass('btn-primary');
            } else {
                // Judul Form
                $('#judul_form').text('Input Transfer');
                
                // Susunan Form (Barcode di-scan duluan)
                $('#group_barcode').insertBefore('#group_lokasi'); // Pindahkan barcode ke atas
                $('#label_barcode').text('1. Scan Barcode Material');
                $('#label_lokasi').text('2. Lokasi ' + jenis + ' Tujuan');
                
                $('#lokasi_mezzanine').prop('readonly', false).val('').attr('placeholder', 'Scan/Ketik Lokasi ' + jenis);
                $('#barcode_id').prop('readonly', false).val('').focus();
                
                $('#btn-lock-container').hide();
                $('#hint_lokasi').text('Lokasi tujuan dapat diisi setelah list barcode selesai.');
                
                $('#btn-transfer').html('<i class="fa fa-paper-plane"></i> Proses Transfer Sekarang').removeClass('btn-primary').addClass('btn-success');
            }
        }

        // -------------------------------------------------------------
        // EVENT SCAN BARCODE (AJAX CEK QTY)
        // -------------------------------------------------------------
        $('#barcode_id').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                let mode = $('#mode_aksi').val();
                
                if (mode === 'scan' && $('#lokasi_mezzanine').val().trim() === '') {
                    showAlert('Pada Mode Scan, Lokasi Rack/Tujuan HARUS diisi terlebih dahulu!', 'danger');
                    $('#lokasi_mezzanine').focus();
                    return;
                }

                let newBarcode = $(this).val().trim();

                if (newBarcode !== '') {
                    let isExist = barcodeQueue.some(item => item.barcode === newBarcode);
                    if(isExist) {
                        showAlert('Barcode sudah ada di daftar scan!', 'warning');
                        $(this).val(''); return;
                    }

                    $(this).prop('disabled', true);
                    let origPlaceholder = $(this).attr('placeholder');
                    $(this).attr('placeholder', 'Mengecek database...');

                    $.ajax({
                        url: "<?php echo site_url('Transaction/cek_barcode_mez') ?>",
                        type: "POST",
                        dataType: "json",
                        data: { barcode_id: newBarcode },
                        success: function(res) {
                            if (res.status === 'success') {
                                let newData = {
                                    barcode: newBarcode,
                                    loc_code: res.data.loc_code || '-',
                                    loc_name: res.data.loc_name || '-',
                                    loc_type: res.data.loc_type || '-',
                                    divi: res.data.divi || '-',
                                    loc_row: res.data.loc_row || '-',
                                    user_id: res.data.user_id || '-',
                                    status: 'Menunggu Eksekusi'
                                };
                                barcodeQueue.unshift(newData); 
                                saveToLocalStorage();
                                renderTable();
                            } else {
                                showAlert(res.message, 'danger');
                            }
                        },
                        error: function() {
                            showAlert('Terjadi kesalahan jaringan.', 'danger');
                        },
                        complete: function() {
                            $('#barcode_id').prop('disabled', false)
                                          .val('')
                                          .attr('placeholder', origPlaceholder)
                                          .focus(); 
                        }
                    });
                }
            }
        });

        // -------------------------------------------------------------
        // HAPUS PER BARIS & KOSONGKAN
        // -------------------------------------------------------------
        $('#queue-body').on('click', '.btn-delete-row', function() {
            let index = $(this).data('index');
            barcodeQueue.splice(index, 1);
            saveToLocalStorage(); renderTable();
        });

        $('#btn-clear-all').click(function() {
            if(barcodeQueue.length > 0 && confirm('Kosongkan semua daftar scan?')) {
                barcodeQueue = [];
                saveToLocalStorage(); renderTable();
            }
        });

        // -------------------------------------------------------------
        // EKSEKUSI PROSES (TRANSFER / SCAN)
        // -------------------------------------------------------------
        $('#btn-transfer').click(function() {
            let lokasiVal = $('#lokasi_mezzanine').val().trim();
            let jenisTransfer = $('#jenis_transfer').val(); 
            let modeAksi = $('#mode_aksi').val();

            if(barcodeQueue.length === 0) {
                showAlert('Daftar scan kosong!', 'danger'); return;
            }
            if(lokasiVal === '') {
                showAlert(`Lokasi ${jenisTransfer} tidak boleh kosong!`, 'danger'); 
                $('#lokasi_mezzanine').focus();
                return;
            }

            let txtKonfirmasi = modeAksi === 'scan' ? 
                `Anda akan MENGINSERT/UPDATE ${barcodeQueue.length} Item ke ${jenisTransfer}: [${lokasiVal}]. Yakin?` : 
                `Anda akan MENTRANSFER ${barcodeQueue.length} Item ke ${jenisTransfer}: [${lokasiVal}]. Yakin?`;

            Swal.fire({
                title: 'Konfirmasi Proses',
                text: txtKonfirmasi,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let btn = $(this);
                    let btnOriginal = btn.html();
                    btn.html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
                    btn.prop('disabled', true);

                    let barcodeArray = barcodeQueue.map(item => item.barcode);

                    $.ajax({
                        url: "<?php echo site_url('Transaction/prosesTransferMez') ?>",
                        type: "POST",
                        dataType: "json",
                        data: {
                            barcodes: barcodeArray,
                            lokasi_mezzanine: lokasiVal,
                            jenis_transfer: jenisTransfer,
                            mode_aksi: modeAksi 
                        },
                        success: function(res) {
                            if (res.status === 'success') {
                                barcodeQueue = [];
                                saveToLocalStorage();
                                renderTable();
                                
                                if (modeAksi === 'scan') {
                                    $('#lokasi_mezzanine').val('').prop('readonly', false);
                                    $('#btn-lock-lokasi').removeClass('btn-success').addClass('btn-outline-secondary').html('<i class="fa fa-lock"></i>');
                                    $('#barcode_id').prop('readonly', true);
                                    $('#lokasi_mezzanine').focus();
                                } else {
                                    $('#lokasi_mezzanine').val('');
                                }

                                let msg = `Berhasil memproses ${res.jml_sukses} Item.`;
                                if (res.jml_gagal > 0) {
                                    msg += `<br><span class="text-danger">Gagal: ${res.jml_gagal} Item. (${res.detail_gagal})</span>`;
                                    Swal.fire('Informasi Proses', msg, 'warning');
                                } else {
                                    Swal.fire({ title: 'Sukses!', text: msg, icon: 'success', timer: 3000, showConfirmButton: false });
                                }
                            } else {
                                Swal.fire('Gagal!', res.message || 'Terjadi kesalahan pada server.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error Server!', 'Gagal menghubungi server.', 'error');
                        },
                        complete: function() {
                            btn.html(btnOriginal);
                            btn.prop('disabled', false);
                        }
                    });
                }
            });
        });

        // -------------------------------------------------------------
        // FUNGSI RENDER TABEL & ALERT
        // -------------------------------------------------------------
        function renderTable() {
            $('#queue-body').empty();
            $('#badge-count').text(`Total: ${barcodeQueue.length} Item`);

            if (barcodeQueue.length === 0) {
                $('#queue-body').html(`<tr><td colspan="10" class="text-center text-muted">Belum ada barcode di-scan.</td></tr>`);
                return;
            }

            $.each(barcodeQueue, function(index, item) {
                let row = `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td><strong>${item.barcode}</strong></td>
                        <td>${item.loc_code}</td>
                        <td>${item.loc_name}</td>
                        <td>${item.loc_type}</td>
                        <td>${item.divi}</td>
                        <td>${item.loc_row}</td>
                        <td>${item.user_id}</td>
                        <td class="text-center">
                            <span class="badge badge-info"><i class="fa fa-clock"></i> ${item.status}</span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm btn-delete-row" data-index="${index}">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#queue-body').append(row);
            });
        }

        function saveToLocalStorage() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(barcodeQueue));
        }

        function showAlert(message, type) {
            let alertBox = $('#alert-message');
            alertBox.removeClass('alert-success alert-danger alert-warning alert-info').addClass('alert-' + type);
            $('#alert-text').html(message);
            alertBox.slideDown();
            setTimeout(() => { alertBox.slideUp(); }, 5000);
        }
    });
</script>