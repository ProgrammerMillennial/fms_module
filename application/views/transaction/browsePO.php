<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<div class="card shadow mb-4 border-left-info">
						<div class="card-header">
				              <h5 class="m-0 font-weight-bold text-gray-800">
                                  <b>Browse PO</b>
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
					 	<div class="col-sm-12">
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
			<div class="my-3"></div>
				<div class="table-responsive">
			    	<table class="table table-bordered table-hover text-gray-800 text-center" id="tableHeader" width="100%" cellspacing="0">
						<thead>
			    			<tr align="center">
						      <th>No</th>
						      <th>PO No</th>
						      <th>MI No</th>
						      <th>Release</th>
						      <th>Style</th>
						      <th>Vendor</th>
						      <th>MI Date</th>
						      <th>Ttl Qtty</th>
						      <th>MI Qtty</th>
						      <th>Ttl Inspect</th>
						      <th>Bal Inspect</th>
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
	    $("#cari").click(function() {
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

			// console.log(e3);
			// // var a1 = selectElem.getElementsByTagName('option'); 
			// var a2 = []; 
			// for(var i=0; i<e3.length; i++) { 
			//   if(e3[i].selected) 
			//     a2.push(e3[i].value); 
			// } 
			// alert(a2.join(','));

			$("#tableHeader").DataTable().off('select');
      		$("#tableHeader").DataTable().clear().destroy();

	    	$('#tableHeader').dataTable().fnClearTable();
      		var table = $('#tableHeader').DataTable({	
			// let table = new DataTable('#tableHeader', {
			"ajax": {
	        			'type': 'POST',
	        			'url': '<?php echo site_url('Transaction/listPoDetail')?>',
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
		      processing: false,
		      responsive: true,
			    select: {
				        	style: 'single'
				        }
			});

			$('#tableHeader').DataTable().on('select', function (e, dt, type, indexes) {
			    	var pono1 ="";
			    	var mino ="";
			    	var qtty ="";
			    	var qty_bal = "";
			    	qty_bal = Number.parseFloat(table.row(indexes).data()[10]);

			    	// if(qty_bal == 0){
				  	// Toast.fire({
				    //             icon: 'error',
				    //             title: 'Balance inspect 0!'
				    //           })
			      //     return;
			      // }

			    	pono1 = table.row(indexes).data()[1];
			    	mino = table.row(indexes).data()[2];
			    	qtty = table.row(indexes).data()[7];
			    	var url ='<?php echo site_url('Dashboard/browsePOdetail')?>/'+pono1+'/'+ mino+'/'+ wh +'/'+qtty;
			    	window.open(url);
			    })

	    });
	    loadVendor();
	    loadWarehouse();
	    // SoundSuccess();
   });

	function reload_table()
    {
      $("#tableHeader").DataTable().off('select');
      $("#tableHeader").DataTable().clear().destroy();
    
      load_table();
    }
</script>