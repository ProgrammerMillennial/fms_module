<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-gray-800"><b>Material Stock Barcode</b></h5>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2">  
                            <div class="form-group">
                              <label class="font-weight-bold">Date From</label>
                                <div class="input-group date" id="tgl1" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_inspect" id="dt1" />
                                      <div class="input-group-append" data-target="#tgl1" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">  
                            <div class="form-group">
                              <label class="font-weight-bold">Date To</label>
                                <div class="input-group date" id="tgl2" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl2" name="tgl_inspect" id="dt2"/>
                                      <div class="input-group-append" data-target="#tgl2" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="font-weight-bold">Warehouse</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="wh"></select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="font-weight-bold">Factory</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="fact">
                                        <!-- <option selected="selected"></option> -->
                                        <option selected="selected" value="ALL">ALL</option>
                                        <option value="IR2">Factory IR2</option>
                                        <option value="PM">Factory PM</option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="font-weight-bold">Material Code</label>
                                <input type="text" id="matl" class="form-control" value=""> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3">
            <div class="card shadow mb-4">
<!--                 <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                </div> -->
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-gray-800" id="tableHeader" width="100%" cellspacing="0">
                            <thead>
                                <tr align="center">
                                  <th width="2%">Group ID</th>
                                  <th width="10%">Name</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card shadow mb-4">
<!--                 <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                </div> -->
                <div class="card-body">
                    <div class="row justify-content-end">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="font-weight-bold"></label>
                                <a type="button" onclick="closeBarcode()" class="btn btn-primary btn-icon-split">
                                    <span class="text">Closing Barcode</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-gray-800 text-center" id="tableDetail" width="100%" cellspacing="0">
                            <thead>
                                <tr align="center">
                                  <th>No</th>
                                  <th>Material Name</th>
                                  <th>Type</th>
                                  <th>Wide</th>
                                  <th>Spec</th>
                                  <th>Color</th>
                                  <th>Unit</th>
                                  <th>Begin</th>
                                  <th>In</th>
                                  <th>Out</th>
                                  <th>Balance</th>
                                  <th>Code</th>
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
        <div class="col-lg-12">
            <div class="card shadow mb-4">
<!--                 <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                </div> -->
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-gray-800 text-center" id="tableDetail1" width="100%" cellspacing="0">
                            <thead>
                                <tr align="center">
                                  <th>No</th>
                                  <th>Serial</th>
                                  <th>Material Name</th>
                                  <th>Type</th>
                                  <th>Wide</th>
                                  <th>Spec</th>
                                  <th>Color</th>
                                  <th>Unit</th>
                                  <th>MI Date</th>
                                  <th>In Qtty</th>
                                  <th>Out Qtty</th>
                                  <th>Balance Qtty</th>
                                  <th>Release</th>
                                  <th>PO No</th>
                                  <th>Style</th>
                                  <th>Model</th>
                                  <th>Qtty Pairs</th>
                                  <th>Code</th>
                                  <th>Factory</th>
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
<?php $this->load->view('report/barcode_stock_modal');?>

<script type="text/javascript">
    $(document).ready(function() {
        var dt1;
        var dt2; 
        var matl;
        var fact;
        $("#cari").click(function() { 
            reload_table();
        });
      loadWarehouse();
   });

function reload_table(){

      $("#tableHeader").DataTable().off('select');
      $("#tableHeader").DataTable().clear().destroy();
      $("#tableDetail").DataTable().off('select');
      $("#tableDetail").DataTable().clear().destroy();
      $("#tableDetail1").DataTable().off('select');
      $("#tableDetail1").DataTable().clear().destroy();
    
      loadGroupCode();
}

function loadGroupCode(){
    $('#tableHeader').dataTable().fnClearTable();
      var table1 =$('#tableHeader').DataTable({
        "ajax": {
            "type" : "GET",
            "url" : '<?php echo site_url('report/getGroupMatl')?>',
            "dataSrc": function ( json ) {
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

      $('#tableHeader').DataTable().on('select', function (e, dt, type, indexes) {
            if (type === 'row') {
                var selectedRow = table1.row(indexes).node();
                var kode ="";
                kode = table1.row(indexes).data()[0];
                loadDetailMatl(kode);
                setTimeout(function() {
                    table1.rows(selectedRow).deselect();
                }, 7000);
            }
      })
}

function loadDetailMatl(kode){
    $("#tableDetail").DataTable().off('select');
    $("#tableDetail").DataTable().clear().destroy();

    var dt1 = $('#dt1').val();
    var dt2 = $('#dt2').val(); 
    var matl = $('#matl').val();
    var f = document.getElementById("fact");
    var fact = f.value;
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
    if(dt1 == "" || dt2 == ""){
        Swal.fire({
                          icon: "info",
                          title: "Oops...",
                          text: "Please choose date first!"
                 });
        return;
    }

    if(matl == ""){
        kode = kode;
    }else{
        kode = matl;
    }

    $('#tableDetail').dataTable().fnClearTable();
      var table2 =$('#tableDetail').DataTable({
        "ajax": {
            "type" : "GET",
            "url" : '<?php echo site_url('report/getStokDetailMatl')?>/load/' + wh + '/' + fact +'/' + dt1 +'/' + dt2 +'/' + kode ,
            "dataSrc": function ( json ) {
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

      $('#tableDetail').DataTable().on('select', function (e, dt, type, indexes) {
            if (type === 'row') {
                var selectedRow = table2.row(indexes).node();
                var kode ="";
                kode = table2.row(indexes).data()[11];
                loadSubDetailMatl(kode, dt1, dt2, fact, wh);
                setTimeout(function() {
                    table2.rows(selectedRow).deselect();
                }, 7000);
            }
      })
}

function loadSubDetailMatl(kode, dt1, dt2, fact, wh){
    $("#tableDetail1").DataTable().off('select');
    $("#tableDetail1").DataTable().clear().destroy();

    $('#tableDetail1').dataTable().fnClearTable();
      var table2 =$('#tableDetail1').DataTable({
        "ajax": {
            "type" : "GET",
            "url" : '<?php echo site_url('report/getStokSubDetailMatl')?>/' + kode + '/' + dt1 +'/' + dt2 +'/' + fact +'/' + wh,
            "dataSrc": function ( json ) {
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

function closeBarcode()
{
    $('[name="wh2"]').val(wh);
    $('#button_ok').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="btnSave" onclick="saveTrans()">Save changes</button>');
    $('#modal_form').modal({backdrop: 'static', keyboard: false})          
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Closing Barcode Material'); // Set Title to Bootstrap modal title
}

function saveTrans()
{
    var f = document.getElementById("fact2");
    var fact = f.value;
    var e = document.getElementById("wh2");
    var wh2 = e.value;

    const fData = [];
    var formData = {
        stat: 'CLOSE',
        wh: wh2,
        fact: fact,
        periode: $("#dt3").val(),
        matl: 'ALL'
        };

        fData.push(formData);
    const dataPost = {params: fData};
    loaderSpinner();
    $.ajax({
        url : "<?php echo site_url('report/ClosingMaterialBarcode')?>",
        type: "POST",
        data: dataPost,
        success: function(data)
        { 
            swal.close();
              // console.log(data);
            const obj = JSON.parse(data);
            Swal.fire(
                        obj.title,
                        obj.message,
                        obj.status
                     )

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