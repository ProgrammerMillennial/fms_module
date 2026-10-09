<div class="row">
	<div class="col-lg-12">
	    <div class="card mb-4">
	    	<div class="card-header">
				  <h5 class="m-0 font-weight-bold text-gray-800">
            <b>Split Barcode</b>
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
							<input type="text" name="barcode" id="barcode" class="form-control" value="" placeholder="Barcode">
							<input type="hidden" id="user" name="user" value="<?php echo $_SESSION['user_id']; ?>">
						</div>

						<div class="col-sm-3">
							<label class="font-weight-bold">Material Name</label>
							<input type="text" name="nama" id="nama" class="form-control" disabled value="">
						</div>

						<div class="col-sm-3">
							<label class="font-weight-bold">Barcode Qtty</label>
							<input type="number" name="qtty" id="qtty" class="form-control" disabled value="">
						</div>
						<?php //$this->load->view('button');?>
					</div>
		    </div>
	    </div>
	</div>
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-6">
				<div class="card shadow mb-4 border-bottom-primary">
				    <div class="card-body">
				    	<div class="row">
					    	<div class="col-sm-3">
			            <div class="form-group">
			            	<a class="btn btn-info" href="<?php echo base_url(); ?>Dashboard/splitBarcode">New Split</i></a>
			              <a class="btn btn-info" id="add"><i class="fas fa-plus"></i></a>
			            </div>
			          </div>
		        	</div>
							<div class="my-3"></div>
							<div class="table-responsive tableFixHead">
								<table class="table table-hover text-nowrap text-gray-800 text-center" id="tableSplit">
						    	<!-- <table class="table table-bordered text-gray-800" id="tableSplit" width="100%" cellspacing="0"> -->
									<thead>
						    			<tr>
									      <th>No</th>
									      <th>New Barcode</th>
									      <th>Split Qtty</th>
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
			<div class="col-lg-6">
				<div class="card shadow mb-4 border-bottom-primary">
		    	<div class="card-header">
					  <h5 class="m-0 font-weight-bold text-gray-800">
	            <b>History Barcode</b>
	          </h5>
				  </div>
				    <div class="card-body">
							<div class="my-3"></div>
							<div class="table-responsive tableFixHead">
								<table class="table table-hover text-nowrap text-gray-800 text-center" id="tableSplit2">
						    	<!-- <table class="table table-bordered text-gray-800" id="tableSplit" width="100%" cellspacing="0"> -->
									<thead>
						    			<tr>
									      <th>No</th>
									      <th>Barcode</th>
									      <th>Qtty</th>
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
	</div>
</div>

<script type="text/javascript">
   $(document).ready(function() {
      $("#add").click(function() {
        // var kem = $("#kemasan").val();
        // var jmlKem = $("#jmlkem").val();
        // var qtty = $("#qtty").val();
        var qtty = 0;
      	if($('#barcode').val() == ""){
      		return;
      	}
        var baris_baru = '<tr><td>1</td><td><input type="text" class="form-control" id="serial[]" name="serial[]" value="" disabled></td><td><input type="number" class="form-control" id="qty_split[]" name="qty_split[]" value ='+ qtty +' onchange="qttyCekwithElement(this)"></td><td><button class="btn btn-danger btn btn-sm text-center" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></button></td></tr>';

        $("#tableSplit").append(baris_baru);
      })

      $("#cari").click(function() {
      	loaderSpinner();
      	// var barcode = $('#barcode').val();
      	var b = $("#barcode").val()
				let barcode = b.toUpperCase();
				findBarcode(barcode);
				detailSplitBarcode(barcode);
      })

      $("#simpan").click(function() {
      	loaderSpinner();
      	qttyCek2();
				dataSavesplit();
      })

      $("#hapus").click(function() {
      	loaderSpinner();
      	dataDeletesplit();
      })
   });

  function deleteRow(ele){
    var table = document.getElementById('tableSplit');
    var rowCount = table.rows.length;
      if(rowCount <= 1){
	  	Toast.fire({
	                icon: 'error',
	                title: 'There is no row available to delete!'
	              })
          return;
      }
        if(ele){
            ele.parentNode.parentNode.remove();
        }else{
            table.deleteRow(rowCount-1);
        }
      }

