<div class="row">
	<div class="col-lg-12">
	    <div class="card mb-4">
	    	<div class="card-header">
				  <h5 class="m-0 font-weight-bold text-gray-800">
            <b>Set Barcode Setting Area</b>
          </h5>
			  </div>
		    <div class="card-body">
					<div class="row">
						<div class="col-sm-1">
					        <div class="form-group">
					            <label class="font-weight-bold">Option</label>
					                <select class="form-control select2bs4" style="width: 100%;" id="scan_set" required="required">
					                    <option value="1">Scanner</option>
					                    <option value="2">Transfer</option>
					                </select>
					        </div>
		       		    </div>

						<div class="col-sm-2">
					        <div class="form-group">
					            <label class="font-weight-bold">Barcode Type</label>
					                <select class="form-control select2bs4" style="width: 100%;" id="vend_stat" required="required">
					                    <option value=""></option>
					                    <option value="0">Non Vendor</option>
					                    <option value="1">Vendor</option>
					                </select>
					        </div>
		       		    </div>

						<div class="col-sm-2">
							<label class="font-weight-bold">Barcode</label>
							<input type="text" name="barcode" id="barcode" class="form-control" value="" placeholder="Barcode">
							<input type="hidden" id="user" name="user" value="<?php echo $_SESSION['user_id']; ?>">
						</div>

						<div class="col-sm-3">
							<label class="font-weight-bold">Material Name</label>
							<input type="text" name="nama" id="nama" class="form-control" disabled value="">
						</div>

						<div class="col-sm-2">
							<label class="font-weight-bold">Barcode Qtty</label>
							<input type="number" name="qtty" id="qtty" class="form-control" disabled value="">
						</div>


						<div class="col-sm-2">
							<label class="font-weight-bold">Transfer Rack</label>
							<input type="text" name="rack" id="rack" class="form-control" value="">
						</div>

						
						<?php //$this->load->view('button');?>
					</div>
		    </div>
	    </div>
	</div>
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-12">
				<div class="card shadow mb-4 border-bottom-primary">

				    <div class="card-body">
				    	<div class="row">
					    	<div class="col-sm-3">
			            <div class="form-group">
			            	<a class="btn btn-outline-success" href="<?php echo base_url(); ?>Dashboard/SettingBarcode">Clear All</i></a>
			            </div>
			          </div>

							<div class="my-3"></div>
							<div class="table-responsive tableFixHead">
								<table class="table table-hover text-nowrap text-gray-800 text-center" id="tableSetting">
						    	<!-- <table class="table table-bordered text-gray-800" id="tableSplit" width="100%" cellspacing="0"> -->
									<thead>
						    			<tr>
									      <th>No</th>
									      <th>Barcode</th>
									      <th>Material Name</th>
									      <th>Last Rack</th>
									      <th>Transfer Rack</th>
									      <th>Qtty</th>
									      <th>Action</th>
						    			</tr>
									</thead>
									<tbody>
									</tbody>
						    	</table>
							</div>
				    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {

    $("#simpan").click(function() {
        loaderSpinner();
        dataSaveSetting();
    });

    // Inisialisasi Status Rack
    setstatus($('#scan_set').val());
    $('#scan_set').change(function() {
        setstatus($(this).val());
        $('#tableSetting').find("tr:gt(0)").remove(); 
    });

    function setstatus(val) {
        if (val == '1') {
            $('#rack').prop('disabled', true).val('SET'); 
            $('#barcode').focus();
        } else if (val == '2') {
           $('#rack').val('').prop('disabled', false).focus();
        }
    }


    document.getElementById("barcode").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            loaderSpinner();
            
            let barcode = $("#barcode").val().toUpperCase();
            if(barcode !== '') {
                findBarcode(barcode);
                // Asumsi detailSplitBarcode() dideklarasikan di tempat lain
                if (typeof detailSplitBarcode === "function") detailSplitBarcode(barcode);
            } else {
                swal.close();
            }
        }
    });
});

