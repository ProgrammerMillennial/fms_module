<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<div class="card shadow mb-4 border-left-info">
			<div class="card-header">
				<h5 class="m-0 font-weight-bold text-gray-800">
                    <b>Out Barcode</b>
                </h5>
			</div>
        <div class="card-body">
        	<div class="row">
        		<div class="col-sm-4">
			        <div class="form-group">
			            <label class="font-weight-bold">Warehouse</label>
			                <select class="form-control select2bs4" style="width: 100%;" id="wh" required="required">
<!-- 			                    <option value=""></option>
			                    <option value="MAT">Material Warehouse</option>
			                    <option value="ENG">Engineering Warehouse</option>
			                    <option value="IDC">IDC Warehouse</option> -->
			                </select>
			        </div>
		        </div>
        		<div class="col-sm-4">
			        <div class="form-group">
			            <label class="font-weight-bold">Factory</label>
			                <select class="form-control select2bs4" style="width: 100%;" id="fact">
			                    <option selected="selected"></option>
			                    <option value="IR2">Factory IR2</option>
			                    <option value="PM">Factory PM</option>
			                </select>
			        </div>
		        </div>
		        <div class="col-sm-4">
			        <div class="form-group">
			            <label class="font-weight-bold">Process Name</label>
			                <select class="form-control select2bs4" style="width: 100%;" id="proces">
			                    <option selected="selected"></option>
			                </select>
			        </div>
		        </div>
		        <div class="col-sm-4">
		        	<div class="form-group">
								<label class="font-weight-bold">MR No</label>
								<input type="text" id="mrno" class="form-control" value="">	
							</div>
						</div>
			<div class="col-sm-4">	
				<div class="form-group">
	              <label class="font-weight-bold">Date From</label>
	                <div class="input-group date" id="tgl1" data-target-input="nearest">
	                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="mr_date1" id="mr_date1" />
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
	                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl2" name="mr_date2" id="mr_date2"/>
	                      <div class="input-group-append" data-target="#tgl2" data-toggle="datetimepicker">
	                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
	                      </div>
	                </div>
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
			<div class="my-3"></div>
				<div class="table-responsive">
			    	<table class="table table-bordered table-hover text-gray-800 text-center" id="tableHeader" width="100%" cellspacing="0">
						<thead>
			    			<tr align="center">
						      <th>No</th>
						      <th>MR No</th>
						      <th>MR Date</th>
						      <th>Release</th>
						      <th>Style</th>
						      <th>Process ID</th>
						      <th>Process Name</th>
						      <th>Factory</th>
						      <th>Approve Qty</th>
						      <th>Out Qty</th>
						      <th>Balance Qty</th>
						      <th>Warehouse</th>
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
<script type="text/javascript">
	$(document).ready(function() {
		loadWarehouse();
		var mrno = $('#mrno').val();
		var dt1 = $('#mr_date1').val();
		var dt2 = $('#mr_date2').val();
		var e = document.getElementById("fact");
		var fact = e.value;
		var f = document.getElementById("proces");
		var opcd = f.value;
		var g = document.getElementById("wh");
		var wh = g.value;

		// console.log(document.getElementById("mrno").value);
		loadDataOut(mrno, dt1, dt2, fact, opcd, wh);

	    $("#cari").click(function() {
	    var mrno = $('#mrno').val();
		var dt1 = $('#mr_date1').val();
		var dt2 = $('#mr_date2').val();
		var e = document.getElementById("fact");
		var fact = e.value;
		var f = document.getElementById("proces");
		var opcd = f.value;
		var g = document.getElementById("wh");
		var wh = g.value;
			if(wh == ''){
				alert('Please choose warehouse first !')
				return;
			}
		    loadDataOut(mrno, dt1, dt2, fact, opcd, wh);
	    });
       $.ajax({
        url : "<?php echo site_url('Cglobal/loadProcessMat')?>",
        type: "GET",
        success: function(data)
        {
        	// console.log(data);
        	const obj = JSON.parse(data);
        	var cd;
        	for (let i = 0; i < obj.proc.length; i++) {
                for (var key in obj.proc[i]) {
                    if (obj.proc[i].hasOwnProperty(key)) {
                    if(obj.proc[i]['opcd'] != cd){
                        var baris_baru = '<option value='+obj.proc[i]['opcd']+'>'+obj.proc[i]['opcd_name']+'</option>';
                        $("#proces").append(baris_baru);
                    }
                    cd = obj.proc[i]['opcd'];
                    }
                }
             }
        },
        error: function (jqXHR, textStatus, errorThrown)
             {
				Toast.fire({
					icon: 'error',
				    title: 'Error while saving the data, re-chek again !'
				})
            }
        });
   });
 function loadDataOut(mrno,dt1,dt2,fact,opcd,wh){
 	var tipe = 'MR';
 	clearTable();
	if(mrno != '' || dt1 != '' || dt2 != '' || fact != '' || opcd != '' || wh != ''){
	  $('#tableHeader').dataTable().fnClearTable();
      var table = $('#tableHeader').DataTable({	
			"ajax": {
	        			'type': 'POST',
	        			'url': '<?php echo site_url('Transaction/loadDataMrByParam')?>',
	        			'data': {
	           						mrno: mrno,
	           						dt1: dt1,
	           						dt2: dt2,
	           						fact: fact,
	           						opcd: opcd,
	           						wh: wh,
	           						tipe: tipe
	        					}
						// 'success': function(data){
						//         alert(data);
						//     }
	        },
		      bDestroy: true,
		      processing: false,
		      responsive: true,
			    select: {
				        	style: 'single'
				        }
			});
	}else{
	  $('#tableHeader').dataTable().fnClearTable();
      var table = $('#tableHeader').DataTable({	
		      bDestroy: true,
		      processing: false,
		      responsive: true,
			    select: {
				        	style: 'single'
				        }
			});
	}

	$('#tableHeader').DataTable().on('select', function (e, dt, type, indexes) {
		var mrno ="";
		var tgl ="";
		var rls ="";
		var style ="";
		var proc ="";
		var fact ="";
		mrno = table.row(indexes).data()[1];
		tgl = table.row(indexes).data()[2];
		rls = table.row(indexes).data()[3];
		style = table.row(indexes).data()[4];
		proc = table.row(indexes).data()[5];
		fact = table.row(indexes).data()[7];
		wh = table.row(indexes).data()[11];
		
		if(wh == 'MAT'){
			var url = '<?php echo site_url('Dashboard/outBarcodeDetail')?>/'+mrno+'/'+tgl+'/'+rls+'/'+style+'/'+proc+'/'+fact+'/'+wh+'/'+mrno.substr(0, 2);
		}else{
			var url = '<?php echo site_url('Dashboard/outBarcodeDetailEng')?>/'+mrno+'/'+tgl+'/'+rls+'/'+style+'/'+proc+'/'+fact+'/'+wh+'/'+mrno.substr(0, 2);
		}
		
		window.open(url);
	})
 }
</script>