function qttyCekwithElement(ele) {
  var qty1 = document.getElementById("qtty").value;
  var qty2 = 0;
  var qty3 = 0;
  var qty_split=document.getElementsByName('qty_split[]');
	if(qty_split.length > 0){
    for(key=0; key < qty_split.length; key++)  {
    	qty3 = qty_split[key].value;
    	qty2 = qty2 + parseInt(qty3);
    }
	  if(qty2 > qty1){
	  	Toast.fire({
	                icon: 'error',
	                title: 'qtty split melebihi qtty barcode!'
	              })
			deleteRow(ele);
			return;
	  }
  }
}

function qttyCek2() {
  var qty1 = document.getElementById("qtty").value;
  var qty2 = 0;
  var qty3 = 0;
  var qty_split=document.getElementsByName('qty_split[]');
	if(qty_split.length > 0){
    for(key=0; key < qty_split.length; key++)  {
    	qty3 = qty_split[key].value;
    	qty2 = qty2 + parseInt(qty3);
    }
	  if(qty2 > qty1){
	  	Toast.fire({
	                icon: 'error',
	                title: 'qtty split melebihi qtty barcode!'
	              })
	  	swal.close();
	  	return;
	  }
  }
}

function findBarcode(barcode) {
	var e = document.getElementById("vend_stat");
	var vend_stat = e.value;
			if(vend_stat == ''){
				alert('Please choose barcode type first !')
				swal.close();
				return;
			}

	$("#tableSplit").find("tr:gt(0)").remove();
			$.ajax({
        url : "<?php echo site_url('Master/headTransBarcode')?>/" + barcode + '/' + vend_stat,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
         // console.log(data);
          // $('[name="barcode"]').val(data.line);
          swal.close();
          if(data.stat == 'success'){
	          $('[name="nama"]').val(data.nama);
	          $('[name="qtty"]').val(data.qtyakhir);
        	}else{
        		Swal.fire(
                  'Error!',
                  'No data Found!',
                  'error'
                )
        	}
            
         },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error get data from ajax');
          }
        });
}

