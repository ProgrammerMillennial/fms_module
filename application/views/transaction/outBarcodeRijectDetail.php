<div class="row">
	<div class="col-lg-12">
		<div class="card shadow mb-4 border-left-info">
			<div class="card-header">
				<h5 class="m-0 font-weight-bold text-gray-800">
                	<b>Out Barcode Riject Detail</b>
                </h5>
			</div>
        <div class="card-body">
        	<div class="row">
		        <div class="col-sm-3">
		        	<div class="form-group" id="tranno">
						<label class="font-weight-bold">Trans. No</label>
						<input type="text" name="tran_out" id="tran_rj" class="form-control" value="" disabled>		
					</div>
				</div>

				
			<!-- 		<div class="form-group">
		        <label class="font-weight-bold">Trans. Date</label>
						<div class="input-group">
		          <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_inspect" id="tgl_inspect" disabled/>
		        </div>
		      </div> -->
		      	<div class="col-sm-3">	
		      	<div class="form-group">
					<label class="font-weight-bold">RJ Date</label>
					<input type="text" name="rjdate2" id="rjdate2" class="form-control" value="" disabled>	
				</div>
	      		</div>


	      <!-- 		<div class="col-sm-3">	
				<div class="form-group">
		          <label class="font-weight-bold">RJ Date</label>
		            <div class="input-group date" id="tgl1" data-target-input="nearest">
		              <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="rjdate2" id="rjdate2" />
		                <div class="input-group-append" data-target="#tgl1" data-toggle="datetimepicker">
		                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
		                </div>
		            </div>
		        </div>
	       	  	</div>
 -->

	

		        <div class="col-sm-3">
		        	<div class="form-group">
						<label class="font-weight-bold">Total Qtty</label>
						<input type="text" name="tRqty" id="tRqty" class="form-control" value="" disabled>	
					</div>
				</div>
		    <div class="col-sm-2">
		        	<div class="form-group">
						<label class="font-weight-bold">User</label>
						<input type="text" name="user" id="user" class="form-control" value="<?php echo $_SESSION['user_id']; ?>" disabled>		
					</div>
				</div>
			</div>
      <div class="row">
		    <div class="col-sm-2">
		      <div class="form-group">
						<label class="font-weight-bold">PO No</label>
						<input type="text" name="pono" id="pono" class="form-control" value=""disabled>	
					</div>
				</div>
		    <div class="col-sm-2">
		      <div class="form-group">
						<label class="font-weight-bold">RJ No</label>
						<input type="text" name="rjno" id="rjno" class="form-control" value=""disabled>	
			  </div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<label class="font-weight-bold">RJ Date</label>
						<input type="text" name="rjdate" id="rjdate" class="form-control" value="" disabled>	
					</div>
          		</div>

				<div class="col-sm-2">
					<div class="form-group">
						<label class="font-weight-bold">Release</label>
						<input type="text" name="release" id="release" class="form-control" value=""disabled>	
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="font-weight-bold">Style</label>
						<input type="text" name="part" id="part" class="form-control" value=""disabled>	
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="font-weight-bold">Factory</label>
						<input type="text" name="fact" id="fact" class="form-control" value=""disabled>	
					</div>
				</div>
			</div>
        </div>
    </div>
	</div>
	<!-- <div class="col-lg-2"></div> -->
</div>
<?php //$this->load->view('button');?>

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
						      <th>Qty Barcode</th>
						      <th>Qty Riject</th>
			    			</tr>
						</thead>
						<tbody>
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
							<select class="form-control select2bs4" style="width: 100%;" id="vend_stat" required="required">
								<option value="">Barcode Type</option>
								<option value="0">Non Vendor</option>
								<option value="1">Vendor</option>
							</select>
						</div>
					</div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" class="form-control keydown" id="barcode" name ="barcode" placeholder="Barcode" autocomplete="off" onsubmit="return false">
                        </div>
                    </div>
                </div>

				<div class="table-responsive">
			    	<table class="table table-bordered text-gray-800" id="detail_material" width="100%" cellspacing="0">
						<thead>
			    			<tr>
						      <th>Serial</th>
						      <th class="text-center">Material Code</th>
						      <th>Material Name</th>
						      <th>Unit</th>
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

<script type="text/javascript">
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

  var ln;
  var method;
   $(document).ready(function() {
    var URL= window.location.href;
    var arr= URL.split('/');

		pono = arr[6];
		rjno = arr[7];
		rjdt = arr[8];
		rjqty = arr[9];
		vend = arr[10];
		mino = arr[11];
		part = arr[12];
		rls = arr[13];
		fact = arr[14];
		wh = arr[15];
    document.getElementById('barcode').value = '';
    document.getElementById("barcode").focus();
    $('[name="pono"]').val(pono);
    $('[name="rjno"]').val(rjno);
    $('[name="rjdate"]').val(rjdt);
    $('[name="rjdate2"]').val(rjdt);
    $('[name="release"]').val(rls);
    $('[name="part"]').val(part);
    $('[name="fact"]').val(fact);
	
		load_table();

	 $("#hapus").click(function() {
	    var id = $('#tran_rj').val();
		delete_all_matl(id);
	 });
	 scanBarcode();
   });

   // let inputStart, inputStop;

// 		$("#barcode")[0].onpaste = e => e.preventDefault();
// 		// handle a key value being entered by either keyboard or scanner
// 		var lastInput

// 		let checkValidity = () => {
// 		  if ($("#barcode").val().length < 10) {
// 		      $("#barcode").val('')
// 		  } else {
// 		    scanBarcode();
// 		  }
// 		  timeout = false
// 		}

