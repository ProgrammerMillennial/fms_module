<div class="col-lg-12">
	<div class="card shadow mb-4 border-left-info">
		<div class="card-header">
			<h5 class="m-0 font-weight-bold text-gray-800">
	        	<b>Closing Transaction By Date</b>
	        </h5>
		</div>
	   <div class="card-body">
            <div class="row">
            	<div class="col-sm-4">
				<div class="form-group">
	              <label class="font-weight-bold">Close Date :</label>
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
			            <label class="font-weight-bold">Flag</label>
			                <select class="form-control select2bs4" style="width: 100%;" id="flag" onChange="OnSelectedIndexChange()">
			                    <option value="MI">MI</option>
			                    <option value="MO">MO</option>
			                </select>
			        </div>
		        </div>
            </div>
            <br><br>
	    	<div class="row">
	        	<div class="col-sm-12">
					<div id="calendar"></div>
				</div>
	        </div>
	        <?php //$this->load->view('button');?>
	    </div>
	</div>
</div>

<script>
   $(document).ready(function() {
     $("#simpan").click(function() {
   	     var tgl = $("#dt1").val();
		 var e = document.getElementById("flag");
		 var flag = e.value;

	     if(tgl =='' || flag == ''){
		  	Toast.fire({
		                icon: 'error',
		                title: 'Tanggal atau flag masih kosong!'
		              })
	          return;
	      }
			dataSave();
      })
	  
	  var e = document.getElementById("flag");
	  var flag = e.value;
      reLoadDate(flag);
   });

	function OnSelectedIndexChange(){
		var flag2 = document.getElementById('flag').value;
		reLoadDate(flag2);
	}

  function dataSave(){
  	const fData = [];
    var formData = {
    	tgl: $("#dt1").val(),
        flag: $("#flag").val(),
        stat: '1',
    };
	fData.push(formData);
	const dataPost = {params: fData};

    	$.ajax({
        url : "<?php echo site_url('report/saveClosingDate')?>",
        type: "POST",
        data: dataPost,
        success: function(data)
        { 
              console.log(data);
              const obj = JSON.parse(data);
              reLoadDate(obj.flag);
	          Toast.fire({
	          	icon: obj.status,
	          	title: obj.message
	          })
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

	function reLoadDate(flag){
		  $(function () {

		    var Calendar = FullCalendar.Calendar;
		    // var Draggable = FullCalendar.Draggable;

		    var calendarEl = document.getElementById('calendar');

		    var calendar = new Calendar(calendarEl, {
		      headerToolbar: {
		        left  : 'prev,next today',
		        center: 'title',
		        // right : 'dayGridMonth,timeGridWeek,timeGridDay'
		        right : ''
		      },
		      themeSystem: 'bootstrap',

		      events: {
		        url: "<?php echo site_url('cglobal/loadClosingDate')?>/"+ flag,
		        success: function(response) { 
		            // Instead of returning the raw response, return only the data 
		            // element Fullcalendar wants
		            return response.data;
		      	}
		  	  }
		    });

		    calendar.render();
		    // $('#calendar').fullCalendar()

		  })
	}
</script>