function detailSplitBarcode(barcode){
	var e = document.getElementById("vend_stat");
	var vend_stat = e.value;

	const fData = [];
	var formData = {
		barcode: barcode,
		vend_stat:vend_stat
	};
			
	fData.push(formData);
	const dataPost = {params: fData};

	$.ajax({
	        url : "<?php echo site_url('Master/headTransBarcodeDetail')?>",
	        type: "POST",
	        data: dataPost,
	        success: function(data)
	        { 
	              // console.log(data);
	              const obj = JSON.parse(data);
	              var line;
		            if(obj.status = 'success'){
		            	$("#tableSplit2").find("tr:gt(0)").remove();
		              for (let i = 0; i < obj.list.length; i++) {
		                for (var key in obj.list[i]) {
		                    if (obj.list[i].hasOwnProperty(key)) {
		                    	if(obj.list[i]['serial'] != line){
									var baris_baru = '<tr><td>'+ obj.list[i]['no'] +'</td><td><input type="text" class="form-control" value='+obj.list[i]['serial']+' name="serial1[]" disabled></td><td><input type="number" class="form-control" name="qty_split1[]" value='+obj.list[i]['qtty']+' disabled></td><td>'+obj.list[i]['action']+'</td></tr>';

									$("#tableSplit2").append(baris_baru);
								}
								line = obj.list[i]['serial'];
		                    }
		                }
		              }
				            // Toast.fire({
							      //   icon: obj.status,
							      //   title: obj.message
							      // })
		                // toastr.success(obj.message);
		                // reload_table();
		          	}
				  			
				  			// Toast.fire({
							  //       icon: obj.status,
							  //       title: obj.message
							  // })
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

function dataSavesplit(){
	const fData = [];
	var e = document.getElementById("vend_stat");
	var vend_stat = e.value;
  var qty_split=document.getElementsByName('qty_split[]');
  var serial=document.getElementsByName('serial[]');
  var b = $("#barcode").val()
	let barcode = b.toUpperCase();
	
    if(qty_split.length==0){
        Swal.fire(
                  'Error!',
                  'No data Found!',
                  'error'
                )
        return;
    }
    for(key=0; key < qty_split.length; key++)  {
    	var formData = {
            barcode: barcode,
            qty_barcode: $("#qtty").val(),
            bar_nama: $("#nama").val(),
            vend_stat: vend_stat,
            serial: serial[key].value,
            user: $("#user").val(),
            qty_split: qty_split[key].value
      };

      fData.push(formData);
    }
    const dataPost = {params: fData};
    // console.log(dataPost);
       $.ajax({
        url : "<?php echo site_url('Transaction/dataSavesplit')?>",
        type: "POST",
        data: dataPost,
        success: function(data)
        { 
              console.log(data);
        			swal.close();
              const obj = JSON.parse(data);
              var qty;
              var line;
              var no = 0;
              console.log(obj.stat);
              if(obj.stat == 'OK'){
	              $("#tableSplit").find("tr:gt(0)").remove();
	              document.getElementById('qtty').value = obj.qtty_sisa;
	              for (let i = 0; i < obj.detail.length; i++) {
	                for (var key in obj.detail[i]) {
	                    if (obj.detail[i].hasOwnProperty(key)) {
	                    if(obj.detail[i]['INSD_LINE'] != line){
	                    	  no = no + 1;
	                        var baris_baru = '<tr><td>'+ no +'</td><td><input type="text" class="form-control" value='+obj.detail[i]['INSD_LINE']+' name="serial[]" disabled></td><td><input type="number" class="form-control" name="qty_split[]" value='+obj.detail[i]['INSD_IQTY']+' disabled></td><td>'+obj.detail[i]['ACTION']+'</td></tr>';

	                        $("#tableSplit").append(baris_baru);
	                    }
	                    line = obj.detail[i]['INSD_LINE'];
	                    }
	                }
	              }
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
             reloadDetailSplit();
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

function dataDeletesplit(){
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
	        if (result.value){
	        	  Deletesplit();
	        }else{
	        	return;
	        }
	      })
}

function Deletesplit(){
const fData = [];
	var e = document.getElementById("vend_stat");
  var qty_split=document.getElementsByName('qty_split[]');
  var serial=document.getElementsByName('serial[]');

    if(qty_split.length==0){
        Swal.fire(
                  'Error!',
                  'No data Found!',
                  'error'
                )
        return;
    }
    for(key=0; key < qty_split.length; key++){
    	var formData = {
            barcode: $("#barcode").val(),
            qty_barcode: $("#qtty").val(),
            bar_nama: $("#nama").val(),
            vend_stat: vend_stat,
            serial: serial[key].value,
            qty_split: qty_split[key].value
      };

      fData.push(formData);
    }
    const dataPost = {params: fData};
    console.log(dataPost);
       $.ajax({
        url : "<?php echo site_url('Transaction/dataDeletesplit')?>",
        type: "POST",
        data: dataPost,
        success: function(data)
        { 
        			swal.close();
              // console.log(data);
              const obj = JSON.parse(data);
              var qty;
              var line;
              var no = 0;
              if(obj.stat == 'success'){
	              $("#tableSplit").find("tr:gt(0)").remove();
	              document.getElementById('qtty').value = obj.qtty_sisa;
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

             reloadDetailSplit();
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

	function delete_matl(line, qty_split, vend_stat, ele){
		const fData = [];
		
			// if(vend_stat == ''){
			// 	alert('Please choose barcode type first !')
			// 	return;
			// }

		var formData = {
      barcode: $("#barcode").val(),
      qty_barcode: $("#qtty").val(),
      bar_nama: $("#nama").val(),
      vend_stat: vend_stat,
      serial: line,
      qty_split: qty_split
		};
		fData.push(formData);
		const dataPost = {params: fData};
		ln = ele;
		method = 'N';
		delTrans(dataPost);
	}

function delTrans(d){
		const dataPost = d;
		var url = '<?php echo site_url('Transaction/dataDeletesplit')?>';
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
											reloadDetailSplit();
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

function reloadDetailSplit(){
	var barcode = $('#barcode').val();
	$("#tableSplit2").find("tr:gt(0)").remove();
	detailSplitBarcode(barcode);
	findBarcode(barcode);
}

var input = document.getElementById("barcode");
	  input.addEventListener("keypress", function(event) {
	    if (event.key === "Enter") {
	      event.preventDefault();
	      loaderSpinner();
      	// var barcode = $('#barcode').val();
      	var b = $("#barcode").val()
				let barcode = b.toUpperCase();
				findBarcode(barcode);
				detailSplitBarcode(barcode);
	    }
	  });
</script>