// 		let timeout = false
// 		$("#barcode").keypress(function (e) {
// 		  if (performance.now() - lastInput > 1000) {
// 		    $("#barcode").val('')
// 		  }
// 		  lastInput = performance.now();
// 		  if (!timeout) {
// 		    timeout = setTimeout(checkValidity, 400)
// 		  }
// 		});

	function scanBarcode(){

	var input = document.getElementById("barcode");
	  input.addEventListener("keypress", function(event) {
	    if (event.key === "Enter") {
		    event.preventDefault();

		    var e = document.getElementById("vend_stat");
			var vend_stat = e.value;
			if(vend_stat == ''){
				alert('Please choose barcode type first !')
				return;
			}

		    var stat = 1;
		    // var e = document.getElementById("wh");
			// var wh = e.value;
			var tqty = $("#tRqty").val();
			if(tqty == ''){
				tqty = 0;
			}

			var b = $("#barcode").val()
			let barcode = b.toUpperCase();
			
			const fData = [];
			var formData = {
			  id: $("#tran_rj").val(),
			  barcode: barcode,
			  mrno: $("#rjno").val(),
			  qtty:tqty,
			  wh:wh,
			  vend_stat:vend_stat,
			  tgl:rjdt,
			  part:$("#part").val(),
			  nbrn:$("#release").val(),
			  opcd:'RJT',
			  fact:$("#fact").val(),
			  user:$("#user").val(),
			  proses:'RJT',
			  mono:'',
			  stt:'RJ'
	        };
			
			fData.push(formData);
			const dataPost = {params: fData};
			loaderSpinner();
			$.ajax({
	        url : "<?php echo site_url('Transaction/transbarcodeOut')?>",
	        type: "POST",
	        data: dataPost,
	        success: function(data)
	        { 
	              swal.close();
	              // console.log(data);
	              const obj = JSON.parse(data);
	              var line;
	              document.getElementById('barcode').value = '';
	              document.getElementById("barcode").focus();
		            if(obj.status!='error'){
		              $('#tranno').html("<label class='font-weight-bold'>Trans. No</label><input type='text' name='tran_rj' id='tran_rj' class='form-control' value ='"+obj.idHeader+"' disabled>");
		              document.getElementById('tRqty').value = obj.total;
		              for (let i = 0; i < obj.list.length; i++) {
		                for (var key in obj.list[i]) {
		                    if (obj.list[i].hasOwnProperty(key)) {
		                    	if(obj.list[i]['ONSD_LINE'] != line){
									var baris_baru = '<tr><td>'+obj.list[i]['ONSD_LINE']+'</td><td>'+obj.list[i]['ONSD_CODE']+'</td><td>'+obj.list[i]['ONSD_NAME']+'</td><td>'+obj.list[i]['ONSD_UNIT']+'</td><td>'+obj.list[i]['ONSD_QTTY']+'</td><td>'+obj.list[i]['ACTION']+'</td></tr>';

									$("#detail_material").append(baris_baru);
								}
								line = obj.list[i]['ONSD_LINE'];
		                    }
		                }
		              }
		                toastr.success(obj.message);
		                // reload_table();
		          	}else{
		          		toastr.error(obj.message);
		          	}
				  				  
					      // Toast.fire({
					      //   icon: 'success',
					      //   title: 'Success, the data has been saved !'
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
	  });
	}

	function delete_matl(line, vend_stat, ele){
		const fData = [];

		if(vend_stat == ''){
			alert('Please choose barcode type first !')
			return;
		}

		var formData = {
				  id: $("#tran_rj").val(),
				  mrno: $("#rjno").val(),
				  mono: '',
				  barcode: line,
				  vend_stat:vend_stat,
				  opcd:'RJT',
				  wh:wh
		        };
		fData.push(formData);
		const dataPost = {params: fData};
		ln = ele;
		method = 'N';
		delTrans(dataPost);
	}

	function delete_all_matl(line){
		const fData = [];
		var formData = {
				  id: line,
				  opcd:'RJT'
		        };
		fData.push(formData);
		const dataPost = {params: fData};
		method = 'Y';
		delTrans(dataPost);
	}

	function delTrans(d){
		const dataPost = d;
		if(method=='N'){
			var url = '<?php echo site_url('Transaction/deleteOutByBarcode')?>';
		}else{
			var url = '<?php echo site_url('Transaction/deleteOutAll')?>';
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
	              	console.log(data);
	              	const obj = JSON.parse(data);
	                Swal.fire(
	                            'Deleted!',
	                            'Your file has been deleted.',
	                            'success'
	                        );
	                if(method=='N'){
	              		delRowtable(ln);
	              	}else{
	              		$("#detail_material").find("tr:gt(0)").remove();
	              	}
	              	document.getElementById('tRqty').value = obj.total;
	                reload_table();
	                if(obj.delAll == '1'){
										$('#tranno').html("<label class='font-weight-bold'>Trans. No</label><input type='text' name='tran_rj' id='tran_rj' class='form-control' disabled>");
										document.getElementById('tRqty').value = '';
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

    function delRowtable(ele){
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

  function reload_table()
    {
      $("#tableHeader").DataTable().off('select');
      $("#tableHeader").DataTable().clear().destroy();
    
      load_table()
    }

	function load_table(){
		loaderSpinner();
	    $('#tableHeader').dataTable().fnClearTable();
	      var table1 =$('#tableHeader').DataTable({
	            // ajax: '<?php echo site_url('Transaction/loadDataRjByDetail')?>/' + rjno,
            "ajax": {
                "type" : "GET",
                "url" : '<?php echo site_url('Transaction/loadDataRjByDetail')?>/' + rjno,
                "dataSrc": function ( json ) {
                		swal.close();
                    return json.data;
                }       
            },
	        responsive: true,
	          bDestroy: true,
	          processing: false,
	          select: {
	                  style: 'single'
	                }
	    });
  	}
</script>