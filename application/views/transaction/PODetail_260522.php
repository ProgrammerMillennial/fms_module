<div class="row">
  <div class="col-lg-12">
    <div class="card shadow mb-3 border-bottom-primary">
      <div class="card-header">
          <h5 class="m-0 font-weight-bold text-gray-800">
              <b id="pono"></b>
          </h5>
      </div>

      <div class="card-body">
        <div class="row invoice-info">
          <div class="col-sm-3 invoice-col">
              <label id="mino"></label><br>
              <label id="miDate"></label><br>
              <label id="orderDate"></label><br>
              <label id="qtty"></label>
          </div>

          <div class="col-sm-2 invoice-col">
              <label id="rls"></label> <br>
              <label id="style"></label> <br>
              <label id="model"></label><br>
          </div>

          <div class="col-sm-3 invoice-col">
              <label> From</label>
              <address>
                  <label id="desc1" style="font-weight: bold;"></label> <br>
                  <label id="add1"></label> <br>
                  <label id="add2"></label> <br>
              </address>
          </div>
          
          <div class="col-sm-4 invoice-col">
              <label> To</label>
              <address>
                  <label id="desc2" style="font-weight: bold;"></label> <br>
                  <label id="add3"></label> <br>
                  <label id="add4"></label> <br>
              </address>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-5"> 
    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-body">
      <div class="my-3"></div>
        <div class="table-responsive">
            <table class="table table-bordered text-gray-800" id="tableHeader" width="100%" cellspacing="0">
            <thead>
                <tr>
                  <th width="30%">Trans</th>
                  <th>Mi No</th>
                  <th>SJ</th>
                  <th width="20%">Total Qtty</th>
                  <th style="white-space:nowrap; text-align: left;">Barcode</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            </table>
        </div>
        </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-body">
      <div class="my-3"></div>
        <div class="table-responsive">
            <table class="table table-bordered text-gray-800" id="tableDetail" width="100%" cellspacing="0">
            <thead>
                <tr align="center">
                  <th width="1%">Seqn</th>
                  <th width="20%">Kode Material</th>
                  <th width="40%">Nama Material</th>
                  <th>Unit</th>
                  <th class="text-center">Qtty PO</th>
                  <th class="text-center">Qtty MI</th>
                  <th>Price</th>
                  <th>Umcd</th>
                  <th class="text-center">Qtty Inspect</th>
                  <th class="text-center">Balance</th>
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

<?php $this->load->view('transaction/modalInspectEntry');?>