function findBarcode(barcode) {
    var vend_stat = $("#vend_stat").val();
    var scan_set  = $("#scan_set").val();
    var rack      = $("#rack").val();

    if (vend_stat === '') {
        alert('Please choose barcode type first!');
        swal.close();
        return;
    }

    if (scan_set === '2' && rack === '') {
        alert('Please choose Location Rack Transfer first!');
        swal.close();
        return;
    }

    $.ajax({
        url : "<?php echo site_url('Setting/HeadSettingBarcode')?>/" + barcode + '/' + vend_stat,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            swal.close();
            
            if(data.stat === 'success') {
                // 1. Cek Duplikasi
                var sudahAda = false;
                $('input[name="serial[]"]').each(function() {
                    if ($(this).val() === barcode) {
                        sudahAda = true;
                        return false; 
                    }
                });

                if (sudahAda) {
                    resetInputBarcode();
                    Swal.fire({
						    icon: 'warning',
						    title: 'Warning!',
						    text: 'Barcode is already on the list!',
						    showConfirmButton: false,
						    timer: 1000
						});

                    return;
                }

                // 2. Validasi Area dan Settings
                if (data.area === "SET" && scan_set === "1") {
                    resetInputBarcode();
                    Swal.fire({
						    icon: 'info',
						    title: 'Duplicate!',
						    text: 'The barcode is already in the Settings Area!',
						    showConfirmButton: false,
						    timer: 1000
						});
                    $('#rack').focus();
                    return;
                }


                 if (data.area === "SET" && scan_set === "2"  && rack === "SET") {
                    resetInputBarcode();
                    Swal.fire({
						    icon: 'info',
						    title: 'Duplicate!',
						    text: 'The barcode is already in the Settings Area!',
						    showConfirmButton: false,
						    timer: 1000
						});
                    $('#rack').focus();
                    return;
                }

                
                if (data.area !== "SET" && scan_set !== "1") {
                	resetInputBarcode();
                    Swal.fire({
						    icon: 'error',
						    title: 'Error!',
						    text: 'Wrong Location Setting!',
						    showConfirmButton: false,
						    timer: 1000
						});
                    return;
                }

                // 3. Proses Penambahan Baris
                resetInputBarcode();
                $('[name="nama"]').val(data.nama);
                $('[name="qtty"]').val(data.qtyakhir);

                if (data.qtyakhir > 0) { 
                    var qtty = data.qtyakhir; 
                    var rowCount = $('#tableSetting tr').length;
                    
                    // Gunakan Template Literals (`) dan readonly agar data bisa disave
                    var baris_baru = `
                        <tr>
                            <td>${rowCount}</td>
                            <td><input type="text" class="form-control" name="serial[]" value="${barcode}" readonly></td>
                            <td><input type="text" class="form-control" name="name_set[]" value="${data.nama}" readonly></td> 
                            <td><input type="text" class="form-control" name="area[]" value="${data.area}" readonly></td>
                            <td><input type="text" class="form-control" name="rack[]" value="${rack}" readonly></td>
                            <td><input type="number" class="form-control" name="qty_set[]" value="${qtty}" readonly></td>
                            <td><button type="button" class="btn btn-danger btn-sm text-center" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    `;
                    $("#tableSetting").append(baris_baru);
                } else {

                    Swal.fire({
						    icon: 'warning',
						    title: 'Warning!',
						    text: 'Qtty is 0!',
						    showConfirmButton: false,
						    timer: 1000
						});

                }

            } else {
                 Swal.fire({
						    icon: 'error',
						    title: 'Error!',
						    text: 'No data Found!',
						    showConfirmButton: false,
						    timer: 1000
						});

            } 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            swal.close();
            alert('Error get data from ajax');
        }
    });
}

// Fungsi Bantuan untuk mereset field input header
function resetInputBarcode() {
    $('[name="barcode"]').val('');
    $('[name="nama"]').val('');
    $('[name="qtty"]').val('');
}

function deleteRow(ele) {
    var table = $('#tableSetting');
    
    if (table.find('tr').length <= 1) { // 1 karena ada header TR
        if(typeof Toast !== "undefined") {
            Toast.fire({ icon: 'error', title: 'There is no row available to delete!' });
        }
        return;
    }

    if (ele) {
        $(ele).closest('tr').remove();
    } else {
        table.find('tr:last').remove();
    }

    // Re-index penomoran
    $('#tableSetting tr').each(function(index) {
        if (index > 0) {
            $(this).find('td:first').text(index);
        }
    });
}

function dataSaveSetting() {
    var dataDetail = [];

    $('#tableSetting tr').each(function(index) {
        if (index > 0) { // Lewati header
            dataDetail.push({
                serial : $(this).find('input[name="serial[]"]').val(),
                nama   : $(this).find('input[name="name_set[]"]').val(),
                qty    : $(this).find('input[name="qty_set[]"]').val(),
                rack   : $(this).find('input[name="rack[]"]').val(),
                user   : $("#user").val()
            });
        }
    });

    if (dataDetail.length === 0) {
        if(typeof Toast !== "undefined") {
            Toast.fire({ icon: 'error', title: 'There is no row available to save!' });
        }
        return;
    }

    $.ajax({
        url: "<?php echo site_url('Setting/simpanDataSetting')?>",
        type: "POST",
        data: { details: dataDetail },
        dataType: "JSON",
        success: function(response) {
            swal.close();
            if (response.stat === 'success') {
                Swal.fire('Berhasil!', 'Data tersimpan', 'success');
   
                $('#tableSetting').find("tr:gt(0)").remove(); 
            } else {
                Swal.fire('Gagal!', response.message, 'error');
            }
        },
        error: function() {
            swal.close();
            Swal.fire('Error!', 'Failed to connect to server', 'error');
        }
    });
}
</script>

 