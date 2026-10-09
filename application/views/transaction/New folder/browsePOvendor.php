<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<div class="card shadow mb-4 border-left-info">
						<div class="card-header">
				              <h5 class="m-0 font-weight-bold text-gray-800">
                                  <b>Inspection Vendor</b>
                         </h5>
			</div>
        <div class="card-body">
        	<div class="row">
        		<div class="col-sm-12">
			        <div class="form-group">
			            <label class="font-weight-bold">Warehouse</label>
			                <select class="form-control select2bs4" style="width: 100%;" id="wh">
			                    <option value="">-</option>
			                    <option value="MAT">Material Warehouse</option>
			                    <option value="ENG">Engineering Warehouse</option>
			                    <option value="IDC">IDC Warehouse</option>
			                </select>
			        </div>
		        </div>
		        <div class="col-sm-4">
		        	<div class="form-group">
								<label class="font-weight-bold">PO No</label>
								<input type="text" name="barcode" class="form-control" value="" id="pono2">	
							</div>
						</div>
						<div class="col-sm-4">	
						  <div class="form-group">
	              <label class="font-weight-bold">Date From</label>
	                <div class="input-group date" id="tgl1" data-target-input="nearest">
	                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_ins1" id="tgl_ins1" />
	                      <div class="input-group-append" data-target="#tgl1" data-toggle="datetimepicker">
	                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
	                      </div>
	                </div>
	            </div>
          	</div>
          	<div class="col-sm-4">	
						  <div class="form-group">
	              <label class="font-weight-bold">Date To</label>
	                <div class="input-group date" id="tgl2" data-target-input="nearest">
	                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl2" name="tgl_ins2" id="tgl_ins2" />
	                      <div class="input-group-append" data-target="#tgl2" data-toggle="datetimepicker">
	                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
	                      </div>
	                </div>
	            </div>
          	</div>
					</div>
					 <div class="row">
					 	<div class="col-sm-8">
			        <div class="form-group">
			            <label class="font-weight-bold">Vendor</label>
			                <select class="form-control select2bs4" multiple="multiple" style="width: 100%;" id="vendor">
			                <!-- <select class="form-control select2bs4" style="width: 100%;" id="vendor"> -->
			                    <!-- <option selected="selected"></option> -->
			                </select>
			        </div>
			      </div>

						  <div class="col-sm-4">
								<div class="form-group">
									<label class="font-weight-bold">Serial Code</label>
									<input type="text" name="barcode" class="form-control" value="" id="serial2">	
								</div>
						  </div>
					 </div>
        </div>
        <?php $this->load->view('button');?>
    </div>
	</div>
	<div class="col-lg-2"></div>
</div>


<div class="row">
	<div class="col-lg-12">
		<div class="card shadow mb-2 border-bottom-primary">
		    <div class="card-body">

		    	<a type="button" onclick="addMatVendor()" class="btn btn-primary btn-icon-split">
              <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
              </span>
              <span class="text">Scan Material</span>
          </a>

			<div class="my-3"></div>
				<div class="table-responsive">
			    	<table class="table table-bordered text-gray-800 text-center" id="tableHeader" width="100%" cellspacing="0">
						<thead>
			    			<tr align="center">
						      <th>New Serial</th>
						      <th>Serial</th>
						      <th>Material Code</th>
						      <th>Material Name</th>
						      <th>Unit</th>
						      <th>Qtty</th>
						      <th>PO No</th>
						      <th>Vendor</th>
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

<?php $this->load->view('transaction/modalInspectVendor');?>