<script type="text/javascript">
  var save_method;
  var dPono;
  var dMino;
  var dMiDate;
  var dNbrn;
  var dStyl;
  var wh;
  var dVend;

  var po;
  var wh;
  var qtty;

  $(document).ready(function() {
    loaderSpinner();
    var URL= window.location.href;
    var arr= URL.split('/');
    po = arr[6];
    mino = arr[7];
    wh = arr[8];
    qtty = arr[9];
    $.ajax({
        url : "<?php echo site_url('Transaction/getDataFromPO')?>/" + po + '/' + wh + '/' + mino,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          swal.close();
          dPono = data.pono;
          dMino = data.mino;
          dMiDate = data.miDate;
          dNbrn = data.rls;
          dStyl = data.style;
          dVend = data.vendid;
          document.getElementById('pono').innerHTML = "<b>PO Number</b> " + data.pono;
          document.getElementById('mino').innerHTML = "<b>MI Number:</b> " + data.mino;
          document.getElementById('miDate').innerHTML = "<b>MI Date:</b> " + data.miDate;
          document.getElementById('orderDate').innerHTML = "<b>Order Date:</b> " + data.orderDate;
          document.getElementById('rls').innerHTML = "<b>Release:</b> " + data.rls;
          document.getElementById('style').innerHTML = "<b>Style:</b> " + data.style;
          document.getElementById('model').innerHTML = "<b>Model:</b> " + data.model;
          document.getElementById('qtty').innerHTML = "<b>Total Qtty:</b> " + qtty;
          document.getElementById('desc1').innerHTML ="<strong>" + data.vend +"</strong>";
          document.getElementById('add1').innerHTML = data.addr1;
          document.getElementById('add2').innerHTML = data.addr2;
          document.getElementById('desc2').innerHTML = "<strong>" + data.vend1 +"</strong>";
          document.getElementById('add3').innerHTML = data.addr3;
          document.getElementById('add4').innerHTML = data.addr4;
            
         },
          error: function (jqXHR, textStatus, errorThrown)
          {
                  Swal.fire(
                    'Error!',
                    'Error get data from ajax !',
                    'error'
                  )
          }
        });
      load_table();
   });

  function load_table(){
    $('#tableHeader').dataTable().fnClearTable();
      var table1 =$('#tableHeader').DataTable({
        "ajax": {
            "type" : "GET",
            "url" : '<?php echo site_url('Transaction/getDataMiHeader')?>/' + po + '/' + mino,
            "dataSrc": function ( json ) {
                return json.data;
            }       
        },
            // ajax: '<?php echo site_url('Transaction/getDataMiHeader')?>/' + po,
        columnDefs: [
            {
                target: 2,
                visible: false
            }          
        ],
        responsive: true,
          bDestroy: true,
          processing: false,
          select: {
                  style: 'single'
                }
      });

      $('#tableHeader').DataTable().on('select', function (e, dt, type, indexes) {
            var idHeader ="";
            idHeader = table1.row(indexes).data()[0];
            console.log(idHeader);
            inspect_detail(idHeader);
          })

      $('#tableDetail').dataTable().fnClearTable();
      var table =$('#tableDetail').DataTable({
            // ajax: '<?php echo site_url('Transaction/loadDetailMI')?>/' + po,
            "ajax": {
                "type" : "GET",
                "url" : '<?php echo site_url('Transaction/loadDetailMI')?>/' + po + '/' + mino +'/' + wh,
                "dataSrc": function ( json ) {
                    return json.data;
                }       
            },
            columnDefs: [
            {
                target: 3,
                visible: false
            },
            {
                target: 4,
                visible: false
            },            
            {
                target: 6,
                visible: false
            },
            {
                target: 7,
                visible: false
            }
        ],
        responsive: true,
          bDestroy: true,
          processing: false,
          select: {
                  style: 'single'
                }
      });

      $('#tableDetail').DataTable().on('select', function (e, dt, type, indexes) {
        var matl =[];
        var matlLength=[];
        matl = table.rows(indexes).data()[0];
        matlLength = table.rows(indexes).data().toArray();

        add_inspect(matl, matlLength.length);
      })
  }

  function reload_table()
    {
      $("#tableHeader").DataTable().off('select');
      $("#tableHeader").DataTable().clear().destroy();
      $("#tableDetail").DataTable().off('select');
      $("#tableDetail").DataTable().clear().destroy();
    
      load_table()
    }

  function add_inspect(matl, mLength)
    {
      save_method = 'add';
      // console.log(dVend);
      document.getElementById('tranno2').value = '';
      document.getElementById('sjno').value = '';
      
      document.getElementById('kemasan').value = '';
      document.getElementById('jmlkem').value = '';
      document.getElementById('qtty2').value = '';

      $('#printall').html('<label><p></p></label><br><a class="btn btn-sm btn-info" title="Cetak"><i class="fas fa-print"></i> Print All</a>');
      $('#button_ok').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="btnSave" onclick="saveTrans()">Save changes</button>');
      $("#header_inspect").find("tr:gt(0)").remove();
      $("#detail_inspect").find("tr:gt(0)").remove();
      $("#header_inspectd").find("tr:gt(0)").remove();
      $("#detail_inspectd").find("tr:gt(0)").remove();
      for (let i = 0; i < mLength; i++) {
            if(matl[9] == 0){
                Toast.fire({
                  icon: 'error',
                  title: 'Balance qtty for inspect 0'
                })
              return;
            }
            var baris_baru = '<tr><td>'+matl[0]+'</td><td>'+matl[1]+'</td><td>'+matl[2]+'</td><td>'+matl[3]+'</td><td>'+matl[9]+'</td><td>'+matl[5]+'</td><td>'+matl[8]+'</td></tr>';
            $("#header_inspect").append(baris_baru);

            $('[name="seqn"]').val(matl[0]);
            $('[name="matcode"]').val(matl[1]);
            $('[name="matname"]').val(matl[2]);
            $('[name="matunit"]').val(matl[3]);
            $('[name="price"]').val(matl[6]);
            $('[name="umcd"]').val(matl[7]);
            $('[name="bal_qty"]').val(matl[9]);
            $('[name="updt"]').val(0);
          }
      if(matl[9] != 0){
        $('#modal_form').modal({backdrop: 'static', keyboard: false})          
        $('#modal_form').modal('show');
        $('.modal-title').text('Inspection Entry');
        $('[name="tgl_inspect"]').val(dMiDate);
        $('[name="ponu"]').val(dPono);
        $('[name="mino2"]').val(dMino);
        $('[name="rls1"]').val(dNbrn);
        $('[name="part1"]').val(dStyl);
        $('[name="wh"]').val(wh);
        $('[name="vend"]').val(dVend);
      }
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

      if(qtty.length > 40){
        saveTrans2();
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
            serial: serial[key].value,
            bal:0
          };

          fData.push(formData);
        }
      }else{
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
            kem: '',
            jmlkem: 0,
            qtty: 0,
            serial: '',
            bal:0
        }
        fData.push(formData);
      }

       const dataPost = {params: fData};
       // console.log(dataPost);
       loaderSpinner();
       $.ajax({
        url : "<?php echo site_url('Transaction/addTransaction')?>",
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
        url : "<?php echo site_url('Transaction/inspectionDetail')?>",
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

  function printBybarcode(id){
      let tipe = id;
      var tipe2;
      var vend = 0;
      if(tipe.substring(0, 3) == 'INH'){
        tipe2 = 'head';
      }else{
        tipe2 = 'line';
      }
      var url = "<?php echo site_url('Transaction/printNewBarcode')?>/"+tipe2+"/"+id+"/"+vend;
      window.open(url);
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

            $('#button_ok').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="btnSave" onclick="saveTrans()">Update changes</button>');
            $('#printall').html('<label><p></p></label><br><a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('+"'"+obj.header['trans']+"'"+')"><i class="fas fa-print"></i> Print All</a>');
            $('#clearall').html('<label><p></p></label><br><a class="btn btn-sm btn-danger" title="Hapus" onClick="delete_all_matl('+"'"+obj.header['trans']+"'"+')"><i class="fas fa-trash"></i> Delete All</a>');

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
        url : "<?php echo site_url('Transaction/addTransaction')?>",
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
    
    var formData = {
          id: $("#tranno2").val(),
          barcode: line,
          wh: $("#wh").val(),
          tgl_inspect: $("#tgl_inspect").val(),
          vend: 0
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
      var url = '<?php echo site_url('Transaction/deleteInByBarcode')?>';
    }else{
      var url = '<?php echo site_url('Transaction/deleteInAll')?>';
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

  function removeFormatting(numberString) {
    // Remove non-numeric characters except decimal point
    return parseFloat(numberString.replace(/[^\d.-]/g, ''));
  }

  // function loadListInspect2(){

  //   var matcode = $("#matcode").val();
  //   var pono = $("#ponu").val();
  //   var mino= $("#mino2").val();
  //   var wh = $("#wh").val();

  //   const fData = [];

  //   var formData = {
  //     matcode: $("#matcode").val(),
  //     pono: $("#ponu").val(),
  //     mino: $("#mino2").val(),
  //     wh: $("#wh").val()
  //   };

  //   fData.push(formData);
  //   const dataPost = {params: fData};

  //     $("#header_inspect").find("tr:gt(0)").remove();
  //     // console.log(id);
  //     $.ajax({
  //       url : "<?php echo site_url('transaction/inspectionDetail2')?>",
  //       type: "POST",
  //       data: dataPost,
  //       success: function(data)
  //       {
  //           const obj = JSON.parse(data);
  //           $('#bal_qty').html("<input type='text' id='bal_qty' name='bal_qty' value ='"+obj.inspect['balqty']+"'>");
  //           var baris_baru = '<tr><td>'+obj.inspect['seqn']+'</td><td>'+obj.inspect['matcode']+'</td><td>'+obj.inspect['matname']+'</td><td>'+obj.inspect['unit']+'</td><td>'+obj.inspect['balqty']+'</td><td>'+obj.inspect['poqty']+'</td><td>'+obj.inspect['inqty']+'</td></tr>';
  //           $("#header_inspect").append(baris_baru);
  //       },
  //       error: function (jqXHR, textStatus, errorThrown)
  //       {
  //                 Swal.fire(
  //                   'Error!',
  //                   'Error while saving the data, re-chek again !',
  //                   'error'
  //                 )
  //       }
  //     });
  // }
</script>