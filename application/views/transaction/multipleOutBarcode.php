<div class="row">
	<div class="col-lg-12">
		<div class="card shadow mb-4 border-left-info">
			<div class="card-header">
				<h5 class="m-0 font-weight-bold text-gray-800">
            <b>Out Barcode ADD</b>
        </h5>
			</div>
        <div class="card-body">
        	<div class="row">
		        <div class="col">
		        	<div class="form-group" id="tranno">
								<label class="font-weight-bold">Trans. No</label>
								<input type="text" name="tran_out" id="tran_out" class="form-control" value="" disabled>	
							</div>
						</div>

						<div class="col">	
							<div class="form-group">
	          		<label class="font-weight-bold">Date</label>
	            	<div class="input-group date" id="tgl1" data-target-input="nearest">
	              	<input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_inspect" id="mrdate2" />
	                <div class="input-group-append" data-target="#tgl1" data-toggle="datetimepicker">
	                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
	                </div>
	            	</div>
	       			</div>
       			</div>
		    		<div class="col">
			    		<div class="form-group">
			        	<label class="font-weight-bold">Warehouse</label>
			        	<select class="form-control select2bs4" style="width: 100%;" id="wh" required="required"></select>
			    		</div>
						</div>
        		<div class="col">
			        <div class="form-group">
			            <label class="font-weight-bold">Factory</label>
			                <select class="form-control select2bs4" style="width: 100%;" id="fact">
			                    <option selected="selected"></option>
			                    <option value="IR2">Factory IR2</option>
			                    <option value="PM">Factory PM</option>
			                </select>
			        </div>
		        </div>
		   			<div class="col">
		        	<div class="form-group">
								<label class="font-weight-bold">Total Barcode</label>
								<input type="text" name="tOqty" id="tOqty" class="form-control" value="" disabled>	
							</div>
						</div>
		   			<div class="col">
		        	<div class="form-group">
								<label class="font-weight-bold">Total ERP</label>
								<input type="text" name="tOqty_erp" id="tOqty_erp" class="form-control" value="" disabled>	
							</div>
						</div>
		    		<div class="col-sm-2">
		        	<div class="form-group">
								<label class="font-weight-bold">User</label>
								<input type="text" name="user" id="user" class="form-control" value="<?php echo $_SESSION['user_id']; ?>" disabled>		
							</div>
						</div>
					</div>
        </div>
    </div>
	</div>
</div>


