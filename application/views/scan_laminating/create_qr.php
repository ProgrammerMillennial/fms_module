<div class="card shadow">
  <div class="card-body">
    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
              <select class="form-control select2bs4" style="width: 100%;" id="">
                <option value="-">Release</option>
                <option value="MAT">Material Warehouse</option>
                <option value="ENG">Engineering Warehouse</option>
                <option value="IDC">IDC Warehouse</option>
              </select>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <input type="text" id="pono" class="form-control" value="" placeholder="Style"> 
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <input type="text" id="pono" class="form-control" value="" placeholder="Model"> 
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
              <select class="form-control select2bs4" style="width: 100%;" id="">
                <option value="">Factory</option>
                <option value="PM">PM</option>
                <option value="IR2">IR2</option>
              </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3">
        <div class="form-group">
          <input type="text" id="pono" class="form-control" value="" placeholder="Comp. Code"> 
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <input type="text" id="pono" class="form-control" value="" placeholder="Comp. Name"> 
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <input type="text" id="pono" class="form-control" value="" placeholder="Material Name"> 
        </div>
      </div>

      <div class="col-sm-3">
          <a type="button" onclick="rePrintQR()" class="btn btn-primary btn-icon-split">
              <span class="icon text-white-50">
                  <i class="fas fa-print"></i>
              </span>
              <span class="text">RePrint QR Code</span>
          </a>
      </div>

    </div>
  </div>
</div>

<div class="card shadow">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-12">      
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-gray-800 text-center" id="tableDetail" width="100%" cellspacing="0">
            <thead>
                <tr align="center">
                  <th>No</th>
                  <th>Release</th>
                  <th>Style</th>
                  <th>Model</th>
                  <th>Comp.Code</th>
                  <th>Comp.Name</th>
                  <th>Material</th>
                  <th>Balance SPK</th>
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

<?php $this->load->view('scan_laminating/reprint_qr');?>

<script type="text/javascript">
    function rePrintQR()
    {
      // console.log(tgl);
      // save_method = 'add';
      // var e = document.getElementById("wh");
      // var wh = e.value;
      // if(wh == ""){
      //     Swal.fire({
      //       icon: "info",
      //       title: "Oops...",
      //       text: "Please choose warehouse first!"
      //     });
      //     return;
      // }
      // $('[name="warehouse"]').val(wh);
      // $('[name="pono"]').val(pono1);
      // $('[name="mino"]').val(mino);
      // $('[name="midate"]').val(tgl_mi);
      // $('[name="miqty"]').val(ttl_mi);
      // $('#form')[0].reset(); // reset form on modals
      $("#detail_material").find("tr:gt(0)").remove();
      $("#detail_material2").find("tr:gt(0)").remove();
      // $('[name="tgl_inspect"]').val(tgl);
      $('#modal_form').modal({backdrop: 'static', keyboard: false})          
      $('#modal_form').modal('show'); // show bootstrap modal
      $('.modal-title').text('Scan new material'); // Set Title to Bootstrap modal title
      load_table(wh, pono1);
    }
</script>