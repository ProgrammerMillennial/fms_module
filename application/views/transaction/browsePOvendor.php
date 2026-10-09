<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<div class="card shadow mb-4 border-left-info">
						<div class="card-header">
				              <h5 class="m-0 font-weight-bold text-gray-800">
                                  <b>Browse PO Vendor</b>
                         </h5>
			</div>
        <div class="card-body">
        	<div class="row">
        		<div class="col-sm-12">
			        <div class="form-group">
			            <label class="font-weight-bold">Warehouse</label>
			                <select class="form-control select2bs4" style="width: 100%;" id="wh">
<!-- 			                    <option value="">-</option>
			                    <option value="MAT">Material Warehouse</option>
			                    <option value="ENG">Engineering Warehouse</option>
			                    <option value="IDC">IDC Warehouse</option> -->
			                </select>
			        </div>
		        </div>
		        <div class="col-sm-4">
		        	<div class="form-group">
								<label class="font-weight-bold">PO No</label>
								<input type="text" id="pono" class="form-control" value="">	
							</div>
						</div>
			<div class="col-sm-4">	
				<div class="form-group">
	              <label class="font-weight-bold">Date From</label>
	                <div class="input-group date" id="tgl1" data-target-input="nearest">
	                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_belanja" id="dt1" />
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
	                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl2" name="tgl_belanja" id="dt2"/>
	                      <div class="input-group-append" data-target="#tgl2" data-toggle="datetimepicker">
	                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
	                      </div>
	                </div>
	            </div>
          	</div>
					</div>
					 <div class="row">
					 	<div class="col-sm-6">
		        	<div class="form-group">
								<label class="font-weight-bold">Barcode</label>
								<input type="text" id="line" class="form-control" value="">	
							</div>
						</div>
					 	<div class="col-sm-6">
			        <div class="form-group">
			            <label class="font-weight-bold">Vendor</label>
			            		<select class="form-control select2bs4" multiple="multiple" style="width: 100%;" id="vendor">
			                <!-- <select class="form-control select2bs4" style="width: 100%;" id="vendor"> -->
			                    <!-- <option selected="selected"></option> -->
			                </select>
			        </div>
			       </div>
					 </div>
        </div>
        <?php //$this->load->view('button');?>
    </div>
	</div>
	<div class="col-lg-2"></div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card shadow mb-4 border-bottom-primary">
		    <div class="card-body">

		    	<a type="button" onclick="addMatVendor()" class="btn btn-primary btn-icon-split">
              <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
              </span>
              <span class="text">Scan Material</span>
          </a>

			<div class="my-3"></div>
				<div class="table-responsive">
			    	<table class="table table-bordered table-hover text-gray-800 text-center" id="tableHeader" width="100%" cellspacing="0">
						<thead>
			    			<tr align="center">
<!-- 						      <th>No</th>
						      <th>PO No</th> -->
						      <!-- <th>MI No</th> -->
<!-- 						      <th>Release</th>
						      <th>Style</th>
						      <th>Vendor</th>
						      <th>MI Date</th>
						      <th>PO Qtty</th>
						      <th>MI Qtty</th>
						      <th>Ttl Inspect</th>
						      <th>Bal Inspect</th> -->
									<th width="3%">No</th>
									<th>Line</th>
									<th>Material Name</th>
									<th width="4%">Unit</th>
									<th>Qtty</th>
									<th>Date</th>
									<th>PO No.</th>
									<th>SJ No.</th>
									<th>Vend ID</th>
									<th>Vend Name</th>
									<th>Transaction ID</th>
									<th>WH</th>
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

<?php $this->load->view('transaction/modalInspectVendor');?>

