<div class="card shadow mb-4 border-bottom-primary">
						<div class="card-header">
				              <h5 class="m-0 font-weight-bold text-gray-800">
                                  <b>Location Rack</b>
                         </h5>
			</div>
    <div class="card-body">
    <?php //$this->load->view('button');?>

  <div class="col-sm-3">
      <div class="form-group">
          <label class="font-weight-bold">Rack Type</label>
              <select class="form-control select2bs4" style="width: 100%;" id="rack_type" required="required">
                  <option value=""></option>
                  <option value="R">Rack</option>
                  <option value="MZ">Mezzanine</option>
              </select>
      </div>
  </div>

	<div class="my-3"></div>
		<div class="table-responsive">
	    	<table class="table table-bordered text-gray-800" id="tableHeader" width="100%" cellspacing="0">
				<thead>
	    			<tr align="center">
						<th>Location Group</th>
						<th>Location Name</th>
	    			</tr>
				</thead>
				
				<tbody>
				</tbody>
	    	</table>
		</div>
		<pre id="example-console-rows"></pre>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
	  var loct = "";
	  var loct_type = "";

    $("#cari").click(function() {
    var e = document.getElementById("rack_type");
    var rack_type = e.value;
        if(rack_type == ''){
            alert('Please choose barcode type first !')
            swal.close();
            return;
        }

		let table = new DataTable('#tableHeader', {
		      ajax: '<?php echo site_url('Master/listlocation')?>/'+rack_type,
	      bDestroy: true,
	      processing: true,
	      responsive: true,
		    select: {
			        	style: 'single'
			        }
		});
		 
		table
		    .on('select', function (e, dt, type, indexes) {
		    	loct = table.row(indexes).data()[0];
		    	loct_type = table.row(indexes).data()[1];
		    })

      });

      $('#print').click(function(){
      		printLocation(loct, loct_type);
          // console.log(loct);
          
       });
   });

		function printLocation(id, loct_type){
      let tipe = id;
      var url = "http://172.16.160.3/prinRack/RackCode.php?LOCATION_ROW="+id+"&LOCATION_TYPE="+loct_type;
      window.open(url);
    }
</script>