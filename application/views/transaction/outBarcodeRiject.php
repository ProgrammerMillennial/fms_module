<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<div class="card shadow mb-4 border-left-info">
			<div class="card-header">
				<h5 class="m-0 font-weight-bold text-gray-800">
                    <b>Out Barcode Riject</b>
                </h5>
			</div>
        <div class="card-body">
        	<div class="row">
        		<div class="col-sm-4">
			        <div class="form-group">
			            <label class="font-weight-bold">Warehouse</label>
			                <select class="form-control select2bs4" style="width: 100%;" id="wh" required="required">
<!-- 			                    <option value="">-</option>
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
						<label class="font-weight-bold">PO No</label>
						<input type="text" id="pono" class="form-control" value="">	
					</div>
				</div>
		        <div class="col-sm-4">
		        	<div class="form-group">
						<label class="font-weight-bold">RJ No</label>
						<input type="text" id="rjno" class="form-control" value="">	
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
						      <th>PO No</th>
						      <th>RJ No</th>
						      <th>RJ Date</th>
						      <th>RJ Qtty</th>
						      <th>Vendor</th>
						      <th>MI No</th>
						      <th>Part</th>
						      <th>Release</th>
						      <th>Factory</th>
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
		// var pono = $('#pono').val();
		// var rjno = $('#rjno').val();
		// var dt1 = $('#mr_date1').val();
		// var dt2 = $('#mr_date1').val();
		// var e = document.getElementById("fact");
		// var fact = e.value;
		// var f = document.getElementById("proces");
		// var opcd = f.value;

		// console.log(document.getElementById("mrno").value);
		// loadDataOut(mrno, dt1, dt2, fact, opcd);

	    $("#cari").click(function() {
		var pono = $('#pono').val();
		var rjno = $('#rjno').val();
		var dt1 = $('#mr_date1').val();
		var dt2 = $('#mr_date2').val();
		var e = document.getElementById("fact");
		var fact = e.value;
		var g = document.getElementById("wh");
		var wh = g.value;
			if(wh == ''){
				alert('Please choose warehouse first !')
				return;
			}
		    loadDataRiject(pono, rjno, dt1, dt2, fact, wh);
	    });
   });
 function loadDataRiject(pono, rjno, dt1, dt2, fact, wh){
 	clearTable();
	if(pono != '' || rjno != '' || dt1 != '' || dt2 != '' || fact != '' || wh != ''){
		console.log(dt2);
	  $('#tableHeader').dataTable().fnClearTable();
      var table = $('#tableHeader').DataTable({	
			"ajax": {
	        			'type': 'POST',
	        			'url': '<?php echo site_url('Transaction/loadDataRjByParam')?>',
	        			'data': {
	           						pono: pono,
	           						rjno: rjno,
	           						dt1: dt1,
	           						dt2: dt2,
	           						fact: fact,
	           						wh: wh
	        					}
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
		var pono ="";
		var rjno ="";
		var rjdt ="";
		var rjqty ="";
		var vend ="";
		var mino ="";
		var part ="";
		var rls ="";
		var fact ="";
		var wh ="";

		pono = table.row(indexes).data()[1];
		rjno = table.row(indexes).data()[2];
		rjdt = table.row(indexes).data()[3];
		rjqty = table.row(indexes).data()[4];
		vend = table.row(indexes).data()[5];
		mino = table.row(indexes).data()[6];
		part = table.row(indexes).data()[7];
		rls = table.row(indexes).data()[8];
		fact = table.row(indexes).data()[9];
		wh = table.row(indexes).data()[10];
		var url = '<?php echo site_url('Dashboard/outBarcodeRijectDetail')?>/'+pono+'/'+rjno+'/'+rjdt+'/'+rjqty+'/'+vend+'/'+mino+'/'+part+'/'+rls+'/'+fact+'/'+wh;
		window.open(url);
	})
 }
</script>