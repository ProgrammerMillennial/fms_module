<link href="<?php echo base_url(); ?>assets/css/laminating.css" rel="stylesheet">

<!-- <h1 style="text-align:center;">Weather to use CSS Grid for tabbed content (or not)</h1>
<h5 style="text-align:center;">Icons used from <a href="https://www.flaticon.com/packs/weather-219">Flat Icon</a></h5> -->

<!-- from here for used code -->
<div class="products-wrapper">
	<input type="hidden" name="mesin" id="mesin" value="WATERBASE">
	<div class="tabs-wrapper">
		<div class="tab-item">
			<img src="<?php echo base_url(); ?>assets/img/scanning.png" alt="show icon">
			<h6 class="m-0 font-weight-bold text-primary">Scan QR Code</h6>
		</div>
		<div class="tab-item">
			<img src="<?php echo base_url(); ?>assets/img/paper.png" alt="rainbow icon">
			<h6 class="m-0 font-weight-bold text-primary">Create QR Code</h6>
		</div>
	</div>
	<div class="tabbed-content">
		<div class="item-content">
			<?php $this->load->view('scan_laminating/scanner');?>
		</div>
		<div class="item-content">
			<?php $this->load->view('scan_laminating/create_qr');?>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	
	loadDataScan();

	let inputStart, inputStop;

	$("#barcode")[0].onpaste = e => e.preventDefault();
	var lastInput

	let checkValidity = () => {
		if ($("#barcode").val().length < 10) {
		      $("#barcode").val('')
		} else {
		    scanBarcode();
		}
		  timeout = false
		}

	let timeout = false
	$("#barcode").keypress(function (e) {
		if (performance.now() - lastInput > 1000) {
		    $("#barcode").val('')
		}
		lastInput = performance.now();
		if (!timeout) {
		    timeout = setTimeout(checkValidity, 200)
		  }
	});
});

function loadDataScan(){
	var mesin;
	var mesin = $('#mesin').val();
    $('#tableHeader').dataTable().fnClearTable();
      var table1 =$('#tableHeader').DataTable({
        "ajax": {
            "type" : "GET",
            "url" : '<?php echo site_url('transaction/loadTodayLamScan')?>/' + mesin,
            "dataSrc": function ( json ) {
                return json.data;
            }       
        },
        responsive: true,
          bDestroy: true,
          processing: true,
          select: {
                  style: 'single'
                }
      });
}

function scanBarcode(){
	var input = document.getElementById("barcode");
	  input.addEventListener("keypress", function(event) {
	    if (event.key === "Enter") {
		    event.preventDefault();

		  var stat = 1;
		    // var e = document.getElementById("wh");
			// var wh = e.value;
			var tqty = $("#tOqty").val();
			if(tqty == ''){
				tqty = 0;
			}
			const fData = [];
			var formData = {
			  id: $("#tran_out").val(),
			  barcode: $("#barcode").val(),
			  mrno: $("#mrno").val(),
			  qtty:tqty,
			  wh:wh,
			  vend_stat:vend_stat,
			  tgl:$("#mrdate2").val(),
			  part:$("#part").val(),
			  nbrn:$("#release").val(),
			  opcd:$("#proc").val(),
			  fact:$("#fact").val()
	        };
			
			fData.push(formData);
			const dataPost = {params: fData};

			$.ajax({
	        url : "<?php echo site_url('transaction/transbarcodeOut')?>",
	        type: "POST",
	        data: dataPost,
	        success: function(data)
		        { 
		              // console.log(data);
		              const obj = JSON.parse(data);
		              var line;
		              document.getElementById('barcode').value = '';
		              document.getElementById("barcode").focus();
			            if(obj.idHeader != ''){
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

function contentTabs() {
	// set initial tab of choice
	const initialTab = 0;

	// -------------------------------------------------

	// declare vars
	let i;
	// check if container/wrapper exist
	const containerActive = document.getElementsByClassName("tabs-wrapper");
	// put all tab-items into a variable
	const tabButton = document.querySelectorAll(".tab-item");
	// put all item-contents into a variable
	const tabContent = document.querySelectorAll(".item-content");

	if (containerActive.length >= 1) {
		runTabs();
	}

	function runTabs() {
		/* maintenance mode, check amount of tab-items is same as item-content.
-- if isn't, suggest "warn" ways to ommit error messages. */
		function initChecks() {
			// clear all active classes
			clearActive();
			// check element numbers are correct
			if (tabButton.length < tabContent.length) {
				// if there are less buttons than content tabs
				console.warn(
					"You need to have the same amount of tab-item's as you have content-tab's"
				);
				console.group(
					"Paste this emmet shorthand inside the 'tabs-wrapper' div and press enter/tab"
				);
				console.log("div.tab-item{tab-title}");
				console.groupEnd();
			} else if (tabContent.length < tabButton.length) {
				// if there are less content tabs than buttons
				console.warn(
					"You need to have the same amount of content-tab's as you have tab-items's"
				);
				console.group(
					"Paste this emmet shorthand inside the 'tabbed-content' div and press enter/tab"
				);
				console.log(
					"div.item-content>div.hightlights*4>h4{some title}+p{some copy}"
				);
				console.groupEnd();
			} else {
				tabContent[initialTab].classList.add("active");
			}
		}
		initChecks();

		/* self calling function to clear all active classed from group of tabs
    -- if user has added active class for some wierd reason */
		function clearActive() {
			// cycle through all elements with class="tab-item" and remove "active".
			for (i = 0; i < tabContent.length; i++) {
				tabContent[i].classList.remove("active");
			}
		}

		// add event listeners to all elements with tab-item class and wait for click
		for (let tabIndex = 0; tabIndex < tabButton.length; tabIndex++) {
			tabButton[tabIndex].addEventListener("click", function () {
				// clear all active class's from the group of tabs.
				clearActive();
				// select item-content with the same index as the clicked tab-item and add an active class.
				tabContent[tabIndex].classList.toggle("active");
			});
		}
	}
}
contentTabs();
</script>