<div class="row">
<div class="col-sm-6">
	<div class="row">
		<div class="col-sm-12">
			<div class="card shadow mb-4 border-left-info">
	      <div class="card-body">
	      	<div class="row">
		      	<div class="col-sm-6">
			        <div class="form-group">
			            <input type="text" class="form-control keydown" id="mr_num" name ="mr_num" placeholder="MR Number" autocomplete="off" onsubmit="return false">
			        </div>
		      	</div>
            <div class="col-sm-1">
              <div class="form-group">
                <a class="btn btn-primary" id="go_add">Load</a>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <a class="btn btn-danger" id="go_clr">Clear All</a>
              </div>
            </div>
	      	</div>
					<div class="table-responsive tableFixHead2">
				    	<table class="table table-bordered text-gray-800" id="detail_mr" width="100%" cellspacing="0">
							<thead>
				    			<tr>
							      <th width="120px">MR No</th>
							      <th>MR Date</th>
							      <th>Release</th>
							      <th>Style</th>
							      <th>Factory</th>
							      <th>Proccess</th>
							      <th>Act</th>
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
	<div class="row">
		<div class="col-sm-12">
			<div class="card shadow mb-2 border-bottom-primary">
			    <div class="card-body">
					<div class="table-responsive">
				    	<table class="table table-bordered text-gray-800" id="tableHeader" width="100%" cellspacing="0">
							<thead>
				    			<tr>
							      <th>Material Name</th>
							      <th>Unit</th>
							      <th>Qty Barcode</th>
							      <th>Qty ERP</th>
							      <th>Balance Qtty</th>
							      <th>Material Code</th>
							      <!-- <th>Total Cons</th> -->
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
	<div class="col-sm-6">
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

				<div class="table-responsive tableFixHead">
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

   $(document).ready(function() {
   	loadWarehouse();
   	loadMRAdd();
   	scanBarcode();
    $("#go_add").click(function() {
    	loadMRHeader();
			loadMRDetail();
    })

    $("#go_clr").click(function() {
    	var dm = document.getElementById('detail_material');
	    var rowCount2 = dm.tBodies[0].rows.length;
	    console.log(rowCount2);
	    if(rowCount2 > 0){
					Swal.fire(
                  'Just Info!',
                  'Material has been out, delete first !',
                  'info'
          )
	    		return;
	    }
    	$("#detail_mr").find("tr:gt(0)").remove();
    	reload_table();
    })
   });

  function loadMRAdd(){
 	  var mr = document.getElementById("mr_num");
	  mr.addEventListener("keypress", function(event){
	    if (event.key === "Enter") {
		      event.preventDefault();

        	var mr_num = $("#mr_num").val();
        	if(mr_num == ""){
        				Swal.fire(
                  'Just Info!',
                  'There is no MR to input!',
                  'info'
                )
          	return;
        	}else{

        		var baris_baru = '<tr><td><input type="text" class="form-control" name="mrno[]" value='+mr_num+' style="font-size:11px;"></td><td><input type="text" class="form-control" name="mrdt[]" style="font-size:11px;" disabled></td><td><input type="text" class="form-control" name="rls[]" style="font-size:11px;" disabled></td><td><input type="text" class="form-control" name="styl[]" style="font-size:11px;" disabled></td><td><input type="text" class="form-control" name="fact[]" style="font-size:11px;" disabled></td><td><input type="text" class="form-control" name="opcd[]" style="font-size:11px;" disabled></td><td><button class="btn btn-danger btn btn-sm" value="delete" onclick="delRowtable2(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

        		$("#detail_mr").append(baris_baru);
        	}
        	document.getElementById("mr_num").value = '';

		   }
		}) 	
  }

  function loadMRHeader(){
		const fData = [];
    var mrno=document.getElementsByName('mrno[]');

    if(mrno.length==0){
        Swal.fire(
                  'Just Info!',
                  'No data Found!',
                  'info'
                )
        document.getElementById('tOqty_erp').value = '';
        return;
    }
      if(mrno.length > 0){
        for(key=0; key < mrno.length; key++)  {
          var formData = {
            mrno: mrno[key].value
          };

          fData.push(formData);
        }
			}
      
      const dataPost = {params: fData};
			$.ajax({
	        url : "<?php echo site_url('Transaction/loadMRListHead')?>",
	        type: "POST",
	        data: dataPost,
	        success: function(data)
	        {
	        		$("#detail_mr").find("tr:gt(0)").remove();
	        		const obj = JSON.parse(data);
							var line;
							var ttl_erp = 0;
	        		for (let i = 0; i < obj.header.length; i++){
		                for (var key in obj.header[i]){
		                    if(obj.header[i].hasOwnProperty(key)) {
		                    	if(obj.header[i]['mrno'] != line){
															var baris_baru = '<tr><td><input type="text" class="form-control" name="mrno[]" value="'+obj.header[i]['mrno']+'" style="font-size:11px;" disabled></td><td><input type="text" class="form-control" name="mrdt[]" style="font-size:11px;" disabled value="'+obj.header[i]['tgl']+'"></td><td><input type="text" class="form-control" name="rls[]" style="font-size:11px;" disabled value='+obj.header[i]['rls']+' ></td><td><input type="text" class="form-control" name="styl[]" style="font-size:11px;" disabled value='+obj.header[i]['style']+' ></td><td><input type="text" class="form-control" name="fact[]" style="font-size:11px;" disabled value='+obj.header[i]['fact']+' ></td><td><input type="text" class="form-control" name="opcd[]" style="font-size:11px;" disabled value='+obj.header[i]['opcd']+' ><input type="hidden" class="form-control" name="qtty[]" style="font-size:11px;" disabled value='+obj.header[i]['tqty_erp']+' ></td><td><button class="btn btn-danger btn btn-sm" value="delete" onclick="delRowtable2(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

																$("#detail_mr").append(baris_baru);
																ttl_erp = ttl_erp + parseFloat(obj.header[i]['tqty_erp']);
																document.getElementById('tOqty_erp').value = ttl_erp;
														}
														line = obj.header[i]['mrno'];
		                    }
		                }
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
	    })
  }

  function loadMRDetail(){
		const fData = [];
    var mrno=document.getElementsByName('mrno[]');
    var rls=document.getElementsByName('rls[]');
    var style=document.getElementsByName('styl[]');
    var fact=document.getElementsByName('fact[]');
    var opcd=document.getElementsByName('opcd[]');
    var qtty=document.getElementsByName('qtty[]');
		var w = document.getElementById("wh");
		var wh = w.value;
		var f;

    if(mrno.length==0){
        Swal.fire(
                  'Just Info!',
                  'No data Found!',
                  'info'
                )
        return;
    }
      if(mrno.length > 0){
        for(key=0; key < mrno.length; key++)  {
          var formData = {
            mrno: mrno[key].value,
            fact: fact[key].value
          };

          fData.push(formData);
        }
			}
      
      // const dataPost = {params: fData};
	    $('#tableHeader').dataTable().fnClearTable();
      		var table = $('#tableHeader').DataTable({	
			// let table = new DataTable('#tableHeader', {
			"ajax": {
	        			'type': 'POST',
	        			'url': '<?php echo site_url('Transaction/loadMRListDetil')?>',
	        			'data': {
	           						params: fData
	        					}
	        },
		      bDestroy: true,
		      processing: false,
		      responsive: true,
			    select: {
				        	style: 'single'
				        }
			});
  }

	function scanBarcode(){
		var tipe = 'KT';
		var barc = document.getElementById("barcode");
		barc.addEventListener("keypress", function(event) {
	    if (event.key === "Enter") {
		    event.preventDefault();

			var mrno=document.getElementsByName('mrno[]');
    	var rls=document.getElementsByName('rls[]');
    	var style=document.getElementsByName('style[]');
    	var fact=document.getElementsByName('fact[]');
    	var opcd=document.getElementsByName('opcd[]');
    	var qtty=document.getElementsByName('qtty[]');
		  var e = document.getElementById("vend_stat");
			var vend_stat = e.value;
			if(vend_stat == ''){
				alert('Please choose barcode type first !')
				return;
			}

		  var stat = 1;
		  var w = document.getElementById("wh");
			var wh = w.value;
			var tqty = $("#tOqty").val();
			if(tqty == ''){
				tqty = 0;
			}

			const mrData = [];
			if(mrno.length > 0){
        for(key=0; key < mrno.length; key++)  {
          var formData = {
            mrno: mrno[key].value,
            rls: rls[key].value,
            style: style[key].value,
            fact: fact[key].value,
            opcd: opcd[key].value,
            qtty: qtty[key].value,
            wh: wh,
          };

          mrData.push(formData);
        }
			}

			const fData = [];
			var b = $("#barcode").val()
			let barcode = b.toUpperCase();
			var formData = {
			  id: $("#tran_out").val(),
			  barcode: barcode,
			  mrno: '',
			  qtty:tqty,
			  wh:wh,
			  vend_stat:vend_stat,
			  tgl:$("#mrdate2").val(),
			  part:'',
			  nbrn:'',
			  opcd:'',
			  fact:$("#fact").val(),
			  user:$("#user").val(),
			  proses:'TMR',
			  mono:'',
			  stt:tipe
	    };
			fData.push(formData);
			const dataPost = {
				params: fData,
				mrcd : mrData
			};

			// console.log(dataPost);
			// loaderSpinner();
			$.ajax({
	        url : "<?php echo site_url('Transaction/transMultipleOutBarc')?>",
	        type: "POST",
	        data: dataPost,
	        success: function(data)
	        { 
	          		// swal.close();
	              console.log(data);
	              const obj = JSON.parse(data);
	              var line;
	              document.getElementById('barcode').value = '';
	              document.getElementById("barcode").focus();
		            if(obj.status!='error'){
		              $('#tranno').html("<label class='font-weight-bold'>Trans. No</label><input type='text' name='tran_out' id='tran_out' class='form-control' value ='"+obj.idHeader+"' disabled>");
		              document.getElementById('tOqty').value = obj.total;
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
				            Toast.fire({
							        icon: obj.status,
							        title: obj.message
							      })
		                // toastr.success(obj.message);
		                reload_table();
		          	}
				  			
				  			Toast.fire({
							        icon: obj.status,
							        title: obj.message
							  })

							  if(obj.status!='error'){
	            		SoundSuccess();
	            	}else{
									SoundError();
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
				  id: $("#tran_out").val(),
				  mrno: $("#mrno").val(),
				  mono:'',
				  barcode: line,
				  vend_stat:vend_stat,
				  opcd:$("#proc").val(),
				  part:part,
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
				  id: line
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
	              	// console.log(data);
	              	const obj = JSON.parse(data);
	              	if(obj.status == 'success'){
			                Swal.fire(
			                            obj.title,
			                            obj.message,
			                            obj.status
			                        );
			                if(method=='N'){
			              		delRowtable(ln);
			              	}else{
			              		$("#detail_material").find("tr:gt(0)").remove();
			              	}
			              	document.getElementById('tOqty').value = obj.total;
			                reload_table();
			                if(obj.delAll == '1'){
													$('#tranno').html("<label class='font-weight-bold'>Trans. No</label><input type='text' name='tran_out' id='tran_out' class='form-control' disabled>");
													document.getElementById('tOqty').value = '';
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

    function delRowtable(ele){
	    var table = document.getElementById('detail_material');
	    var rowCount = table.rows.length;
	      if(rowCount <= 1){
        		Swal.fire(
                  'Just Info!',
                  'There is no row available to delete!',
                  'info'
                )
	          return;
	      }
	        if(ele){
	            ele.parentNode.parentNode.remove();
	        }else{
	            table.deleteRow(rowCount-1);
	    }
    }

    function delRowtable2(ele){
	    var table = document.getElementById('detail_mr');
	    var dm = document.getElementById('detail_material');
	    var rowCount2 = dm.tBodies[0].rows.length;
	    console.log(rowCount2);
	    if(rowCount2 > 0){
					Swal.fire(
                  'Just Info!',
                  'Material has been out, delete first !',
                  'info'
          )
	    		return;
	    }
	    var rowCount = table.rows.length;
	      if(rowCount <= 1){
        		Swal.fire(
                  'Just Info!',
                  'There is no row available to delete!',
                  'info'
                )
	          return;
	      }
	        if(ele){
	            ele.parentNode.parentNode.remove();
	        }else{
	            table.deleteRow(rowCount-1);
	    }

	    reload_table();
    }

  function reload_table()
    {
      $("#tableHeader").DataTable().off('select');
      $("#tableHeader").DataTable().clear().destroy();
    
      load_table()
    }

	function load_table(){
			loadMRHeader();
			loadMRDetail();
  	}
</script>