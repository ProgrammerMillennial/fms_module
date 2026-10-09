<div class="card shadow mb-4 border-bottom-primary">
						<div class="card-header">
				              <h5 class="m-0 font-weight-bold text-gray-800">
                                  <b>Warehouse Area</b>
                         </h5>
			</div>
    <div class="card-body">
    <?php //$this->load->view('button');?>
<!-- 		<a href="" class="btn btn-primary btn-icon-split" data-placement="bottom" title="Cari">
	    	<span class="icon text-white-50">
				<i class="fa fa-search" aria-hidden="true"></i>
	    	</span>
	    <span class="text">Cari</span>
		</a> -->

	<div class="my-3"></div>
		<div class="table-responsive">
	    	<table class="table table-bordered text-gray-800" id="tableHeader" width="100%" cellspacing="0">
				<thead>
	    			<tr align="center">
						<th width="30px">No</th>
						<th>Warehouse ID</th>
						<th>Warehouse Name</th>
						<!-- <th width="14%">Action</th> -->
	    			</tr>
				</thead>
				
				<tbody>
				</tbody>
	    	</table>
		</div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
      $("#cari").click(function() {
		let table = new DataTable('#tableHeader', {
		      ajax: '<?php echo site_url('Master/listwarehouse')?>',
		  responsive: true,
	      bDestroy: true,
	      processing: true,
		    select: {
			        	style: 'single'
			        }
		});

	      // table =$('#tableHeader').DataTable({ 
	      //   "responsive": true,
	      //   "autoWidth": false,
	      //   "bDestroy": true,
	      //   "processing": true,
	      //   "ajax": {
	      //     "url": "<?php echo site_url('master/listwarehouse')?>",
	      //   },
	      // });

      })
   });
</script>