<script type="text/javascript">
	$(document).ready(function() {
	    $("#cari").click(function(){
		  var pono = $('#pono2').val();
			var dt1 = $('#tgl_ins1').val();
			var dt2 = $('#tgl_ins2').val();
			var e2 = document.getElementById("vendor");
			var vend = e2.value;
			var serial = $('#serial2').val();
			var e = document.getElementById("wh");
			var wh = e.value;
			if(wh == ""){
					Swal.fire({
					  icon: "info",
					  title: "Oops...",
					  text: "Please choose warehouse first!"
					});
					return;
			}
	    $('#tableHeader').dataTable().fnClearTable();
      var table = $('#tableHeader').DataTable({	
			"ajax": {
	        			'type': 'POST',
	        			'url': '<?php echo site_url('transaction/loadDataPoVend')?>',
	        			'data': {
	           						pono: pono,
	           						dt1: dt1,
	           						dt2: dt2,
	           						vend: vend,
	           						serial: serial,
	           						wh: wh
	        					}
	        },
		      bDestroy: true,
		      processing: true,
		      responsive: true,
			    select: {
				        	style: 'single'
				        }
			});

			$('#tableHeader').DataTable().on('select', function (e, dt, type, indexes){
				$("#print").click(function(){
			    	var line ="";
			    	line = table.row(indexes).data()[0];
			    	printBybarcode(line);
			  });
			})
   });
	 loadVendor();
	});
</script>

<script type="text/javascript">
    function addMatVendor()
    {
    	var e = document.getElementById("wh");
			var wh = e.value;
    	// console.log(tgl);
      save_method = 'add';
      // $('#form')[0].reset(); // reset form on modals
      $('[name="wh2"]').val(wh);
      $("#detail_material").find("tr:gt(0)").remove();
      // $('[name="tgl_inspect"]').val(tgl);
      $('#modal_form').modal({backdrop: 'static', keyboard: false})          
      $('#modal_form').modal('show'); // show bootstrap modal
      $('.modal-title').text('Scan new material'); // Set Title to Bootstrap modal title
    }

	  var input = document.getElementById("barcode");
	  input.addEventListener("keypress", function(event) {
	    if (event.key === "Enter") {
	      event.preventDefault();

	      var stat = 1;
	      // var e2 = document.getElementById("wh2");
				// var wh2 = e2.value;
				var wh2 = $("#wh2").val();
				if(wh2 == ""){
					Swal.fire({
					  icon: "info",
					  title: "Oops...",
					  text: "Please choose warehouse first!"
					});
					return;
				}
				const fData = [];
				var formData = {
          tranno: $("#tranno2").val(),
          tgl_inspect: $("#tgl_inspect").val(),
          wh: wh2,
          vstat: stat,
          barcode: $("#barcode").val(),
          user: $("#user").val()
        };
				fData.push(formData);
				const dataPost = {params: fData};
				// console.log(dataPost);
				loaderSpinner();
				$.ajax({
        url : "<?php echo site_url('transaction/scanMatlVendor')?>",
        type: "POST",
        data: dataPost,
        success: function(data)
        { 
              // console.log(data);
        			swal.close();
              const obj = JSON.parse(data);
              var line;
              // console.log(obj.status);
              document.getElementById('barcode').value = '';
              document.getElementById("barcode").focus();
              if (obj.status == 'success'){
	              $('#tranno').html("<label for='exampleInputEmail1'><b>Trans. No</b></label><input type='text' class='form-control' id='tranno2' name='tranno2' value ='"+obj.id+"'  disabled>");
	              document.getElementById('total_inspect').value = obj.total;
	              for (let i = 0; i < obj.detail.length; i++) {
	                for (var key in obj.detail[i]) {
	                    if (obj.detail[i].hasOwnProperty(key)) {
	                    	if(obj.detail[i]['INSD_LINE'] != line){
										      var baris_baru = '<tr><td>'+obj.detail[i]['INSD_LINE']+'</td><td>'+obj.detail[i]['INSD_CODE']+'</td><td>'+obj.detail[i]['INSD_NAME']+'</td><td>'+obj.detail[i]['INSD_IQTY']+'</td><td>'+obj.detail[i]['INSD_PONO']+'</td><td>'+obj.detail[i]['INSD_VEND']+'</td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

										        $("#detail_material").append(baris_baru);
										     }
										     line = obj.detail[i]['INSD_LINE'];
	                    }
	                }
	              }
              }

				      Toast.fire({
				        icon: obj.status,
				        title: obj.message
				      })
        },
        error: function (jqXHR, textStatus, errorThrown)
             {
	                Swal.fire(
	                  'Error!',
	                  'Error while saving the data, re-chek again !',
	                  'error'
	                )
            }
        });
	    }
	  });
</script>