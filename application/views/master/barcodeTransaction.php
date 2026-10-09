<div class="row">
	<div class="col-lg-12">
	    <div class="card mb-4">
	    	<div class="card-header">
				<h5 class="m-0 font-weight-bold text-gray-800">
                    <b>Barcode Transaction</b>
                </h5>
			</div>
	        <div class="card-body">
				<div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="font-weight-bold">Barcode Type</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="vend_stat" required="required">
                                    <option value=""></option>
                                    <option value="0">Non Vendor</option>
                                    <option value="1">Vendor</option>
                                </select>
                        </div>
                    </div>
					<div class="col-sm-3">
						<label class="font-weight-bold">Barcode</label>
						<input type="text" id="barcode" name="barcode" class="form-control"  placeholder="Barcode">
					</div>

					<div class="col-sm-3">
						<label class="font-weight-bold">Material Name</label>
						<input type="text" name="nama" class="form-control" disabled  placeholder="Material Name">
					</div>

					<div class="col-sm-3">
						<label class="font-weight-bold">Qtty Akhir</label>
						<input type="number" name="qtty" class="form-control" disabled placeholder="Qtty">
					</div>

					<?php //$this->load->view('button');?>
<!-- 					<div class="col-sm-2">
                                    <button type="submit" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <span class="text">Search</span>
                                    </button>

					</div> -->
				</div>
	        </div>
	    </div>
	</div>

	<div class="col-lg-12">
		<div class="card shadow mb-4 border-bottom-primary">
		    <div class="card-body">
			<div class="my-3"></div>
				<div class="table-responsive">
			    	<table class="table table-bordered text-gray-800" id="tableHeader" width="100%" cellspacing="0">
						<thead>
			    			<tr align="center">
						      <th>No</th>
						      <th>Barcode</th>
						      <th>In Transaction</th>
						      <th>In Date</th>
						      <th>SJ No</th>
						      <th>PO No</th>
						      <th>In Qtty</th>
						      <th>Out Transaction</th>
						      <th>Out Date</th>
						      <th>MR No</th>
						      <th>Out Qtty</th>
			    			</tr>
						</thead>
						<tbody>

						</tbody>
			    	</table>
				</div>
		    </div>
		</div> </div>
</div>

<?php $this->load->view('transaction/modalInspectEntry');?>

<script type="text/javascript">
	let table;
	$(document).ready(function() {
      $("#cari").click(function() {
        var e = document.getElementById("vend_stat");
        var vend_stat = e.value;
            if(vend_stat == ''){
                alert('Please choose barcode type first !')
                swal.close();
                return;
            }
      	var barcode = $('#barcode').val();
      	// console.log(barcode);
		let table = new DataTable('#tableHeader', {
		      ajax: '<?php echo site_url('Master/DetTransBarcode')?>/' + barcode,
		  responsive: true,
	      bDestroy: true,
	      processing: true,
		    select: {
			        	style: 'single'
			        }
		});

		  // $('#tableHeader').DataTable().on('select', function (e, dt, type, indexes) {
          //   var idHeader ="";
          //   idHeader = table.row(indexes).data()[0];
          //   inspect_detail(idHeader);
          // })

		$.ajax({
        url : "<?php echo site_url('Master/headTransBarcode')?>/" + barcode + '/' + vend_stat,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
         // console.log(data.line);
          // $('[name="barcode"]').val(data.line);
          $('[name="nama"]').val(data.nama);
          $('[name="qtty"]').val(data.qtyakhir);
            
         },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error get data from ajax');
          }
        });

      })
   });

   function callDetail(idHeader){
   		// console.log(idHeader);
		inspect_detail(idHeader);
   }

   function inspect_detail(id)
    {
      var line;
      $("#header_inspect").find("tr:gt(0)").remove();
      $("#detail_inspect").find("tr:gt(0)").remove();
      $.ajax({
        url : "<?php echo site_url('Transaction/inspectionDetail')?>/" + id,
        type: "GET",
        success: function(data)
        {
            const obj = JSON.parse(data);

            $('[name="tranno2"]').val(obj.header['trans']);
            $('[name="ponu"]').val(obj.header['pono']);
            $('[name="tgl_inspect"]').val(obj.header['tglinsp']);
            $('[name="sjno"]').val(obj.header['sjno']);
            $('[name="seqn"]').val(obj.header['seqn']);
            $('[name="matcode"]').val(obj.header['matcode']);
            $('[name="matunit"]').val(obj.header['matunit']);
            $('[name="price"]').val(obj.header['price']);
            $('[name="umcd"]').val(obj.header['umcd']);
            $('[name="rls1"]').val(obj.header['release']);
            $('[name="part1"]').val(obj.header['style']);
            $('[name="wh"]').val(obj.header['wh']);
            $('[name="vend"]').val(obj.header['vend']);

            $('#button_ok').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="btnSave" onclick="saveTrans()">Update changes</button>');
            $('#printall').html('<label><p></p></label><br><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.header['trans']+"'"+')"><i class="fas fa-print"></i> Print All</a>');

            var baris_baru = '<tr><td>'+obj.inspect['seqn']+'</td><td>'+obj.inspect['matcode']+'</td><td>'+obj.inspect['matname']+'</td><td>'+obj.inspect['unit']+'</td><td>'+obj.inspect['poqty']+'</td><td>'+obj.inspect['inqty']+'</td><td>'+obj.inspect['balqty']+'</td></tr>';
            $("#header_inspect").append(baris_baru);

            for (let i = 0; i < obj.barcode.length; i++) {
                for (var key in obj.barcode[i]) {
                    if (obj.barcode[i].hasOwnProperty(key)) {
                    if(obj.barcode[i]['barc'] != line){

                        var baris_baru = '<tr><td><input type="text" class="form-control" value='+obj.barcode[i]['barc']+' name="serial[]"disabled></td><td><input type="text" class="form-control" name="kem[]" value='+obj.barcode[i]['kem']+'></td><td><input type="text" class="form-control" name="jmlkem[]" value='+obj.barcode[i]['jmlkem']+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+obj.barcode[i]['qtty']+'></td><td><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.barcode[i]['barc']+"'"+')"><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></button></td></tr>';

                        $("#detail_inspect").append(baris_baru);
                    }
                    line = obj.barcode[i]['barc'];
                    }
                }
              }
            // $('#inspectDetail').html(data);
            $('#modal_form').modal({backdrop: 'static', keyboard: false})          
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Inspection Detail'); // Set title to Bootstrap modal title
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
</script>