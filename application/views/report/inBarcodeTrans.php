<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<div class="card shadow mb-4 border-left-info">
			<div class="card-header">
					<h5 class="m-0 font-weight-bold text-gray-800">
          		<b>In Transaction</b>
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
								<label class="font-weight-bold">Serial</label>
								<input type="text" id="serial" class="form-control" value="">	
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
			<div class="my-3"></div>
				<div class="table-responsive">
			    	<table class="table table-bordered table-hover text-gray-800 text-center" id="tableHeader" width="100%" cellspacing="0">
						<thead>
			    			<tr align="center">
						      <th>No</th>
						      <th>Transaction No</th>
						      <th>Trans. Date</th>
						      <th>SJ No</th>
						      <th>PO No</th>
						      <th>Release</th>
						      <th>Style</th>
						      <th>Vendor</th>
						      <th>Ttl Inspect</th>
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

<?php $this->load->view('report/modalInBarcodeTrans');?>

<script type="text/javascript">
	$(document).ready(function() {
	    $("#cari").click(function() {
		  var pono = $('#pono').val();
		  var serial = $('#serial').val();
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
	        			'url': '<?php echo site_url('report/loadTransIn')?>',
	        			'data': {
	           						serial: serial,
	           						pono: pono,
	           						dt1: dt1,
	           						dt2: dt2,
	           						vend: vend,
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

      $('#tableHeader').DataTable().on('select', function (e, dt, type, indexes) {
            var idHeader ="";
            idHeader = table.row(indexes).data()[1];
            inspect_detail(idHeader);
          })

	    });
	    loadVendor();
      loadWarehouse();
   });

	function reload_table()
    {
      $("#tableHeader").DataTable().off('select');
      $("#tableHeader").DataTable().clear().destroy();
    
      load_table();
    }

	function saveTrans()
    {
      const fData = [];
      var kem=document.getElementsByName('kem[]');
      var jmlkem=document.getElementsByName('jmlkem[]');
      var qtty=document.getElementsByName('qtty_inspect[]');
      var serial=document.getElementsByName('serial[]');
      var trans = $("#tranno2").val();
      var stat = 0;
      var no = 1;
      var id_header = '';

      if(qtty.length==0 && trans == ''){
        Swal.fire(
                  'Error!',
                  'No data Found!',
                  'error'
                )
        return;
      }
      // console.log($("#mino2").val());
      if(qtty.length > 0){
        for(key=0; key < qtty.length; key++)  {
          var formData = {

            tranno: $("#tranno2").val(),
            seqn: $("#seqn").val(),
            seqh: 1,
            matcode: $("#matcode").val(),
            matname: $("#matname").val(),
            matunit: $("#matunit").val(),
            pono: $("#ponu").val(),
            mino: $("#mino2").val(),
            tgl_inspect: $("#tgl_inspect").val(),
            sjno: $("#sjno").val(),
            release: $("#rls1").val(),
            style: $("#part1").val(),
            price: $("#price").val(),
            umcd: $("#umcd").val(),
            vend: $("#vend").val(),
            wh: $("#wh").val(),
            user: $("#user").val(),
            vstat: stat,
            lineItem: no++,
            kem: kem[key].value,
            jmlkem: jmlkem[key].value,
            qtty: qtty[key].value,
            serial: serial[key].value
          };

          fData.push(formData);
        }
      }else{
        var formData = {
          tranno: $("#tranno2").val(),
          sjno: $("#sjno").val(),
          qtty: 0
        }
        fData.push(formData);
      }
       const dataPost = {params: fData};
       // console.log(dataPost);
       loaderSpinner();
       $.ajax({
        url : "<?php echo site_url('transaction/addTransaction')?>",
        type: "POST",
        data: dataPost,
        success: function(data)
        { 
              
              swal.close();
              // console.log(data);
              const obj = JSON.parse(data);
              var qty;
              var line;

              $('#tranno').html("<label for='exampleInputEmail1'><b>Trans. No</b></label><input type='text' class='form-control' id='tranno2' name='tranno2' value ='"+obj.id+"'  disabled>");
              if(obj.id==""){
                  $('#printall').html('<label><p></p></label><br><a class="btn btn-sm btn-info" title="Cetak"><i class="fas fa-print"></i> Print All</a>');
              }else{
                  $('#printall').html('<label><p></p></label><br><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.id+"'"+')"><i class="fas fa-print"></i> Print All</a>');
              }
              loadListInspect(obj.id);
              $("#detail_inspect").find("tr:gt(0)").remove();
              document.getElementById('kemasan').value = ''
              document.getElementById('jmlkem').value = ''
              document.getElementById('qtty2').value = ''
              for (let i = 0; i < obj.detail.length; i++) {
                for (var key in obj.detail[i]) {
                    if (obj.detail[i].hasOwnProperty(key)) {
                    if(obj.detail[i]['INSD_LINE'] != line){

                        var baris_baru = '<tr><td><input type="text" class="form-control" value='+obj.detail[i]['INSD_LINE']+' name="serial[]"disabled></td><td><input type="text" class="form-control" name="kem[]" value='+obj.detail[i]['INSD_KEMAS']+'></td><td><input type="text" class="form-control" name="jmlkem[]" value='+obj.detail[i]['INSD_TKEMAS']+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+obj.detail[i]['INSD_IQTY']+'></td><td><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.detail[i]['INSD_LINE']+"'"+')"><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></button></td></tr>';

                        $("#detail_inspect").append(baris_baru);
                    }
                    line = obj.detail[i]['INSD_LINE'];
                    }
                }
              }

              if(obj.stat == 'Y'){
                Toast.fire({
                  icon: 'success',
                  title: obj.message
                })
              }else{
                Toast.fire({
                  icon: 'error',
                  title: obj.message
                })
              }
               //if success close modal and reload ajax table
               // $('#modal_form').modal('hide');
              reload_table();

        },
        error: function (jqXHR, textStatus, errorThrown)
             {
              Toast.fire({
                icon: 'error',
                title: 'Error while saving the data, re-chek again !'
              })
            }
          });
     }

    function loadListInspect(id){

    var matcode = $("#matcode").val();
    var pono = $("#ponu").val();
    var mino= $("#mino2").val();
    var wh = $("#wh").val();

    const fData = [];

    var formData = {
      id:id,
      matcode: $("#matcode").val(),
      pono: $("#ponu").val(),
      mino: $("#mino2").val(),
      wh: $("#wh").val()
    };

    fData.push(formData);
    const dataPost = {params: fData};

      $("#header_inspect").find("tr:gt(0)").remove();
      // console.log(id);
      $.ajax({
        url : "<?php echo site_url('transaction/inspectionDetail')?>",
        type: "POST",
        data: dataPost,
        success: function(data)
        {
            const obj = JSON.parse(data);
            $('#bal_qty').html("<input type='text' id='bal_qty' name='bal_qty' value ='"+obj.inspect['balqty']+"'>");
            var baris_baru = '<tr><td>'+obj.inspect['seqn']+'</td><td>'+obj.inspect['matcode']+'</td><td>'+obj.inspect['matname']+'</td><td>'+obj.inspect['unit']+'</td><td>'+obj.inspect['balqty']+'</td><td>'+obj.inspect['poqty']+'</td><td>'+obj.inspect['inqty']+'</td></tr>';
            $("#header_inspect").append(baris_baru);
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

    function inspect_detail(id)
    {
      var line;
      $("#header_inspect").find("tr:gt(0)").remove();
      $("#detail_inspect").find("tr:gt(0)").remove();
      $.ajax({
        url : "<?php echo site_url('transaction/inspectionDetail')?>/" + id,
        type: "GET",
        success: function(data)
        {
            const obj = JSON.parse(data);

            $('[name="tranno2"]').val(obj.header['trans']);
            $('[name="ponu"]').val(obj.header['pono']);
            $('[name="mino2"]').val(obj.header['mino']);
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
            $('[name="bal_qty"]').val(obj.inspect['balqty']);
            $('[name="updt"]').val(1);

            // $('#button_ok').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="btnSave" onclick="saveTrans()">Update changes</button>');
            $('#printall').html('<label><p></p></label><br><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.header['trans']+"'"+')"><i class="fas fa-print"></i> Print All</a>');
            // $('#clearall').html('<label><p></p></label><br><a class="btn btn-sm btn-danger" title="Hapus" onClick="delete_all_matl('+"'"+obj.header['trans']+"'"+')"><i class="fas fa-trash"></i> Delete All</a>');

            var baris_baru = '<tr><td>'+obj.inspect['seqn']+'</td><td>'+obj.inspect['matcode']+'</td><td>'+obj.inspect['matname']+'</td><td>'+obj.inspect['unit']+'</td><td>'+obj.inspect['poqty']+'</td><td>'+obj.inspect['inqty']+'</td><td>'+obj.inspect['balqty']+'</td></tr>';
            $("#header_inspect").append(baris_baru);

            for (let i = 0; i < obj.barcode.length; i++) {
                for (var key in obj.barcode[i]) {
                    if (obj.barcode[i].hasOwnProperty(key)) {
                    if(obj.barcode[i]['barc'] != line){

                        var baris_baru = '<tr><td><input type="text" class="form-control" value='+obj.barcode[i]['barc']+' name="serial[]"disabled></td><td><input type="text" class="form-control" name="kem[]" value='+obj.barcode[i]['kem']+'></td><td><input type="text" class="form-control" name="jmlkem[]" value='+obj.barcode[i]['jmlkem']+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+obj.barcode[i]['qtty']+'></td><td><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.barcode[i]['barc']+"'"+')"><i class="fas fa-print"></i></a>&nbsp'+obj.barcode[i]['action']+'</td></tr>';

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


function saveTrans2()
    {
      const fData = [];
      var jmlkem = $("#jmlkem").val();
      var kem = $("#kemasan").val();
      var qtty = $("#qtty2").val();
      var bal_qty2 = "";
      var bal_qty2 = $("#bal_qty").val();
      let bal_qty = removeFormatting(bal_qty2);


      var trans = $("#tranno2").val();
      var stat = 0;
      var no = 1;
      var id_header = '';

      if(qtty.length==0 && trans == ''){
        Swal.fire(
                  'Error!',
                  'No data Found!',
                  'error'
                )
        return;
      }

      var formData = {
            tranno: $("#tranno2").val(),
            seqn: $("#seqn").val(),
            seqh: 1,
            matcode: $("#matcode").val(),
            matname: $("#matname").val(),
            matunit: $("#matunit").val(),
            pono: $("#ponu").val(),
            mino: $("#mino2").val(),
            tgl_inspect: $("#tgl_inspect").val(),
            sjno: $("#sjno").val(),
            release: $("#rls1").val(),
            style: $("#part1").val(),
            price: $("#price").val(),
            umcd: $("#umcd").val(),
            vend: $("#vend").val(),
            wh: $("#wh").val(),
            user: $("#user").val(),
            vstat: stat,
            lineItem: no++,
            kem: kem,
            jmlkem: jmlkem,
            qtty: qtty,
            serial:'',
            bal:bal_qty
          };

          fData.push(formData);
      
       const dataPost = {params: fData};
       loaderSpinner();
       $.ajax({
        url : "<?php echo site_url('transaction/addTransaction')?>",
        type: "POST",
        data: dataPost,
        success: function(data)
        { 
              
              swal.close();
              const obj = JSON.parse(data);
              var qty;
              var line;

              $('#tranno').html("<label for='exampleInputEmail1'><b>Trans. No</b></label><input type='text' class='form-control' id='tranno2' name='tranno2' value ='"+obj.id+"'  disabled>");
              if(obj.id==""){
                  $('#printall').html('<label><p></p></label><br><a class="btn btn-sm btn-info" title="Cetak"><i class="fas fa-print"></i> Print All</a>');
                  $('#clearall').html('<label><p></p></label><br><a class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i> Delete All</a>');
              }else{
                  $('#printall').html('<label><p></p></label><br><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.id+"'"+')"><i class="fas fa-print"></i> Print All</a>');
                  $('#clearall').html('<label><p></p></label><br><a class="btn btn-sm btn-danger" title="Hapus" onClick="delete_all_matl('+"'"+obj.id+"'"+', this)><i class="fas fa-trash"></i> Delete All</a>');
              }
              if(obj.id != ""){
                loadListInspect(obj.id);
              }
              $("#detail_inspect").find("tr:gt(0)").remove();
              document.getElementById('kemasan').value = ''
              document.getElementById('jmlkem').value = ''
              document.getElementById('qtty2').value = ''
              for (let i = 0; i < obj.detail.length; i++) {
                for (var key in obj.detail[i]) {
                    if (obj.detail[i].hasOwnProperty(key)) {
                    if(obj.detail[i]['INSD_LINE'] != line){

                        // var baris_baru = '<tr><td><input type="text" class="form-control" value='+obj.detail[i]['INSD_LINE']+' name="serial[]"disabled></td><td><input type="text" class="form-control" name="kem[]" value='+obj.detail[i]['INSD_KEMAS']+'></td><td><input type="text" class="form-control" name="jmlkem[]" value='+obj.detail[i]['INSD_TKEMAS']+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+obj.detail[i]['INSD_IQTY']+'></td><td><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.detail[i]['INSD_LINE']+"'"+')"><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></button></td></tr>';

                        var baris_baru = '<tr><td><input type="text" class="form-control" value='+obj.detail[i]['INSD_LINE']+' name="serial[]"disabled></td><td><input type="text" class="form-control" name="kem[]" value='+obj.detail[i]['INSD_KEMAS']+'></td><td><input type="text" class="form-control" name="jmlkem[]" value='+obj.detail[i]['INSD_TKEMAS']+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+obj.detail[i]['INSD_IQTY']+'></td><td><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.detail[i]['INSD_LINE']+"'"+')"><i class="fas fa-print"></i></a>&nbsp'+obj.detail[i]['ACTION']+'</td></tr>';

                        $("#detail_inspect").append(baris_baru);
                    }
                    line = obj.detail[i]['INSD_LINE'];
                    }
                }
              }

              if(obj.stat == 'Y'){
                Toast.fire({
                  icon: 'success',
                  title: obj.message
                })
              }else{
                Toast.fire({
                  icon: 'error',
                  title: obj.message
                })
              }
               //if success close modal and reload ajax table
               // $('#modal_form').modal('hide');
              reload_table();

        },
        error: function (jqXHR, textStatus, errorThrown)
             {
              Toast.fire({
                icon: 'error',
                title: 'Error while saving the data, re-chek again !'
              })
            }
          });
     }


  function delete_matl(line, ele){
    const fData = [];

    let result = line.substring(0, 1);
    let vnd;
    if(result !== 'B'){
        vnd = 1;
    }else{
        vnd = 0;
    }

    var formData = {
          id: $("#tranno2").val(),
          barcode: line,
          wh: $("#wh").val(),
          tgl_inspect: $("#tgl_inspect").val(),
          vend: vnd
    };

    fData.push(formData);
    const dataPost = {params: fData};
    ln = ele;
    method = 'N';
    delTrans(dataPost, ele);
  }

  function delete_all_matl(line, ele){
    const fData = [];
    var formData = {
          id: line
            };
    fData.push(formData);
    const dataPost = {params: fData};
    method = 'Y';
    delTrans(dataPost, ele);
  }

  function delTrans(d, ele){
    var id = $("#tranno2").val();

    const dataPost = d;
    if(method=='N'){
      var url = '<?php echo site_url('transaction/deleteInByBarcode')?>';
    }else{
      var url = '<?php echo site_url('transaction/deleteInAll')?>';
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
                      if(method=='N'){
                        deleteRow(ele);
                      }else{
                        $("#detail_inspect").find("tr:gt(0)").remove();
                      }
                      // document.getElementById('tOqty').value = obj.total;
                      loadListInspect(id);
                      reload_table();
                      if(obj.delAll == '1'){
                          $('#tranno2').html("<label for='exampleInputEmail1'><b>Trans. No</b></label><input type='text' class='form-control' id='tranno2' name='tranno2'>");
                          document.getElementById('tranno2').value = '';
                          document.getElementById("tranno2").disabled = false;
                          document.getElementById('sjno').value = '';
                      }
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

  function deleteRow(ele){
    var table = document.getElementById('detail_inspect');
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