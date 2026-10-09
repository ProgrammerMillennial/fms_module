<!-- <h1 class="h3 mb-4 text-gray-800">Blank Page</h1> -->
<div class="row">
	<!-- <div class="col-lg-2"></div> -->
	<div class="col-lg-12">
		<div class="card shadow mb-4 border-left-info">
						<div class="card-header">
				              <h5 class="m-0 font-weight-bold text-gray-800">
                                  <b>Out Barcode Riject</b>
                         </h5>
			</div>
        <div class="card-body">
        	<div class="row">
<!--         		<div class="col-sm-12">
			        <div class="form-group">
			            <label class="font-weight-bold">Warehouse</label>
			                <select class="form-control select2bs4" style="width: 100%;">
			                    <option selected="selected"></option>
			                    <option>Material Warehouse</option>
			                    <option>Engineering Warehouse</option>
			                    <option>IDC Warehouse</option>
			                </select>
			        </div>
		        </div> -->
		        <div class="col-sm-3">
		        	<div class="form-group">
								<label class="font-weight-bold">PO No</label>
								<input type="text" name="barcode" class="form-control" value="">	
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
								<label class="font-weight-bold">PO Date</label>
								<input type="text" name="barcode" class="form-control" value="" disabled>	
					</div>
<!-- 					<div class="form-group">
		              <label class="font-weight-bold">MR Date</label>
		                <div class="input-group date" id="tgl1" data-target-input="nearest">
		                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_belanja" />
		                      <div class="input-group-append" data-target="#tgl1" data-toggle="datetimepicker">
		                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
		                      </div>
		                </div>
		            </div> -->
          		</div>
			</div>
					 <div class="row">
							<div class="col-sm-2">
								<div class="form-group">
									<label class="font-weight-bold">Release</label>
									<input type="text" name="barcode" class="form-control" value="">	
								</div>
						  </div>

						  <div class="col-sm-3">
								<div class="form-group">
									<label class="font-weight-bold">Style</label>
									<input type="text" name="barcode" class="form-control" value="" disabled>	
								</div>
						  </div>

						  <div class="col-sm-2">
								<div class="form-group">
									<label class="font-weight-bold">Factory</label>
									<input type="text" name="barcode" class="form-control" value="">	
								</div>
						  </div>

<!-- 						  <div class="col-sm-3">
								<div class="form-group">
									<label class="font-weight-bold">Process</label>
									<input type="text" name="barcode" class="form-control" value="">	
								</div>
						  </div>
 -->
			        		<div class="col-sm-2">
						        <div class="form-group">
						            <label class="font-weight-bold">Warehouse</label>
						                <select class="form-control select2bs4" style="width: 100%;">
						                    <option selected="selected"></option>
						                    <option>Material Warehouse</option>
						                    <option>Engineering Warehouse</option>
						                    <option>IDC Warehouse</option>
						                </select>
						        </div>
					        </div>

					 </div>
        </div>
        <?php $this->load->view('button');?>
    </div>
	</div>
	<!-- <div class="col-lg-2"></div> -->
</div>


<div class="row">
	<div class="col-sm-5">
		<div class="card shadow mb-2 border-bottom-primary">
		    <div class="card-body">
				<div class="table-responsive">
			    	<table class="table table-bordered text-gray-800" id="tableHeader" width="100%" cellspacing="0">
						<thead>
			    			<tr>
						      <th>Material Code</th>
						      <th>Material Name</th>
						      <th>Unit</th>
						      <th>Qtty</th>
			    			</tr>
						</thead>
						<tbody>
							<tr>
								<td>C2700(0691B</td>
								<td>NASA 600;44";;M ;CLEAR;0.10 MM</td>
								<td>M</td>
								<td>10</td>
                </tr>
						</tbody>
			    	</table>
				</div>
		    </div>
		</div>
	</div>

	<div class="col-sm-7">
		<div class="card shadow mb-2 border-bottom-primary">
		    <div class="card-body">
		    	<div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" class="form-control keydown" id="barcode" placeholder="Barcode">
                        </div>
                    </div>
                </div>

				<div class="table-responsive">
			    	<table class="table table-bordered text-gray-800" id="tableDetail" width="100%" cellspacing="0">
						<thead>
			    			<tr>
						      <th>Serial</th>
						      <th class="text-center">Material Code</th>
						      <th>Material Name</th>
						      <th>Unit</th>
						      <th>Qtty</th>
			    			</tr>
						</thead>
						<tbody>
							<tr>
								<td>12345</td>
								<td>C2700(0691B</td>
								<td>NASA 600;44";;M ;CLEAR;0.10 MM</td>
								<td>M</td>
								<td>10</td>
                </tr>
						</tbody>
			    	</table>
				</div>
		    </div>
		</div>
	</div>
</div>

<?php $this->load->view('transaction/modalInspectVendor');?>

<script type="text/javascript">

    function addMatVendor(id)
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
      $('.modal-title').text('Add new material'); // Set Title to Bootstrap modal title
      $("#detail_inspect").find("tr:gt(0)").remove();
    }

    $(function() {
	    $('.keydown').keydown(function() {
          alert('You pressed enter!');
          return;
	    });
	});

</script>

<script type="text/javascript">
   $(document).ready(function() {
      $("#add").click(function() {
        var kem = $("#kemasan").val();
        var jmlKem = $("#jmlkem").val();
        var qtty = $("#qtty").val();

        if(qtty == "" || qtty < 0 ){
          alert("There is no qtty to input!");
          return;
        }else{

        var baris_baru = '<tr><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a></td><td><input type="text" class="form-control" id="serial[]"" value=""></td><td><input type="text" class="form-control" id="kem[]" value='+kem+'></td><td><input type="text" class="form-control" id="jmlkem[]" value='+jmlKem+'></td><td><input type="number" class="form-control" id="qtty[]" value='+qtty+'></td><td><button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

        $("#detail_material").append(baris_baru);
        }
      })
   });

  function deleteRow(ele){
    var table = document.getElementById('detail_material');
    var rowCount = table.rows.length;
      if(rowCount <= 1){
          alert("There is no row available to delete!");
          return;
      }
        if(ele){
            ele.parentNode.parentNode.remove();
        }else{
            table.deleteRow(rowCount-1);
        }
      }
</script>