<script type="text/javascript">
	$(document).ready(function() {
	    $("#cari").click(function() {
		  var pono = $('#pono').val();
			var dt1 = $('#dt1').val();
			var dt2 = $('#dt2').val();
			var e2 = document.getElementById("vendor");
			var e3 = e2.options;
			var vend = e2.value;
			var fact = "";
			var line = $('#line').val();
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

			$("#tableHeader").DataTable().off('select');
      $("#tableHeader").DataTable().clear().destroy();

	    	$('#tableHeader').dataTable().fnClearTable();
      		var table = $('#tableHeader').DataTable({	
			// let table = new DataTable('#tableHeader', {
			"ajax": {
	        			'type': 'POST',
	        			'url': '<?php echo site_url('Transaction/loadInspectBarcVendor')?>',
	        			'data': {
	           						pono: pono,
	           						dt1: dt1,
	           						dt2: dt2,
	           						vend: vend,
	           						fact: fact,
	           						wh:wh,
	           						line:line
	        					}
	        },
		      bDestroy: true,
		      processing: false,
		      responsive: true,
			    select: {
				        	style: 'single'
				        }
			});

			// $('#tableHeader').DataTable().on('select', function (e, dt, type, indexes) {
			//     	var pono1 ="";

			//     	pono1 = table.row(indexes).data()[1];
			// 			addMatVendor(pono1);
			//     });

	    });
	    loadVendor();
	    loadWarehouse();
	    scanBarcode();
   });

    function addMatVendor(pono1)
    {
    	// console.log(tgl);
      save_method = 'add';
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
      $('[name="warehouse"]').val(wh);
      // $('[name="pono"]').val(pono1);
      // $('[name="mino"]').val(mino);
      // $('[name="midate"]').val(tgl_mi);
      // $('[name="miqty"]').val(ttl_mi);
      // $('#form')[0].reset(); // reset form on modals
      // $("#detail_material").find("tr:gt(0)").remove();
      $("#detail_material2").find("tr:gt(0)").remove();
      // $('[name="tgl_inspect"]').val(tgl);
      $('#modal_form').modal({backdrop: 'static', keyboard: false})          
      $('#modal_form').modal('show'); // show bootstrap modal
      $('.modal-title').text('Scan new material'); // Set Title to Bootstrap modal title
      // load_table(wh, pono1);
    }

		function load_table(wh){
    $('#tableDetail').dataTable().fnClearTable();
      var table1 =$('#tableDetail').DataTable({
        "ajax": {
            "type" : "GET",
            "url" : '<?php echo site_url('Transaction/getDetailInspectVendor')?>/' + wh,
            "dataSrc": function ( json ) {
            		console.log(json);
                return json.data;
            }       
        },
            // ajax: '<?php echo site_url('transaction/getDataMiHeader')?>/' + po,
        // columnDefs: [
        //     {
				// 			"targets": [0,1,2,3,4,5],
				// 			    "createdCell": function (td, cellData, rowData, row, col) {
				// 			      if ( rowData[4] == 'OK' ) {
				// 			        $(td).css('color', 'green')
				// 			      }
				// 			    }
        //     }          
        // ],
        responsive: true,
          bDestroy: true,
          processing: true,
          select: {
                  style: 'single'
                }
      });
  }

  function reload_table()
    {
      $("#tableHeader").DataTable().off('select');
      $("#tableHeader").DataTable().clear().destroy();
    
      load_table2()
    }

  function loadTable2(){
			var pono = $('#pono').val();
			var dt1 = $('#dt1').val();
			var dt2 = $('#dt2').val();
			var e2 = document.getElementById("vendor");
			var e3 = e2.options;
			var vend = e2.value;
			var fact = "";
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

			$("#tableHeader").DataTable().off('select');
      		$("#tableHeader").DataTable().clear().destroy();

	    	$('#tableHeader').dataTable().fnClearTable();
      		var table = $('#tableHeader').DataTable({	
			// let table = new DataTable('#tableHeader', {
			"ajax": {
	        			'type': 'POST',
	        			'url': '<?php echo site_url('Transaction/listPoDetailVendor')?>',
	        			'data': {
	           						pono: pono,
	           						dt1: dt1,
	           						dt2: dt2,
	           						vend: vend,
	           						fact: fact,
	           						wh:wh
	        					}
	        },
		      bDestroy: true,
		      processing: true,
		      responsive: true,
			    select: {
				        	style: 'single'
				        }
			});

			// $('#tableHeader').DataTable().on('select', function (e, dt, type, indexes) {
			//     	var pono1 ="";

			//     	pono1 = table.row(indexes).data()[1];
			// 			addMatVendor(pono1);
			// });
  }

	function printBarcVendor(id){
      let tipe = id;
      var tipe2;
      var vd_st = 1;
      if(tipe.substring(0, 3) == 'INH'){
        tipe2 = 'head';
      }else{
        tipe2 = 'line';
      }
      var url = "<?php echo site_url('Transaction/printNewBarcode')?>/"+tipe2+"/"+id+"/"+vd_st;
      window.open(url);
    }

  function delete_matl(line, ele, idxx, wh, tgl){
    const fData = [];

    var formData = {
          id: idxx,
          barcode: line,
          wh: wh,
          tgl_inspect: tgl,
          vend: 1
    };

    fData.push(formData);
    const dataPost = {params: fData};
    ln = ele;
    method = 'N';
    delTrans(dataPost, ele);
  }

	function delTrans(d, ele){
    // var id = $("#tranno2").val();

    const dataPost = d;
    if(method=='N'){
      var url = '<?php echo site_url('Transaction/deleteInByBarcode')?>';
    }else{
      var url = '<?php echo site_url('Transaction/deleteInAll')?>';
    }
    swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
          // closeOnConfirm: false
        }).then(function (result) {
           if (result.value) {
             $.ajax({
                url : url,
                type: "POST",
                data: dataPost,
                success: function(data){
                  // console.log(data);
                  const obj = JSON.parse(data);
                  if(obj.status == 'success'){
                      Swal.fire(
                                  obj.title,
                                  obj.message,
                                  obj.status
                              );
                      // if(obj.delAll == '1'){
                      //     $('#tranno2').html("<label for='exampleInputEmail1'><b>Trans. No</b></label><input type='text' class='form-control' id='tranno2' name='tranno2'>");
                      //     document.getElementById('tranno2').value = '';
                      //     document.getElementById("tranno2").disabled = false;
                      //     document.getElementById('sjno').value = '';
                      // }

                      reload_table();
                  }else{
                      Swal.fire(
                          obj.title,
                          obj.message,
                          obj.status
                      )
                  }
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

          } else{
            return;
          }
        })
  }
</script>

<script type="text/javascript">
		// let inputStart, inputStop;

		// $("#barcode")[0].onpaste = e => e.preventDefault();
		// // handle a key value being entered by either keyboard or scanner
		// var lastInput

		// let checkValidity = () => {
		//   if ($("#barcode").val().length < 10) {
		//       $("#barcode").val('')
		//   } else {
		//     scanBarcode();
		//   }
		//   timeout = false
		// }

		// let timeout = false
		// $("#barcode").keypress(function (e) {
		//   if (performance.now() - lastInput > 1000) {
		//     $("#barcode").val('')
		//   }
		//   lastInput = performance.now();
		//   if (!timeout) {
		//     timeout = setTimeout(checkValidity, 400)
		//   }
		// });

		function scanBarcode(){
			var input = document.getElementById("barcode");
		  input.addEventListener("keypress", function(event) {
		    if (event.key === "Enter") {
		      event.preventDefault();

		      var stat = 1;
					var wh2 = $("#warehouse").val();
					var midate = $("#midate").val();
					var miqty = $("#miqty").val();
					// console.log(wh2);
					if(wh2 == ""){
						Swal.fire({
						  icon: "info",
						  title: "Oops...",
						  text: "Please choose warehouse first!"
						});
						return;
					}

					var b = $("#barcode").val()
					let barcode = b.toUpperCase();

					const fData = [];
					var formData = {
	          tranno: $("#tranno2").val(),
	          pono: $("#pono").val(),
	          tgl_inspect: $("#tgl_inspect").val(),
	          wh: wh2,
	          vstat: stat,
	          barcode: barcode,
	          user: $("#user").val()
	        };
					fData.push(formData);
					const dataPost = {params: fData};
					// console.log(dataPost);
					loaderSpinner();
					$.ajax({
	        url : "<?php echo site_url('Transaction/scanMatlVendor')?>",
	        type: "POST",
	        data: dataPost,
	        success: function(data)
	        { 
	              console.log(data);
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
		                    	if(obj.detail[i]['INSD_LIN2'] != line){
											      var baris_baru = '<tr><td>'+obj.detail[i]['INSD_LIN2']+'</td><td>'+obj.detail[i]['INSD_CODE']+'</td><td>'+obj.detail[i]['INSD_NAME']+'</td><td>'+obj.detail[i]['INSD_IQTY']+'</td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

											        $("#detail_material").append(baris_baru);
											     }
											     line = obj.detail[i]['INSD_LIN2'];
		                    }
		                }
		              }
	              }

	              if(obj.status!='error'){
	              // load_table(obj.wh);
	              // reload_table();
	            	}

	            	if(obj.status!='error'){
	            		SoundSuccess();
	            	}else{
						SoundError();
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

		           SoundError();
	            }
	        });
		    }
		  });
		}
</script>